<?php

use App\Models\Evento;
use App\Models\Pergunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('impede usuario anonimo de votar', function () {
    $pergunta = Pergunta::factory()->create();

    $this->post(route('perguntas.votar', $pergunta))
        ->assertRedirect(route('login.create'));

    $this->assertDatabaseCount('pergunta_user', 0);
});

it('registra o voto na tabela pivot quando o usuario ainda nao votou', function () {
    $user = User::factory()->create();
    $pergunta = Pergunta::factory()->create();

    $this->actingAs($user)
        ->from(route('eventos.show', $pergunta->evento_id))
        ->post(route('perguntas.votar', $pergunta))
        ->assertRedirect(route('eventos.show', $pergunta->evento_id))
        ->assertSessionHas('sucesso');

    $this->assertDatabaseHas('pergunta_user', [
        'pergunta_id' => $pergunta->id,
        'user_id'     => $user->id,
    ]);
});

it('remove o voto quando o usuario ja votou (toggle)', function () {
    $user = User::factory()->create();
    $pergunta = Pergunta::factory()->create();
    $pergunta->votos()->attach($user->id);

    $this->actingAs($user)
        ->post(route('perguntas.votar', $pergunta))
        ->assertRedirect();

    $this->assertDatabaseMissing('pergunta_user', [
        'pergunta_id' => $pergunta->id,
        'user_id'     => $user->id,
    ]);
});

it('nao permite voto duplicado do mesmo usuario na mesma pergunta', function () {
    $user = User::factory()->create();
    $pergunta = Pergunta::factory()->create();
    $pergunta->votos()->attach($user->id);

    expect(fn () => $pergunta->votos()->attach($user->id))
        ->toThrow(Illuminate\Database\QueryException::class);
});

it('expoe os relacionamentos N:M nos dois modelos', function () {
    $user = User::factory()->create();
    $pergunta = Pergunta::factory()->create();
    $user->perguntasVotadas()->attach($pergunta->id);

    expect($pergunta->votos()->count())->toBe(1)
        ->and($user->perguntasVotadas()->first()->id)->toBe($pergunta->id);
});

it('ordena as perguntas do evento por votos de forma decrescente', function () {
    $this->withoutVite();

    $evento = Evento::factory()->create();
    $poucos = Pergunta::factory()->for($evento)->create(['texto' => 'Pergunta com 1 voto']);
    $muitos = Pergunta::factory()->for($evento)->create(['texto' => 'Pergunta com 3 votos']);
    $nenhum = Pergunta::factory()->for($evento)->create(['texto' => 'Pergunta sem votos']);

    $poucos->votos()->attach(User::factory()->count(1)->create());
    $muitos->votos()->attach(User::factory()->count(3)->create());

    $perguntas = $this->get(route('eventos.show', $evento))
        ->assertOk()
        ->viewData('perguntas');

    expect($perguntas->pluck('id')->all())->toBe([$muitos->id, $poucos->id, $nenhum->id])
        ->and($perguntas->first()->votos_count)->toBe(3);
});

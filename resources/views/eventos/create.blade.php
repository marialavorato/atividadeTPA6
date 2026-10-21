@extends('layouts.app')

@section('title', 'Criar Evento — FalaQ')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md text-gray-800">
    <h2 class="text-2xl font-bold mb-6">📅 Criar Novo Evento</h2>

    <form action="{{ route('eventos.store') }}" method="POST" novalidate>
        @csrf

        {{-- Título --}}
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1">Título do Evento</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}"
                   placeholder="Ex: Palestra de Laravel"
                   class="w-full border rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('titulo') border-red-500 @else border-gray-300 @enderror">
            @error('titulo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descrição --}}
        <div class="mb-4">
            <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-1">Descrição do Evento</label>
            <textarea name="descricao" id="descricao" rows="4"
                      placeholder="Conte um pouco sobre o evento..."
                      class="w-full border rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500
                             @error('descricao') border-red-500 @else border-gray-300 @enderror">{{ old('descricao') }}</textarea>
            @error('descricao')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Data --}}
        <div class="mb-6">
            <label for="data_evento" class="block text-sm font-semibold text-gray-700 mb-1">Data</label>
            <input type="date" name="data_evento" id="data_evento" value="{{ old('data_evento') }}"
                   class="w-full border rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('data_evento') border-red-500 @else border-gray-300 @enderror">
            @error('data_evento')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Criar Evento
        </button>
    </form>
</div>
@endsection

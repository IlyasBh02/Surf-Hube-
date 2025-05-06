@extends('layouts.coach')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Modifier le cours</h1>

    <form action="{{ route('coach.cours.update', $cours->id) }}" method="POST" class="max-w-2xl bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="titre" class="block text-sm font-medium text-gray-700">Titre du cours</label>
            <input type="text" name="titre" id="titre" value="{{ old('titre', $cours->titre) }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('titre')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $cours->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="datetime-local" name="date" id="date" value="{{ old('date', $cours->date->format('Y-m-d\TH:i')) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="niveau" class="block text-sm font-medium text-gray-700">Niveau</label>
                <select name="niveau" id="niveau" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="debutant" {{ old('niveau', $cours->niveau) == 'debutant' ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediaire" {{ old('niveau', $cours->niveau) == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="avance" {{ old('niveau', $cours->niveau) == 'avance' ? 'selected' : '' }}>Avancé</option>
                </select>
                @error('niveau')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité maximale</label>
                <input type="number" name="capacite" id="capacite" min="1" value="{{ old('capacite', $cours->capacite) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('capacite')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prix" class="block text-sm font-medium text-gray-700">Prix (€)</label>
                <input type="number" name="prix" id="prix" min="0" step="0.01" value="{{ old('prix', $cours->prix) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('prix')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('coach.cours') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Annuler
            </a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection 
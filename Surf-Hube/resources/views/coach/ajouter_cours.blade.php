@extends('layouts.coach')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Ajouter un nouveau cours</h1>

    <form action="{{ route('coach.cours.store') }}" method="POST" class="max-w-2xl bg-white p-6 rounded-lg shadow">
        @csrf
        
        <div class="mb-4">
            <label for="titre" class="block text-sm font-medium text-gray-700">Titre du cours</label>
            <input type="text" name="titre" id="titre" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="datetime-local" name="date" id="date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="niveau" class="block text-sm font-medium text-gray-700">Niveau</label>
                <select name="niveau" id="niveau" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité maximale</label>
                <input type="number" name="capacite" id="capacite" min="1" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="prix" class="block text-sm font-medium text-gray-700">Prix (€)</label>
                <input type="number" name="prix" id="prix" min="0" step="0.01" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Créer le cours
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Gestion des Surfeurs</h1>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <input type="text" placeholder="Rechercher un surfeur..." class="px-4 py-2 border rounded-lg">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-2">Rechercher</button>
            </div>
            <button class="bg-green-500 text-white px-4 py-2 rounded-lg">+ Ajouter un surfeur</button>
        </div>

        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 text-left">Nom</th>
                    <th class="py-2 px-4 text-left">Email</th>
                    <th class="py-2 px-4 text-left">Niveau</th>
                    <th class="py-2 px-4 text-left">Inscriptions</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example rows - Replace with actual data -->
                <tr class="border-t">
                    <td class="py-2 px-4">Marie Vague</td>
                    <td class="py-2 px-4">marie@wave.com</td>
                    <td class="py-2 px-4">Débutant</td>
                    <td class="py-2 px-4">3</td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500 hover:text-blue-700 mr-2">Éditer</button>
                        <button class="text-red-500 hover:text-red-700">Supprimer</button>
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="py-2 px-4">Jean Océan</td>
                    <td class="py-2 px-4">jean@ocean.com</td>
                    <td class="py-2 px-4">Intermédiaire</td>
                    <td class="py-2 px-4">5</td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500 hover:text-blue-700 mr-2">Éditer</button>
                        <button class="text-red-500 hover:text-red-700">Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-center">
            <nav class="inline-flex rounded-lg shadow">
                <button class="px-3 py-1 rounded-l-lg bg-gray-200">Précédent</button>
                <button class="px-3 py-1 bg-blue-500 text-white">1</button>
                <button class="px-3 py-1 bg-gray-200">2</button>
                <button class="px-3 py-1 bg-gray-200">3</button>
                <button class="px-3 py-1 rounded-r-lg bg-gray-200">Suivant</button>
            </nav>
        </div>
    </div>
</div>
@endsection 
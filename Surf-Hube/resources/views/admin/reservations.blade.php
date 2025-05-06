@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Gestion des Réservations</h1>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <input type="text" placeholder="Rechercher une réservation..." class="px-4 py-2 border rounded-lg">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-2">Rechercher</button>
            </div>
            <div class="flex space-x-2">
                <select class="px-4 py-2 border rounded-lg">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmée</option>
                    <option value="cancelled">Annulée</option>
                </select>
                <button class="bg-green-500 text-white px-4 py-2 rounded-lg">+ Nouvelle réservation</button>
            </div>
        </div>

        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Surfeur</th>
                    <th class="py-2 px-4 text-left">Cours</th>
                    <th class="py-2 px-4 text-left">Date</th>
                    <th class="py-2 px-4 text-left">Statut</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example rows - Replace with actual data -->
                <tr class="border-t">
                    <td class="py-2 px-4">#1234</td>
                    <td class="py-2 px-4">Marie Vague</td>
                    <td class="py-2 px-4">Cours Débutant</td>
                    <td class="py-2 px-4">2024-02-20 10:00</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Confirmée</span>
                    </td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500 hover:text-blue-700 mr-2">Éditer</button>
                        <button class="text-red-500 hover:text-red-700">Annuler</button>
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="py-2 px-4">#1235</td>
                    <td class="py-2 px-4">Jean Océan</td>
                    <td class="py-2 px-4">Cours Intermédiaire</td>
                    <td class="py-2 px-4">2024-02-21 14:00</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">En attente</span>
                    </td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500 hover:text-blue-700 mr-2">Éditer</button>
                        <button class="text-red-500 hover:text-red-700">Annuler</button>
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
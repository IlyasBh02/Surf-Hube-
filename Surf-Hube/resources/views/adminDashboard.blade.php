@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tableau de bord</h1>
    <p class="mb-6">Bienvenue sur le panneau d'administration du club Surf-Hube.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Utilisateurs</h3>
            <p class="text-3xl font-bold text-blue-500">150</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Cours</h3>
            <p class="text-3xl font-bold text-green-500">25</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Réservations</h3>
            <p class="text-3xl font-bold text-yellow-500">75</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Coachs</h3>
            <p class="text-3xl font-bold text-purple-500">12</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Dernières réservations</h2>
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left">Surfeur</th>
                        <th class="py-2 px-4 text-left">Cours</th>
                        <th class="py-2 px-4 text-left">Date</th>
                        <th class="py-2 px-4 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="py-2 px-4">Marie Vague</td>
                        <td class="py-2 px-4">Cours Débutant</td>
                        <td class="py-2 px-4">2024-02-20</td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Confirmée</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Cours populaires</h2>
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left">Cours</th>
                        <th class="py-2 px-4 text-left">Coach</th>
                        <th class="py-2 px-4 text-left">Inscriptions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="py-2 px-4">Cours Débutant</td>
                        <td class="py-2 px-4">Alex Surfer</td>
                        <td class="py-2 px-4">25</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



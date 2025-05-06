@extends('layouts.coach')

@section('content')
<h1 class="text-2xl font-bold mb-4">Mes réservations</h1>
<table class="min-w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-4 text-left">Cours</th>
            <th class="py-2 px-4">Surfeur</th>
            <th class="py-2 px-4">Date</th>
            <th class="py-2 px-4">Statut</th>
        </tr>
    </thead>
    <tbody>
        <!-- Exemple -->
        <tr>
            <td class="py-2 px-4">Surf Débutant</td>
            <td class="py-2 px-4">Léo Rider</td>
            <td class="py-2 px-4">2025-05-01</td>
            <td class="py-2 px-4">Confirmé</td>
        </tr>
    </tbody>
</table>
@endsection

@extends('layouts.coach')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Tableau de Bord Coach</h1>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total des Cours</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalCours }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total des Réservations</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalReservations }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total des Surfeurs</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalSurfeurs }}</p>
        </div>
    </div>

    <!-- Prochains Cours -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Prochains Cours</h2>
            @if($cours->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Places</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cours as $c)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->date->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->niveau }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->places_disponibles }}/{{ $c->places_max }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('coach.edit_cours', $c->id) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Aucun cours à venir.</p>
            @endif
        </div>
    </div>

    <!-- Dernières Réservations -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Dernières Réservations</h2>
            @if($reservations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surfeur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->surfer->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->cours->niveau }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->cours->date->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($reservation->statut === 'confirmé') bg-green-100 text-green-800
                                            @elseif($reservation->statut === 'en attente') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $reservation->statut }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="updateStatut({{ $reservation->id }})" class="text-blue-600 hover:text-blue-900">
                                            Modifier le statut
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Aucune réservation récente.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatut(reservationId) {
    // Implémenter la logique de mise à jour du statut
    console.log('Mise à jour du statut pour la réservation:', reservationId);
}
</script>
@endpush
@endsection

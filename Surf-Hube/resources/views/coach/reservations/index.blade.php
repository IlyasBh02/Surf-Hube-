@extends('layouts.coach')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Réservations</h1>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left">Cours</th>
                    <th class="py-3 px-6">Surfeur</th>
                    <th class="py-3 px-6">Date de réservation</th>
                    <th class="py-3 px-6">Statut</th>
                    <th class="py-3 px-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reservations as $r)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $r->cours->titre }}</td>
                    <td class="py-3 px-6">{{ $r->surfer->name }}</td>
                    <td class="py-3 px-6">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-6">
                        <span class="px-2 py-1 rounded text-sm 
                            @if($r->statut == 'Confirmé') bg-green-100 text-green-800
                            @elseif($r->statut == 'En attente') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $r->statut }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        <form action="{{ route('coach.reservations.update_statut', $r->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <select name="statut" onchange="this.form.submit()" class="rounded border-gray-300 text-sm">
                                <option value="En attente" {{ $r->statut == 'En attente' ? 'selected' : '' }}>En attente</option>
                                <option value="Confirmé" {{ $r->statut == 'Confirmé' ? 'selected' : '' }}>Confirmé</option>
                                <option value="Annulé" {{ $r->statut == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 px-6 text-center text-gray-500">Aucune réservation</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
</div>
@endsection 
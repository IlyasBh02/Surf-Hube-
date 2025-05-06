@extends('layouts.coach')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Mes Cours</h1>
        <a href="{{ route('coach.ajouter_cours') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            ➕ Ajouter un cours
        </a>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left">Titre</th>
                    <th class="py-3 px-6">Date</th>
                    <th class="py-3 px-6">Niveau</th>
                    <th class="py-3 px-6">Capacité</th>
                    <th class="py-3 px-6">Participants</th>
                    <th class="py-3 px-6">Prix</th>
                    <th class="py-3 px-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cours as $c)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $c->titre }}</td>
                    <td class="py-3 px-6">{{ $c->date->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-6">
                        <span class="px-2 py-1 rounded text-sm 
                            @if($c->niveau == 'debutant') bg-blue-100 text-blue-800
                            @elseif($c->niveau == 'intermediaire') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($c->niveau) }}
                        </span>
                    </td>
                    <td class="py-3 px-6">{{ $c->capacite }}</td>
                    <td class="py-3 px-6">{{ $c->participants }} / {{ $c->capacite }}</td>
                    <td class="py-3 px-6">{{ number_format($c->prix, 2) }} €</td>
                    <td class="py-3 px-6">
                        <a href="{{ route('coach.edit_cours', $c->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">Modifier</a>
                        <form action="{{ route('coach.delete_cours', $c->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 px-6 text-center text-gray-500">Aucun cours programmé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $cours->links() }}
    </div>
</div>
@endsection 
@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Gestion des Utilisateurs</h1>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <input type="text" placeholder="Rechercher un utilisateur..." class="px-4 py-2 border rounded-lg">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-2">Rechercher</button>
            </div>
            <button class="bg-green-500 text-white px-4 py-2 rounded-lg">+ Ajouter un utilisateur</button>
        </div>

        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 text-left">Nom</th>
                    <th class="py-2 px-4 text-left">Email</th>
                    <th class="py-2 px-4 text-left">Rôle</th>
                    <th class="py-2 px-4 text-left">Statut</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $user->name }} {{ $user->prenom }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-sm 
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'coach') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        @if($user->role === 'coach')
                            <span class="px-2 py-1 rounded text-sm 
                                @if($user->is_approved) bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $user->is_approved ? 'Approuvé' : 'En attente' }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        @if($user->role !== 'admin')
                            <form action="{{ route('admin.users.promote', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-purple-500 hover:text-purple-700 mr-2" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir promouvoir cet utilisateur en administrateur ?')">
                                    Promouvoir admin
                                </button>
                            </form>
                        @endif
                        
                        @if($user->role === 'coach' && !$user->is_approved)
                            <form action="{{ route('admin.coaches.approve', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-500 hover:text-green-700 mr-2">
                                    Approuver
                                </button>
                            </form>
                        @endif
                        
                        <button class="text-red-500 hover:text-red-700" 
                            onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">
                            Supprimer
                        </button>
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection 
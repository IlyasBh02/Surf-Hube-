@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des Coachs</h1>
        <div class="flex space-x-4">
            <input type="text" placeholder="Rechercher un coach..." class="px-4 py-2 border rounded-lg">
            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">Ajouter un coach</button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($coaches as $coach)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $coach->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $coach->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $coach->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $coach->is_approved ? 'Approuvé' : 'En attente' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $coach->courses_count ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        @if(!$coach->is_approved)
                        <form action="{{ route('admin.coaches.approve', $coach) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900">Approuver</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.coaches.reject', $coach) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900">Rejeter</button>
                        </form>
                        <a href="{{ route('admin.coaches.edit', $coach) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coaches->links() }}
    </div>
</div>
@endsection 
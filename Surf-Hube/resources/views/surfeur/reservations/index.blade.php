@extends('layouts.dashboard')

@section('title', 'My Reservations')
@section('dashboard-type', 'surfeur')
@section('user-role', 'surfeur')
@section('user-name', Auth::user()->name)
@section('status-message', 'Surfeur')

@section('sidebar-menu')
    @include('partials.surfeur-sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Reservations</h1>
        <p class="mt-1 text-gray-600">Manage your surf course bookings</p>
    </div>

    <!-- Reservation Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Confirmed Reservations -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-5 bg-green-50 border-b border-green-100">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3">
                        <i class="fa-solid fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-green-800">Confirmed</h3>
                        <p class="text-2xl font-bold text-green-900">{{ $confirmedCount }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-sm text-gray-600">
                Confirmed bookings are ready to attend
            </div>
        </div>

        <!-- Pending Reservations -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-5 bg-yellow-50 border-b border-yellow-100">
                <div class="flex items-center">
                    <div class="rounded-full bg-yellow-100 p-3">
                        <i class="fa-solid fa-clock text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-yellow-800">Pending</h3>
                        <p class="text-2xl font-bold text-yellow-900">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-sm text-gray-600">
                Pending bookings are awaiting confirmation
            </div>
        </div>

        <!-- Cancelled Reservations -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-5 bg-red-50 border-b border-red-100">
                <div class="flex items-center">
                    <div class="rounded-full bg-red-100 p-3">
                        <i class="fa-solid fa-ban text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-red-800">Cancelled</h3>
                        <p class="text-2xl font-bold text-red-900">{{ $cancelledCount }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-sm text-gray-600">
                Cancelled bookings will not be held
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
        <form method="GET" action="{{ route('surfer.reservations') }}" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <select id="time" name="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Time</option>
                    <option value="upcoming" {{ request('time') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="past" {{ request('time') == 'past' ? 'selected' : '' }}>Past</option>
                </select>
            </div>
            <div class="md:self-end">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Reservation History</h2>
        </div>
        
        @if($reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coach</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->course->title }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                        {{ Str::limit($reservation->course->description, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            {{ substr($reservation->course->coach->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if(isset($reservation->course->coach))
                                                    <a href="{{ route('courses.coach', $reservation->course->coach) }}" class="hover:text-blue-600 transition">
                                                        {{ $reservation->course->coach->name }}
                                                    </a>
                                                @else
                                                    Unknown
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $reservation->course->date->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $reservation->course->date->format('g:i A') }}
                                        ({{ $reservation->course->duration }} min)
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('surfer.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        View
                                    </a>
                                    
                                    @if($reservation->status === 'confirmed' && $reservation->course->date > now())
                                        <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        @else
            <div class="p-10 text-center">
                <div class="flex justify-center">
                    <div class="rounded-full bg-blue-100 p-6 mb-4">
                        <i class="fa-solid fa-calendar text-blue-500 text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No reservations found</h3>
                <p class="text-gray-600 mb-6">You don't have any reservations matching the selected filters.</p>
                <a href="{{ route('courses.browse') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-search mr-2"></i> Browse Courses
                </a>
            </div>
        @endif
    </div>
@endsection 
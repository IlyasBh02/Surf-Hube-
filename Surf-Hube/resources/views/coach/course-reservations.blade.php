@extends('layouts.dashboard')

@section('title', 'Course Reservations')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    <a href="{{ route('coach.dashboard') }}" class="sidebar-link {{ request()->routeIs('coach.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('coach.courses.index') ?? '#' }}" class="sidebar-link {{ request()->routeIs('coach.courses*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>My Courses</span>
    </a>
    <a href="{{ route('coach.courses.create') ?? '#' }}" class="sidebar-link {{ request()->routeIs('coach.courses.create') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-plus sidebar-icon"></i>
        <span>Create Course</span>
    </a>
    <a href="{{ route('coach.reservations') ?? '#' }}" class="sidebar-link {{ request()->routeIs('coach.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>Reservations</span>
        @if($pendingReservationsCount ?? 0 > 0)
            <span class="ml-auto bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $pendingReservationsCount ?? 0 }}</span>
        @endif
    </a>
    <a href="{{ route('coach.profile') ?? '#' }}" class="sidebar-link {{ request()->routeIs('coach.profile*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user sidebar-icon"></i>
        <span>My Profile</span>
    </a>
@endsection

@section('page-title', 'Course Reservations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Course Reservations</h1>
            <p class="text-gray-600">Manage reservations for <span class="font-medium">{{ $course->title }}</span></p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('coach.courses.show', $course->id) }}" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fa-solid fa-eye mr-2"></i> View Course
            </a>
            <a href="{{ route('coach.reservations') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> All Reservations
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Course Information Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Course Details</h2>
                </div>
                @if($course->date->isFuture())
                    <a href="{{ route('coach.courses.edit', $course->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fa-solid fa-edit mr-1"></i> Edit Course
                    </a>
                @endif
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Date & Time</h3>
                    <p class="mt-1 text-sm text-gray-900">
                        <i class="fa-solid fa-calendar-day text-gray-400 mr-1"></i>
                        {{ $course->date->format('l, d F Y') }}
                    </p>
                    <p class="mt-1 text-sm text-gray-900">
                        <i class="fa-solid fa-clock text-gray-400 mr-1"></i>
                        {{ $course->date->format('H:i') }} - {{ $course->date->addMinutes($course->duration)->format('H:i') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Capacity</h3>
                    <p class="mt-1 text-sm text-gray-900">
                        <i class="fa-solid fa-users text-gray-400 mr-1"></i>
                        {{ $course->available_places }} max attendees
                    </p>
                    <p class="mt-1 text-sm text-gray-900">
                        <i class="fa-solid fa-hourglass-half text-gray-400 mr-1"></i>
                        {{ $course->duration }} minutes
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="mt-1">
                        @if($course->date->isPast())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Completed
                            </span>
                        @elseif($course->date->isFuture())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Upcoming
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                In Progress
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total</h3>
                <p class="text-3xl font-bold text-gray-700">{{ count($reservations) }}</p>
                <p class="text-sm text-gray-500">Reservations</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-500">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Confirmed</h3>
                <p class="text-3xl font-bold text-green-600">{{ $reservations->where('status', 'confirmed')->count() }}</p>
                <p class="text-sm text-gray-500">Reservations</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-500">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $reservations->where('status', 'pending')->count() }}</p>
                <p class="text-sm text-gray-500">Reservations</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-red-500">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Cancelled</h3>
                <p class="text-3xl font-bold text-red-600">{{ $reservations->where('status', 'cancelled')->count() }}</p>
                <p class="text-sm text-gray-500">Reservations</p>
            </div>
        </div>
    </div>

    <!-- Capacity Indicator -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Capacity</h3>
        
        @php
            $confirmedCount = $reservations->where('status', 'confirmed')->count();
            $pendingCount = $reservations->where('status', 'pending')->count();
            $totalCapacity = $course->available_places;
            $confirmedPercentage = $totalCapacity > 0 ? ($confirmedCount / $totalCapacity) * 100 : 0;
            $pendingPercentage = $totalCapacity > 0 ? ($pendingCount / $totalCapacity) * 100 : 0;
            $availablePercentage = 100 - $confirmedPercentage - $pendingPercentage;
        @endphp
        
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="flex h-4 rounded-full overflow-hidden">
                @if($confirmedPercentage > 0)
                    <div class="bg-green-600 h-4" style="width: {{ $confirmedPercentage }}%"></div>
                @endif
                
                @if($pendingPercentage > 0)
                    <div class="bg-yellow-400 h-4" style="width: {{ $pendingPercentage }}%"></div>
                @endif
            </div>
        </div>
        
        <div class="flex justify-between mt-2 text-sm text-gray-600">
            <div>
                <span class="inline-block w-3 h-3 bg-green-600 rounded-full mr-1"></span>
                Confirmed: {{ $confirmedCount }} ({{ round($confirmedPercentage) }}%)
            </div>
            <div>
                <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-1"></span>
                Pending: {{ $pendingCount }} ({{ round($pendingPercentage) }}%)
            </div>
            <div>
                Available: {{ $totalCapacity - $confirmedCount - $pendingCount }} 
                ({{ round($availablePercentage) }}%)
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    @if(count($reservations) > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Reservations List</h3>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Surfer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reserved On
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reservations as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                        @if($reservation->surfeur->profile_picture)
                                            <img src="{{ asset('storage/' . $reservation->surfeur->profile_picture) }}" alt="{{ $reservation->surfeur->name }}" class="h-10 w-10 rounded-full">
                                        @else
                                            <i class="fa-solid fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->surfeur->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->surfeur->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reservation->status === 'confirmed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                @elseif($reservation->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('coach.reservation.status', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Confirm Reservation">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($reservation->status !== 'cancelled' && $course->date > now())
                                        <form action="{{ route('coach.reservation.status', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Are you sure you want to cancel this reservation?')"
                                                title="Cancel Reservation">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($course->date < now() && $reservation->status === 'confirmed')
                                        <form action="{{ route('coach.reservation.status', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-blue-600 hover:text-blue-900" title="Mark as Completed">
                                                <i class="fa-solid fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-10 bg-white rounded-lg shadow">
            <i class="fa-solid fa-calendar-xmark text-gray-300 text-5xl mb-3"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No reservations found</h3>
            <p class="text-gray-500 mb-6">There are no reservations for this course yet.</p>
        </div>
    @endif
@endsection 
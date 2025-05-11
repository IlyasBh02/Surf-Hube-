@extends('layouts.dashboard')

@section('title', 'All Reservations')
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
    <a href="{{ route('coach.reservations') ?? '#' }}" class="sidebar-link {{ request()->routeIs('coach.reservations') ? 'sidebar-active' : '' }}">
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

@section('page-title', 'Reservations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Reservations</h1>
            <p class="text-gray-600">View and manage all reservations for your courses</p>
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

    <!-- Reservations Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total</h3>
                <p class="text-3xl font-bold text-gray-700">{{ $reservations->total() }}</p>
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

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('coach.reservations') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Reservation Status</label>
                    <select id="status" name="status" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                    <select id="course_id" name="course_id" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Courses</option>
                        @foreach(App\Models\Course::where('coach_id', Auth::id())->orderBy('date', 'desc')->get() as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }} ({{ $course->date->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Surfer</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Name or email" 
                           class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-filter mr-2"></i> Apply Filters
                </button>
                
                @if(request()->has('status') || request()->has('course_id') || request()->has('search'))
                    <a href="{{ route('coach.reservations') }}" class="ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fa-solid fa-xmark mr-2"></i> Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    @if(count($reservations) > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Surfer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('coach.course.reservations', $reservation->course->id) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $reservation->course->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $reservation->course->date->format('d M Y') }}</div>
                                <div class="text-xs">{{ $reservation->course->date->format('H:i') }} ({{ $reservation->course->duration }} min)</div>
                                <div class="text-xs text-gray-400">Reserved: {{ $reservation->created_at->format('d M Y') }}</div>
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
                                @elseif($reservation->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('coach.course.reservations', $reservation->course->id) }}" class="text-blue-600 hover:text-blue-900" title="View Course Reservations">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('coach.reservation.status', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Confirm Reservation">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($reservation->status !== 'cancelled' && $reservation->course->date > now())
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
                                    
                                    @if($reservation->course->date < now() && $reservation->status === 'confirmed')
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
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-10 bg-white rounded-lg shadow">
            <i class="fa-solid fa-calendar-xmark text-gray-300 text-5xl mb-3"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No reservations found</h3>
            <p class="text-gray-500 mb-6">There are no reservations matching your filters.</p>
            
            @if(request()->has('status') || request()->has('course_id') || request()->has('search'))
                <a href="{{ route('coach.reservations') }}" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-filter-circle-xmark mr-2"></i> Clear All Filters
                </a>
            @endif
        </div>
    @endif
@endsection 
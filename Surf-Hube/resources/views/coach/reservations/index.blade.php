@extends('layouts.dashboard')

@section('title', 'Manage Reservations')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('page-title', 'Manage Reservations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Reservations</h1>
            <p class="text-gray-600">Manage surfeur reservations for all your courses</p>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Confirmed Reservations -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fa-solid fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Confirmed Reservations</p>
                    <p class="text-2xl font-bold text-gray-700">{{ isset($reservations) ? $reservations->where('status', 'confirmed')->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- Pending Reservations -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fa-solid fa-clock text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Reservations</p>
                    <p class="text-2xl font-bold text-gray-700">{{ isset($reservations) ? $reservations->where('status', 'pending')->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- Cancelled Reservations -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <i class="fa-solid fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Cancelled Reservations</p>
                    <p class="text-2xl font-bold text-gray-700">{{ isset($reservations) ? $reservations->where('status', 'cancelled')->count() : 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('coach.reservations') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select id="course" name="course_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Courses</option>
                    @foreach(App\Models\Course::where('coach_id', Auth::id())->orderBy('date', 'desc')->get() as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }} - {{ $course->date->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Surfer</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                    placeholder="Name or email" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            
            <div class="flex items-end space-x-2 min-w-[200px]">
                <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
                
                <a href="{{ route('coach.reservations') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fa-solid fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    @if(isset($reservations) && count($reservations) > 0)
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reserved On
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
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->course->title }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->course->duration }} minutes</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->course->date->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->course->date->format('H:i') }}</div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->created_at->format('d M Y, H:i') }}
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
                                    
                                    <a href="{{ route('coach.course.reservations', $reservation->course->id) }}" class="text-blue-600 hover:text-blue-900" title="View All Course Reservations">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg shadow">
            <div class="p-3 mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                <i class="fa-solid fa-calendar-xmark text-blue-600 text-2xl"></i>
            </div>
            <h3 class="mt-5 text-xl font-medium text-gray-900">No reservations found</h3>
            <p class="mt-3 text-sm text-gray-500 max-w-lg mx-auto">
                @if(request()->has('status') || request()->has('course_id') || request()->has('search'))
                    No reservations match your current filters. Try broadening your search criteria.
                @else
                    There are no reservations for your courses yet. Reservations will appear here when surfers book your courses.
                @endif
            </p>
            
            @if(request()->has('status') || request()->has('course_id') || request()->has('search'))
                <div class="mt-6">
                    <a href="{{ route('coach.reservations') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fa-solid fa-filter-circle-xmark mr-2"></i> Clear All Filters
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Course Statistics -->
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Course Statistics</h2>
            <p class="mt-1 text-sm text-gray-500">Overview of all your courses and their booking status</p>
        </div>
        
        @if(isset($courses) && count($courses) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Course
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Capacity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booked/Available
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $course)
                            @php 
                                $stats = $courseStats[$course->id] ?? [
                                    'title' => $course->title,
                                    'date' => $course->date->format('d M Y'),
                                    'available' => $course->available_places,
                                    'booked' => 0,
                                    'remaining' => $course->available_places
                                ];
                                $fillPercentage = $stats['available'] > 0 ? ($stats['booked'] / $stats['available']) * 100 : 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-water"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $stats['title'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $stats['date'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $stats['available'] }} spots</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-900 mr-2">{{ $stats['booked'] }} / {{ $stats['available'] }}</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                            <div class="@if($fillPercentage >= 100) bg-green-600 @elseif($fillPercentage >= 75) bg-yellow-500 @else bg-blue-600 @endif h-2.5 rounded-full" style="width: {{ $fillPercentage }}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs text-gray-500">{{ $stats['remaining'] }} left</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('coach.course.reservations', $course->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fa-solid fa-users"></i> View
                                        </a>
                                        <a href="{{ route('coach.courses.show', $course->id) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fa-solid fa-edit"></i> Manage
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-10 text-center">
                <p class="text-gray-600 mb-6">You don't have any courses yet.</p>
                <a href="{{ route('coach.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-plus mr-2"></i> Create a Course
                </a>
            </div>
        @endif
    </div>
@endsection 
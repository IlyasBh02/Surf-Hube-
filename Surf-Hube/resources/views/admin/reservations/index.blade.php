@extends('layouts.dashboard')

@section('title', 'Reservation Management')
@section('dashboard-type', 'Admin')
@section('dashboard-icon', 'fa-solid fa-user-shield')
@section('user-role', 'Administrator')
@section('user-name', Auth::user()->name ?? 'Admin')
@section('status-message', 'Admin Access')
@section('dashboard-home-link', route('admin.dashboard'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-users sidebar-icon"></i>
        <span>User Management</span>
    </a>
    
    <a href="{{ route('admin.coaches') }}" class="sidebar-link {{ request()->routeIs('admin.coaches*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user-tie sidebar-icon"></i>
        <span>Coach Approvals</span>
        @if($pendingCoachesCount ?? 0 > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $pendingCoachesCount }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.courses') }}" class="sidebar-link {{ request()->routeIs('admin.courses*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>Course Management</span>
    </a>
    
    <a href="{{ route('admin.reservations') }}" class="sidebar-link {{ request()->routeIs('admin.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>Reservations</span>
    </a>
    
    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gear sidebar-icon"></i>
        <span>Settings</span>
    </a>
@endsection

@section('page-title', 'Reservation Management')

@section('content')
    <!-- Stats Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Reservations -->
        <div class="dashboard-stats border-blue-500">
            <div class="text-3xl font-bold text-blue-500 mb-1">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $totalReservations }}</div>
            <div class="text-sm text-gray-500">Total Reservations</div>
            <div class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-calendar-week"></i> All time
            </div>
        </div>
        
        <!-- Confirmed Reservations -->
        <div class="dashboard-stats border-green-500">
            <div class="text-3xl font-bold text-green-500 mb-1">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $confirmedReservations }}</div>
            <div class="text-sm text-gray-500">Confirmed Reservations</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-thumbs-up"></i> {{ number_format(($totalReservations > 0 ? $confirmedReservations / $totalReservations * 100 : 0), 1) }}% of total
            </div>
        </div>
        
        <!-- Cancelled Reservations -->
        <div class="dashboard-stats border-red-500">
            <div class="text-3xl font-bold text-red-500 mb-1">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $cancelledReservations }}</div>
            <div class="text-sm text-gray-500">Cancelled Reservations</div>
            <div class="text-xs text-red-600 mt-2">
                <i class="fa-solid fa-thumbs-down"></i> {{ number_format(($totalReservations > 0 ? $cancelledReservations / $totalReservations * 100 : 0), 1) }}% of total
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="w-full md:w-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search-reservations" name="search" placeholder="Search by surfeur or course..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <select id="time-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Dates</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
                <option value="today">Today</option>
            </select>
            
            <button id="reset-filters" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-filter-circle-xmark mr-1"></i> Reset
            </button>
        </div>
    </div>
    
    <!-- Reservations Table -->
    <div class="content-card mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surfeur</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coach</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="reservations-table-body">
                    @foreach($reservations as $reservation)
                        <tr data-status="{{ $reservation->status }}" data-time="{{ $reservation->course->date->isPast() ? 'past' : ($reservation->course->date->isToday() ? 'today' : 'upcoming') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($reservation->surfeur->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->surfeur->name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->surfeur->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->course->title ?? 'Unknown Course' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($reservation->course->coach)
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($reservation->course->coach->name ?? 'C', 0, 1) }}
                                        </div>
                                        <div class="ml-2 text-sm text-gray-900">
                                            <a href="{{ route('courses.coach', $reservation->course->coach) }}" class="hover:text-blue-600 transition">
                                                {{ $reservation->course->coach->name }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">No coach</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->course->date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $reservation->course->date->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $reservation->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.courses.reservations', $reservation->course_id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('admin.reservations.confirm', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded-md">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($reservation->status !== 'cancelled')
                                        <form action="{{ route('admin.reservations.cancel', $reservation->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md">
                                                <i class="fa-solid fa-ban"></i>
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
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const searchInput = document.getElementById('search-reservations');
        const statusFilter = document.getElementById('status-filter');
        const timeFilter = document.getElementById('time-filter');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const reservationsTable = document.getElementById('reservations-table-body');
        
        function filterReservations() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const timeValue = timeFilter.value.toLowerCase();
            
            const rows = reservationsTable.querySelectorAll('tr');
            
            rows.forEach(row => {
                // Get data from cells
                const surfeurName = row.querySelector('td:nth-child(1) .text-gray-900').textContent.toLowerCase();
                const surfeurEmail = row.querySelector('td:nth-child(1) .text-gray-500').textContent.toLowerCase();
                const courseTitle = row.querySelector('td:nth-child(2) .text-gray-900').textContent.toLowerCase();
                const coachName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                // Get data attributes
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                const rowTime = row.getAttribute('data-time').toLowerCase();
                
                // Check if row matches all filters
                const matchesSearch = surfeurName.includes(searchTerm) || 
                                     surfeurEmail.includes(searchTerm) || 
                                     courseTitle.includes(searchTerm) ||
                                     coachName.includes(searchTerm);
                                     
                const matchesStatus = statusValue === '' || rowStatus === statusValue;
                const matchesTime = timeValue === '' || rowTime === timeValue;
                
                if (matchesSearch && matchesStatus && matchesTime) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Add event listeners
        searchInput.addEventListener('input', filterReservations);
        statusFilter.addEventListener('change', filterReservations);
        timeFilter.addEventListener('change', filterReservations);
        
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            timeFilter.value = '';
            
            const rows = reservationsTable.querySelectorAll('tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        });
    });
</script>
@endsection 
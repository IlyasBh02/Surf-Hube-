@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')
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

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Analytics Dashboard</h1>
            <p class="text-gray-600">View insights and statistics about your courses and reservations</p>
        </div>
        <div>
            <a href="{{ route('coach.reports') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fa-solid fa-file-lines mr-2"></i> View Reports
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-base font-medium text-gray-600">Total Courses</h3>
                <span class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-blue-600"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalCourses }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-base font-medium text-gray-600">Total Reservations</h3>
                <span class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-users text-green-600"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalReservations }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-base font-medium text-gray-600">Est. Earnings</h3>
                <span class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fa-solid fa-dollar-sign text-purple-600"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalEarnings, 2) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-base font-medium text-gray-600 mb-4">Course & Reservation Trends</h3>
            <div class="h-64">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-base font-medium text-gray-600 mb-4">Reservation Status Distribution</h3>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Popular Courses -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-medium text-gray-600">Most Popular Courses</h3>
        </div>
        <div class="px-6 py-4">
            @if(count($popularCourses) > 0)
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
                                    Reservations
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fill Rate
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($popularCourses as $course)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $course->date->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $course->reservations_count }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $fillRate = ($course->reservations_count / $course->available_places) * 100;
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($fillRate, 100) }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ round($fillRate) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('coach.course.reservations', $course->id) }}" class="text-blue-600 hover:text-blue-900">View Reservations</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">No course data available yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Courses',
                        data: @json($courseData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.2
                    },
                    {
                        label: 'Reservations',
                        data: @json($reservationData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Status distribution chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($reservationStatuses);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled', 'Completed'],
                datasets: [{
                    data: [
                        statusData['confirmed'] || 0,
                        statusData['pending'] || 0,
                        statusData['cancelled'] || 0,
                        statusData['completed'] || 0
                    ],
                    backgroundColor: [
                        '#10b981', // green
                        '#f59e0b', // yellow
                        '#ef4444', // red
                        '#3b82f6'  // blue
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
@endpush 
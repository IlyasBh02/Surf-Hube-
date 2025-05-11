@extends('layouts.dashboard')

@section('title', 'Surfeur Dashboard')
@section('dashboard-type', 'surfeur')
@section('user-role', 'surfeur')
@section('user-name', Auth::user()->name)
@section('status-message', 'Surfeur')

@section('sidebar-menu')
    @include('partials.surfeur-sidebar')
@endsection

@section('content')
    <!-- Dashboard Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="mt-1 text-lg text-gray-600">Here's an overview of your surfing journey</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reservations -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <i class="fa-solid fa-calendar-days text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Reservations</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalReservations }}</p>
            </div>
        </div>

        <!-- Confirmed Reservations -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center">
            <div class="rounded-full bg-green-100 p-3 mr-4">
                <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Confirmed Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $confirmedReservations }}</p>
            </div>
        </div>

        <!-- Completed Courses -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center">
            <div class="rounded-full bg-purple-100 p-3 mr-4">
                <i class="fa-solid fa-graduation-cap text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Completed Courses</p>
                <p class="text-2xl font-bold text-gray-900">{{ $completedCourses }}</p>
            </div>
        </div>

        <!-- Surf Level -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex items-center">
            <div class="rounded-full bg-yellow-100 p-3 mr-4">
                <i class="fa-solid fa-chart-line text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Surf Level</p>
                <p class="text-2xl font-bold text-gray-900">
                    @if($completedCourses >= 10)
                        Advanced
                    @elseif($completedCourses >= 5)
                        Intermediate
                    @elseif($completedCourses >= 1)
                        Beginner
                    @else
                        Newcomer
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upcoming Reservations -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Your Upcoming Reservations</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($upcomingReservations as $reservation)
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $reservation->course->title }}</h3>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <i class="fa-solid fa-calendar-day mr-1"></i>
                                        <span>{{ $reservation->course->date->format('F j, Y') }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <i class="fa-solid fa-clock mr-1"></i>
                                        <span>{{ $reservation->course->date->format('g:i A') }} ({{ $reservation->course->duration }} min)</span>
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <i class="fa-solid fa-user-tie mr-1"></i>
                                        <span>Coach: 
                                            <a href="{{ route('courses.coach', $reservation->course->coach) }}" class="hover:text-blue-600 transition">
                                                {{ $reservation->course->coach->name }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 flex justify-end">
                                <a href="{{ route('surfer.reservations.show', $reservation) }}" class="text-sm text-blue-600 hover:text-blue-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <i class="fa-solid fa-calendar-xmark text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">You don't have any upcoming reservations</p>
                            <a href="{{ route('courses.browse') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                Browse Courses
                            </a>
                        </div>
                    @endforelse
                </div>
                @if(count($upcomingReservations) > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <a href="{{ route('surfer.reservations') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            View all reservations <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recommended Courses -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Recommended Courses</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recommendedCourses as $course)
                        <div class="px-6 py-4">
                            <h3 class="font-medium text-gray-900">{{ $course->title }}</h3>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <i class="fa-solid fa-calendar-day mr-1"></i>
                                <span>{{ $course->date->format('F j, Y') }}</span>
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <i class="fa-solid fa-user-tie mr-1"></i>
                                <span>Coach: 
                                    <a href="{{ route('courses.coach', $course->coach) }}" class="hover:text-blue-600 transition">
                                        {{ $course->coach->name }}
                                    </a>
                                </span>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('courses.show', $course) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center">
                            <p class="text-gray-600">No courses available right now</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('courses.browse') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Browse all courses <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection 
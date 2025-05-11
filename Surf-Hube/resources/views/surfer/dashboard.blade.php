@extends('layouts.dashboard')

@section('title', 'Surfer Dashboard')
@section('dashboard-type', 'Surfer')
@section('dashboard-icon', 'fa-solid fa-water')
@section('user-role', 'Surfeur')
@section('user-name', Auth::user()->name ?? 'Surfer')
@section('status-message', 'Member since ' . (Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'May 2023'))
@section('dashboard-home-link', route('surfer.dashboard'))

@section('sidebar-menu')
    <a href="{{ route('surfer.dashboard') }}" class="sidebar-link {{ request()->routeIs('surfer.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('courses.browse') ?? '#' }}" class="sidebar-link {{ request()->routeIs('courses.browse') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>Browse Courses</span>
    </a>
    <a href="{{ route('surfer.reservations') ?? '#' }}" class="sidebar-link {{ request()->routeIs('surfer.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>My Reservations</span>
        @if($upcomingReservationsCount ?? 0 > 0)
            <span class="ml-auto bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $upcomingReservationsCount ?? 0 }}</span>
        @endif
    </a>
    <a href="{{ route('surfer.profile') ?? '#' }}" class="sidebar-link {{ request()->routeIs('surfer.profile*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user sidebar-icon"></i>
        <span>My Profile</span>
    </a>
@endsection

@section('page-title', 'Surfer Dashboard')

@section('content')
    <!-- Stats Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Upcoming Reservations -->
        <div class="dashboard-stats border-blue-500">
            <div class="text-3xl font-bold text-blue-500 mb-1">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $upcomingReservations ?? 2 }}</div>
            <div class="text-sm text-gray-500">Upcoming Reservations</div>
            <div class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-calendar"></i> Next on {{ $nextReservationDate ?? 'June 15, 2023' }}
            </div>
        </div>
        
        <!-- Completed Courses -->
        <div class="dashboard-stats border-green-500">
            <div class="text-3xl font-bold text-green-500 mb-1">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $completedCourses ?? 5 }}</div>
            <div class="text-sm text-gray-500">Completed Courses</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-trophy"></i> {{ $skillLevel ?? 'Intermediate' }} level
            </div>
        </div>
        
        <!-- Top Coaches -->
        <div class="dashboard-stats border-purple-500">
            <div class="text-3xl font-bold text-purple-500 mb-1">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $favoriteCoaches ?? 3 }}</div>
            <div class="text-sm text-gray-500">Favorite Coaches</div>
            <div class="text-xs text-purple-600 mt-2">
                <i class="fa-solid fa-star"></i> From {{ $totalCoachesTried ?? 4 }} coaches tried
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Reservations -->
        <div class="content-card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">My Upcoming Courses</h2>
                <a href="{{ route('surfer.reservations') ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-800">View All Reservations</a>
            </div>
            
            @if(!empty($upcomingReservationsList ?? []))
                <div class="space-y-4">
                    @foreach($upcomingReservationsList ?? [] as $reservation)
                        <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                                        <i class="fa-solid fa-water"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ $reservation['course_title'] ?? 'Beginner Surfing Course' }}</h3>
                                        <p class="text-xs text-gray-500">with {{ $reservation['coach_name'] ?? 'Coach Alex' }}</p>
                                        <div class="mt-1 flex items-center">
                                            <i class="fa-solid fa-calendar-day text-gray-400 mr-1 text-xs"></i>
                                            <span class="text-xs text-gray-500">{{ $reservation['date'] ?? 'June 15, 2023' }}</span>
                                            <i class="fa-solid fa-clock text-gray-400 ml-3 mr-1 text-xs"></i>
                                            <span class="text-xs text-gray-500">{{ $reservation['time'] ?? '09:00 AM - 11:00 AM' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('surfer.reservations.show', $reservation['id'] ?? 1) ?? '#' }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fa-solid fa-eye mr-1"></i> Details
                                    </a>
                                    <form action="{{ route('reservations.cancel', $reservation['id'] ?? 1) ?? '#' }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fa-solid fa-times mr-1"></i> Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Sample data if no upcoming reservations -->
                <div class="space-y-4">
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Beginner Surfing Course</h3>
                                    <p class="text-xs text-gray-500">with Coach Alex</p>
                                    <div class="mt-1 flex items-center">
                                        <i class="fa-solid fa-calendar-day text-gray-400 mr-1 text-xs"></i>
                                        <span class="text-xs text-gray-500">June 15, 2023</span>
                                        <i class="fa-solid fa-clock text-gray-400 ml-3 mr-1 text-xs"></i>
                                        <span class="text-xs text-gray-500">09:00 AM - 11:00 AM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fa-solid fa-eye mr-1"></i> Details
                                </a>
                                <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fa-solid fa-times mr-1"></i> Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-4">
                                    <i class="fa-solid fa-water"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Intermediate Wave Riding</h3>
                                    <p class="text-xs text-gray-500">with Coach Maria</p>
                                    <div class="mt-1 flex items-center">
                                        <i class="fa-solid fa-calendar-day text-gray-400 mr-1 text-xs"></i>
                                        <span class="text-xs text-gray-500">June 22, 2023</span>
                                        <i class="fa-solid fa-clock text-gray-400 ml-3 mr-1 text-xs"></i>
                                        <span class="text-xs text-gray-500">10:00 AM - 12:30 PM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fa-solid fa-eye mr-1"></i> Details
                                </a>
                                <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fa-solid fa-times mr-1"></i> Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(empty($upcomingReservationsList ?? []) && empty($upcomingReservations))
                <div class="bg-gray-50 p-6 text-center rounded-lg">
                    <div class="text-4xl text-gray-300 mb-3">
                        <i class="fa-solid fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No upcoming reservations</h3>
                    <p class="text-gray-600 mb-4">You haven't booked any surfing courses yet.</p>
                    <a href="{{ route('courses.browse') ?? '#' }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fa-solid fa-search mr-2"></i> Browse Available Courses
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Recommended Courses -->
        <div class="content-card">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recommended For You</h2>
            
            <div class="space-y-4">
                @foreach($recommendedCourses ?? [] as $course)
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full flex-shrink-0 bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-water"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $course['title'] ?? 'Advanced Technique Course' }}</h3>
                                <p class="text-xs text-gray-500">by {{ $course['coach'] ?? 'Coach David' }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $course['level'] === 'Beginner' ? 'bg-green-100 text-green-800' : ($course['level'] === 'Intermediate' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $course['level'] ?? 'Advanced' }}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        <i class="fa-solid fa-calendar-day mr-1"></i> {{ $course['date'] ?? 'June 25, 2023' }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('courses.show', $course['id'] ?? 1) ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-800">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Sample data if no recommended courses -->
                @if(empty($recommendedCourses ?? []))
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full flex-shrink-0 bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                                <i class="fa-solid fa-water"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Advanced Wave Techniques</h3>
                                <p class="text-xs text-gray-500">by Coach David</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 text-purple-800">
                                        Advanced
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        <i class="fa-solid fa-calendar-day mr-1"></i> June 25, 2023
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full flex-shrink-0 bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-water"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Intermediate Surf Safety</h3>
                                <p class="text-xs text-gray-500">by Coach Maria</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                        Intermediate
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        <i class="fa-solid fa-calendar-day mr-1"></i> June 28, 2023
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full flex-shrink-0 bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                <i class="fa-solid fa-water"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Wave Reading 101</h3>
                                <p class="text-xs text-gray-500">by Coach Alex</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-800">
                                        Beginner
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        <i class="fa-solid fa-calendar-day mr-1"></i> July 2, 2023
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('courses.browse') ?? '#' }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fa-solid fa-search mr-2"></i> Browse All Courses
                </a>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.dashboard')

@section('title', 'Coach Dashboard')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('page-title', 'Coach Dashboard')

@section('content')
    @if(!Auth::user()->coach_approved ?? false)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Your coach account is pending approval. Once approved, you will be able to create surf courses.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Active Courses -->
        <div class="dashboard-stats border-blue-500">
            <div class="text-3xl font-bold text-blue-500 mb-1">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $activeCourses ?? 0 }}</div>
            <div class="text-sm text-gray-500">Active Courses</div>
            <div class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-calendar"></i> {{ $upcomingCourses ?? 0 }} upcoming this week
            </div>
        </div>
        
        <!-- Total Students -->
        <div class="dashboard-stats border-green-500">
            <div class="text-3xl font-bold text-green-500 mb-1">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $totalStudents ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total Students</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-arrow-up"></i> {{ $newStudentsThisMonth ?? 0 }} new this month
            </div>
        </div>
        
        <!-- Total Reservations -->
        <div class="dashboard-stats border-purple-500">
            <div class="text-3xl font-bold text-purple-500 mb-1">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $totalReservations ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total Reservations</div>
            <div class="text-xs text-purple-600 mt-2">
                <i class="fa-solid fa-calendar-plus"></i> {{ $newReservationsThisWeek ?? 0 }} new this week
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Upcoming Courses -->
        <div class="content-card lg:col-span-3">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Upcoming Courses</h2>
                <a href="{{ route('coach.courses.index') ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-800">View All Courses</a>
            </div>
            
            @if(!empty($upcomingCoursesList ?? []))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($upcomingCoursesList ?? [] as $course)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                <i class="fa-solid fa-water"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $course['title'] ?? 'Beginner Surfing Course' }}</div>
                                                <div class="text-sm text-gray-500">{{ $course['level'] ?? 'Beginner' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $course['date'] ?? '2023-06-10' }}</div>
                                        <div class="text-sm text-gray-500">{{ $course['time'] ?? '09:00 AM - 11:00 AM' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-900">{{ $course['booked'] ?? 4 }} / {{ $course['capacity'] ?? 6 }}</span>
                                            <div class="ml-2 w-20 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($course['booked'] ?? 4) / ($course['capacity'] ?? 6) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('coach.courses.show', $course['id'] ?? 1) ?? '#' }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fa-solid fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('coach.courses.edit', $course['id'] ?? 1) ?? '#' }}" class="text-green-600 hover:text-green-900 ml-3">
                                                <i class="fa-solid fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Sample data if no data provided -->
                            @if(empty($upcomingCoursesList ?? []))
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                <i class="fa-solid fa-water"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Beginner Surfing Course</div>
                                                <div class="text-sm text-gray-500">Beginner</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">2023-06-10</div>
                                        <div class="text-sm text-gray-500">09:00 AM - 11:00 AM</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-900">4 / 6</span>
                                            <div class="ml-2 w-20 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 66%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                <i class="fa-solid fa-eye"></i> View
                                            </a>
                                            <a href="#" class="text-green-600 hover:text-green-900 ml-3">
                                                <i class="fa-solid fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                                <i class="fa-solid fa-water"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Intermediate Wave Riding</div>
                                                <div class="text-sm text-gray-500">Intermediate</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">2023-06-12</div>
                                        <div class="text-sm text-gray-500">10:00 AM - 12:30 PM</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-900">5 / 5</span>
                                            <div class="ml-2 w-20 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                <i class="fa-solid fa-eye"></i> View
                                            </a>
                                            <a href="#" class="text-green-600 hover:text-green-900 ml-3">
                                                <i class="fa-solid fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 p-4 text-center rounded-lg">
                    <p class="text-gray-600">You have no upcoming courses.</p>
                    <a href="{{ route('coach.courses.create') ?? '#' }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Create a Course</a>
                </div>
            @endif
        </div>
        
        <!-- Recent Reservations -->
        <div class="content-card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Recent Reservations</h2>
                <a href="{{ route('coach.reservations') ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentReservations ?? [] as $reservation)
                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $reservation['user_name'] ?? 'Student Name' }}</p>
                                <p class="text-xs text-gray-500">{{ $reservation['course_title'] ?? 'Course Title' }} - {{ $reservation['date'] ?? '2023-06-10' }}</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reservation['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($reservation['status'] ?? 'confirmed') }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $reservation['created_at'] ?? 'Just now' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Sample data if no data provided -->
                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Emily Johnson</p>
                                <p class="text-xs text-gray-500">Beginner Surfing Course - 2023-06-10</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                2 hours ago
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Thomas Wilson</p>
                                <p class="text-xs text-gray-500">Intermediate Wave Riding - 2023-06-12</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                5 hours ago
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Jessica Martinez</p>
                                <p class="text-xs text-gray-500">Beginner Surfing Course - 2023-06-10</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Yesterday
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="content-card mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('coach.courses.create') ?? '#' }}" class="flex flex-col items-center p-5 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-3">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">Create New Course</span>
            </a>
            
            <a href="{{ route('coach.reservations') ?? '#' }}" class="flex flex-col items-center p-5 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-colors">
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mb-3">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">Manage Reservations</span>
            </a>
            
            <a href="{{ route('coach.profile') ?? '#' }}" class="flex flex-col items-center p-5 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition-colors">
                <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 mb-3">
                    <i class="fa-solid fa-user-edit"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">Update Profile</span>
            </a>
            
            <a href="#" class="flex flex-col items-center p-5 border border-gray-200 rounded-lg hover:bg-amber-50 hover:border-amber-200 transition-colors">
                <div class="h-12 w-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-3">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">View Analytics</span>
            </a>
        </div>
    </div>
@endsection 
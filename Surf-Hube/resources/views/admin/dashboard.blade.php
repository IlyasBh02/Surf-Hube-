@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('dashboard-type', 'Admin')
@section('dashboard-icon', 'fa-solid fa-user-shield')
@section('user-role', 'Administrator')
@section('user-name', Auth::user()->name ?? 'Admin')
@section('status-message', 'Admin Access')
@section('dashboard-home-link', route('admin.dashboard'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link space-x-2 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.users') ?? '#' }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-users sidebar-icon"></i>
        <span>User Management</span>
    </a>
    
    <a href="{{ route('admin.coaches') ?? '#' }}" class="sidebar-link {{ request()->routeIs('admin.coaches*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user-tie sidebar-icon"></i>
        <span>Coach Approvals</span>
        @if($pendingCoachesCount ?? 0 > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $pendingCoachesCount ?? 0 }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.courses') ?? '#' }}" class="sidebar-link {{ request()->routeIs('admin.courses*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>Course Management</span>
    </a>
    
    <a href="{{ route('admin.reservations') ?? '#' }}" class="sidebar-link {{ request()->routeIs('admin.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>Reservations</span>
    </a>
    
    <a href="{{ route('admin.settings') ?? '#' }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gear sidebar-icon"></i>
        <span>Settings</span>
    </a>
@endsection

@section('page-title', 'Dashboard Overview')

@section('content')
    <!-- Stats Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="dashboard-stats border-blue-500">
            <div class="text-3xl font-bold text-blue-500 mb-1">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $totalUsers ?? 152 }}</div>
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-arrow-up"></i> {{ $newUsersThisMonth ?? 12 }} new this month
            </div>
        </div>
        
        <!-- Active Coaches -->
        <div class="dashboard-stats border-green-500">
            <div class="text-3xl font-bold text-green-500 mb-1">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $activeCoaches ?? 24 }}</div>
            <div class="text-sm text-gray-500">Active Coaches</div>
            <div class="text-xs text-yellow-600 mt-2">
                <i class="fa-solid fa-clock"></i> {{ $pendingCoachesCount ?? 3 }} pending approval
            </div>
        </div>
        
        <!-- Active Courses -->
        <div class="dashboard-stats border-purple-500">
            <div class="text-3xl font-bold text-purple-500 mb-1">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $activeCourses }}</div>
            <div class="text-sm text-gray-500">Active Courses</div>
            <div class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-calendar"></i> {{ $upcomingCourses }} upcoming this week
            </div>
        </div>
        
        <!-- Total Reservations -->
        <div class="dashboard-stats border-amber-500">
            <div class="text-3xl font-bold text-amber-500 mb-1">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $totalReservations }}</div>
            <div class="text-sm text-gray-500">Total Reservations</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-arrow-up"></i> {{ $newReservationsThisWeek }} new this week
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Coach Approvals -->
        <div class="content-card lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pending Coach Approvals</h2>
                <a href="{{ route('admin.coaches') ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            
            @if(($pendingCoaches ?? []) && count($pendingCoaches ?? []) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Loop through pending coaches -->
                            @foreach($pendingCoaches ?? [] as $coach)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ substr($coach['name'] ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $coach['name'] ?? 'Coach Name' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $coach['email'] ?? 'coach@example.com' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $coach['requested_at'] ?? '2023-05-20' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.approve-coach', $coach['id'] ?? 1) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reject-coach', $coach['id'] ?? 1) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-times mr-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Sample data if no data provided -->
                            @if(empty($pendingCoaches ?? []))
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                J
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">John Smith</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">john.smith@example.com</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">2023-05-20</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-times mr-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                M
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Maria Garcia</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">maria.garcia@example.com</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">2023-05-22</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                                    <i class="fa-solid fa-times mr-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 p-4 text-center rounded-lg">
                    <p class="text-gray-600">No pending coach approvals at this time.</p>
                </div>
            @endif
        </div>
        
        <!-- System Overview -->
        <div class="content-card">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">System Overview</h2>
            <ul class="space-y-3">
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">System Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fa-solid fa-circle text-green-400 mr-1 text-xs"></i> Operational
                    </span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Total Admins</span>
                    <span class="text-gray-900 font-medium">{{ $totalAdmins }}</span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Total Coaches</span>
                    <span class="text-gray-900 font-medium">{{ $totalCoaches }}</span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Total Surfeurs</span>
                    <span class="text-gray-900 font-medium">{{ $totalSurfeurs }}</span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Active Courses</span>
                    <span class="text-gray-900 font-medium">{{ $activeCourses }}</span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Completed Courses</span>
                    <span class="text-gray-900 font-medium">{{ $completedCourses }}</span>
                </li>
                <li class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-600">Last Backup</span>
                    <span class="text-gray-900 font-medium">{{ $lastBackup }}</span>
                </li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('admin.settings') ?? '#' }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-gears mr-2"></i> System Settings
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="content-card mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
            <!-- No "View All" link since we don't have a separate page for activities yet -->
        </div>
        
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="h-10 w-10 rounded-full 
                    @if($activity['type'] == 'user_registered')
                        bg-green-100 text-green-600
                    @elseif($activity['type'] == 'course_created')
                        bg-blue-100 text-blue-600
                    @elseif($activity['type'] == 'reservation_made')
                        bg-purple-100 text-purple-600
                    @else
                        bg-blue-100 text-blue-600
                    @endif
                    flex items-center justify-center mr-3">
                        <i class="{{ $activity['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">{!! $activity['message'] !!}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 p-4 text-center rounded-lg">
                    <p class="text-gray-600">No recent activity to display.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection 
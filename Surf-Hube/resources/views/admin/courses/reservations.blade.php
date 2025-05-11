@extends('layouts.dashboard')

@section('title', 'Course Reservations')
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

@section('page-title', 'Course Reservations')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li class="flex items-center space-x-2">
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <a href="{{ route('admin.courses') }}" class="hover:text-blue-600">Courses</a>
            </li>
            <li class="flex items-center space-x-2">
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-700 font-medium">{{ $course->title }}</span>
            </li>
        </ol>
    </nav>

    <!-- Course Details Card -->
    <div class="content-card mb-6">
        <div class="flex flex-col md:flex-row justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                    <i class="fa-solid fa-water text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-500">Coach: {{ $course->coach->name ?? 'Unknown' }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Date</div>
                    <div class="font-medium">{{ $course->date->format('M d, Y') }}</div>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Time</div>
                    <div class="font-medium">{{ $course->date->format('g:i A') }}</div>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Duration</div>
                    <div class="font-medium">{{ $course->duration }} min</div>
                </div>
                
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Reservations</div>
                    <div class="font-medium">{{ $course->reservations->count() }} / {{ $course->available_places }}</div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 border-t pt-4">
            <h3 class="text-md font-medium text-gray-700 mb-2">Description</h3>
            <p class="text-gray-600">{{ $course->description }}</p>
        </div>
    </div>
    
    <!-- Reservations List -->
    <div class="content-card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Reservations</h2>
            <span class="px-3 py-1 text-sm rounded-full {{ $course->reservations->count() == 0 ? 'bg-gray-100 text-gray-800' : ($course->reservations->count() >= $course->available_places ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">
                {{ $course->reservations->count() == 0 ? 'No Reservations' : ($course->reservations->count() >= $course->available_places ? 'Fully Booked' : 'Available: ' . ($course->available_places - $course->reservations->count())) }}
            </span>
        </div>
        
        @if($course->reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surfeur</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved On</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($course->reservations as $reservation)
                            <tr>
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $reservation->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $reservation->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
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
        @else
            <div class="bg-gray-50 p-4 text-center rounded-lg">
                <p class="text-gray-600">No reservations have been made for this course yet.</p>
            </div>
        @endif
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-6 flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
        <a href="{{ route('admin.courses') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Courses
        </a>
        
        @if($course->date->isFuture())
            <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" class="sm:inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this course? All reservations will also be deleted.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                    <i class="fa-solid fa-trash mr-2"></i> Delete Course
                </button>
            </form>
        @endif
    </div>
@endsection 
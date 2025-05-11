@extends('layouts.dashboard')

@section('title', 'Course Details')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('page-title', 'Course Details')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $course->title }}</h1>
            <p class="text-gray-600">Course Details</p>
        </div>
        <div class="flex space-x-2">
            @if($course->date->isFuture())
                <a href="{{ route('coach.courses.edit', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-edit mr-2"></i> Edit Course
                </a>
            @endif
            <a href="{{ route('coach.course.reservations', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                <i class="fa-solid fa-users mr-2"></i> View Reservations
            </a>
            <a href="{{ route('coach.courses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Courses
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Course Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="content-card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fa-solid fa-info-circle text-blue-500 mr-2"></i>
                    Course Information
                </h2>
                
                @if($course->thumbnail)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="rounded-lg w-full h-auto object-cover max-h-64">
                </div>
                @endif
                
                <div class="prose max-w-none">
                    <p>{{ $course->description }}</p>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created On</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="content-card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fa-solid fa-users text-purple-500 mr-2"></i>
                    Reservations Summary
                </h2>
                
                @php
                    $confirmedCount = $course->reservations->where('status', 'confirmed')->count();
                    $pendingCount = $course->reservations->where('status', 'pending')->count();
                    $cancelledCount = $course->reservations->where('status', 'cancelled')->count();
                    $totalReservations = $confirmedCount + $pendingCount + $cancelledCount;
                    $remainingPlaces = $course->available_places - $confirmedCount;
                    
                    if ($course->available_places > 0) {
                        $fillPercentage = ($confirmedCount / $course->available_places) * 100;
                    } else {
                        $fillPercentage = 0;
                    }
                @endphp
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $confirmedCount }} / {{ $course->available_places }} spots filled
                            </span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ number_format($fillPercentage, 0) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="@if($fillPercentage >= 100) bg-green-600 @elseif($fillPercentage >= 75) bg-yellow-400 @else bg-blue-600 @endif h-2.5 rounded-full" style="width: {{ $fillPercentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $remainingPlaces }} spots remaining</p>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 pt-4">
                        <div class="bg-green-50 p-3 rounded-lg text-center">
                            <div class="text-xl font-bold text-green-600">{{ $confirmedCount }}</div>
                            <div class="text-xs text-gray-500">Confirmed</div>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg text-center">
                            <div class="text-xl font-bold text-yellow-600">{{ $pendingCount }}</div>
                            <div class="text-xs text-gray-500">Pending</div>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg text-center">
                            <div class="text-xl font-bold text-red-600">{{ $cancelledCount }}</div>
                            <div class="text-xs text-gray-500">Cancelled</div>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <a href="{{ route('coach.course.reservations', $course->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-arrow-right mr-1"></i> Manage Reservations
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="space-y-6">
            <div class="content-card">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fa-solid fa-calendar-day text-green-500 mr-2"></i>
                    Course Details
                </h2>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
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
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fa-solid fa-calendar-day text-gray-400 mr-2"></i>
                                {{ $course->date->format('l, d F Y') }}
                            </div>
                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-clock text-gray-400 mr-2"></i>
                                {{ $course->date->format('H:i') }} - {{ $course->date->addMinutes($course->duration)->format('H:i') }}
                            </div>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duration</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fa-solid fa-hourglass-half text-gray-400 mr-2"></i>
                                {{ $course->duration }} minutes
                                @if($course->duration >= 60)
                                    ({{ floor($course->duration / 60) }} hour{{ floor($course->duration / 60) > 1 ? 's' : '' }}
                                    @if($course->duration % 60 > 0)
                                        and {{ $course->duration % 60 }} minutes
                                    @endif
                                    )
                                @endif
                            </div>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Available Places</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fa-solid fa-users text-gray-400 mr-2"></i>
                                {{ $course->available_places }} max attendees
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
            
            @if($course->date->isFuture())
                <div class="content-card">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fa-solid fa-tools text-gray-500 mr-2"></i>
                        Actions
                    </h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('coach.courses.edit', $course->id) }}" class="flex items-center p-2 rounded-md hover:bg-blue-50 text-blue-600">
                            <i class="fa-solid fa-edit w-5 h-5 mr-2"></i>
                            <span>Edit Course</span>
                        </a>
                        
                        <a href="{{ route('coach.course.reservations', $course->id) }}" class="flex items-center p-2 rounded-md hover:bg-purple-50 text-purple-600">
                            <i class="fa-solid fa-users w-5 h-5 mr-2"></i>
                            <span>Manage Reservations</span>
                        </a>
                        
                        <form action="{{ route('coach.courses.destroy', $course->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="flex items-center w-full p-2 rounded-md hover:bg-red-50 text-red-600" 
                                onclick="return confirm('Are you sure you want to delete this course? This will cancel all reservations.')">
                                <i class="fa-solid fa-trash w-5 h-5 mr-2"></i>
                                <span>Delete Course</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection 
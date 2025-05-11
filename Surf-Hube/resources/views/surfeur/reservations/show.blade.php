@extends('layouts.dashboard')

@section('title', 'Reservation Details')
@section('dashboard-type', 'surfeur')
@section('user-role', 'surfeur')
@section('user-name', Auth::user()->name)
@section('status-message', 'Surfeur')

@section('sidebar-menu')
    @include('partials.surfeur-sidebar')
@endsection

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('surfer.reservations') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Reservations
        </a>
    </div>

    <!-- Reservation Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reservation Details</h1>
            <p class="mt-1 text-gray-600">Booking for {{ $reservation->course->title }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($reservation->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Course and Reservation Info -->
        <div class="md:col-span-2 space-y-6">
            <!-- Course Details Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Course Details</h2>
                </div>
                <div class="p-6">
                    <!-- Course Image -->
                    <div class="mb-6">
                        @if($reservation->course->thumbnail)
                            <img src="{{ asset('storage/' . $reservation->course->thumbnail) }}" alt="{{ $reservation->course->title }}" class="w-full h-48 object-cover rounded-lg">
                        @else
                            <div class="w-full h-48 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-water text-blue-500 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Course Info -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-medium text-gray-900">{{ $reservation->course->title }}</h3>
                            <p class="mt-1 text-gray-600">{{ $reservation->course->description }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Date & Time</h4>
                                <p class="mt-1 text-gray-900">
                                    {{ $reservation->course->date->format('l, F j, Y') }}<br>
                                    {{ $reservation->course->date->format('g:i A') }} - 
                                    {{ $reservation->course->date->addMinutes($reservation->course->duration)->format('g:i A') }}
                                </p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Duration</h4>
                                <p class="mt-1 text-gray-900">{{ $reservation->course->duration }} minutes</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Location</h4>
                                <p class="mt-1 text-gray-900">{{ $reservation->course->location ?? 'Beach Location' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Skill Level</h4>
                                <p class="mt-1 text-gray-900">{{ ucfirst($reservation->course->level) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Status Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Reservation Status</h2>
                </div>
                <div class="p-6">
                    <ol class="relative border-l border-gray-200 ml-3">
                        <!-- Booking Created -->
                        <li class="mb-6 ml-6">
                            <span class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full -left-4 ring-4 ring-white">
                                <i class="fa-solid fa-calendar-plus text-green-500"></i>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Booking Created</h3>
                            <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                {{ $reservation->created_at->format('F j, Y \a\t g:i A') }}
                            </time>
                            <p class="mb-4 text-base font-normal text-gray-500">
                                Your booking was successfully created and is awaiting confirmation.
                            </p>
                        </li>
                        
                        <!-- Booking Confirmed / Cancelled -->
                        @if($reservation->status !== 'pending')
                            <li class="mb-6 ml-6">
                                <span class="absolute flex items-center justify-center w-8 h-8 
                                    @if($reservation->status === 'confirmed') bg-blue-100 @else bg-red-100 @endif 
                                    rounded-full -left-4 ring-4 ring-white">
                                    @if($reservation->status === 'confirmed')
                                        <i class="fa-solid fa-check text-blue-500"></i>
                                    @else
                                        <i class="fa-solid fa-ban text-red-500"></i>
                                    @endif
                                </span>
                                <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                                    Booking {{ ucfirst($reservation->status) }}
                                </h3>
                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                    {{ $reservation->updated_at->format('F j, Y \a\t g:i A') }}
                                </time>
                                <p class="mb-4 text-base font-normal text-gray-500">
                                    @if($reservation->status === 'confirmed')
                                        Your booking has been confirmed. We're looking forward to seeing you at the surf course!
                                    @else
                                        This booking has been cancelled and will not be held.
                                    @endif
                                </p>
                            </li>
                        @endif
                        
                        <!-- Course Date (Future) -->
                        @if($reservation->status === 'confirmed' && $reservation->course->date > now())
                            <li class="ml-6">
                                <span class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white">
                                    <i class="fa-solid fa-surfboard text-gray-500"></i>
                                </span>
                                <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Course Date</h3>
                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                    {{ $reservation->course->date->format('F j, Y \a\t g:i A') }}
                                </time>
                                <p class="text-base font-normal text-gray-500">
                                    Your surf course is scheduled for this date. Please arrive 15 minutes early.
                                </p>
                            </li>
                        @endif
                        
                        <!-- Course Completed (Past) -->
                        @if($reservation->status === 'confirmed' && $reservation->course->date < now())
                            <li class="ml-6">
                                <span class="absolute flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full -left-4 ring-4 ring-white">
                                    <i class="fa-solid fa-flag-checkered text-blue-500"></i>
                                </span>
                                <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Course Completed</h3>
                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                    {{ $reservation->course->date->format('F j, Y \a\t g:i A') }}
                                </time>
                                <p class="text-base font-normal text-gray-500">
                                    This course has been completed. Thank you for surfing with us!
                                </p>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Coach Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Coach Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                            {{ substr($reservation->course->coach->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                @if(isset($reservation->course->coach))
                                    <a href="{{ route('courses.coach', $reservation->course->coach) }}" class="hover:text-blue-600 transition">
                                        {{ $reservation->course->coach->name }}
                                    </a>
                                @else
                                    Unknown Coach
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500">Professional Surf Instructor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">
                        {{ $reservation->course->coach->bio ?? 'An experienced surf instructor ready to help you catch the perfect wave!' }}
                    </p>
                </div>
            </div>
            
            <!-- Reservation Actions Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Reservation Actions</h2>
                </div>
                <div class="p-6">
                    <!-- Upcoming Course Actions -->
                    @if($reservation->status === 'confirmed' && $reservation->course->date > now())
                        <div class="mb-4">
                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" onsubmit="return confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <i class="fa-solid fa-ban mr-2"></i> Cancel Reservation
                                </button>
                            </form>
                        </div>
                        <div class="mb-4">
                            <a href="#" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-block text-center">
                                <i class="fa-solid fa-calendar-alt mr-2"></i> Add to Calendar
                            </a>
                        </div>
                    @endif
                    
                    <!-- Contact Support -->
                    <div>
                        <a href="mailto:support@surfhub.com" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-block text-center">
                            <i class="fa-solid fa-headset mr-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Details Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Booking Details</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Booking ID</h4>
                            <p class="mt-1 text-gray-900">{{ $reservation->id }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Booking Date</h4>
                            <p class="mt-1 text-gray-900">{{ $reservation->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Price</h4>
                            <p class="mt-1 text-gray-900">${{ number_format($reservation->course->price, 2) }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Payment Status</h4>
                            <p class="mt-1 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Paid
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.app')

@section('title', 'Browse Courses')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Browse Surf Courses</h1>
            <p class="mt-2 text-lg text-gray-600">Find and book the perfect surf course for your level</p>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('courses.browse') }}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900">Filter Courses</h3>
                    <div class="flex flex-wrap gap-4">
                        <!-- Date Filter -->
                        <div class="w-full md:w-auto">
                            <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <select id="dateFilter" name="date"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('date') == 'all' ? 'selected' : '' }}>All Dates</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="tomorrow" {{ request('date') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>

                        <!-- Coach Filter -->
                        <div class="w-full md:w-auto">
                            <label for="coachFilter" class="block text-sm font-medium text-gray-700 mb-1">Coach</label>
                            <select id="coachFilter" name="coach_id"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="" {{ !request('coach_id') ? 'selected' : '' }}>All Coaches</option>
                                @foreach($coaches ?? [] as $coach)
                                    <option value="{{ $coach->id }}" {{ request('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Available Places Filter -->
                        <div class="w-full md:w-auto">
                            <label for="placesFilter" class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                            <select id="placesFilter" name="availability"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('availability') == 'all' ? 'selected' : '' }}>Any Availability</option>
                                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Places</option>
                                <option value="limited" {{ request('availability') == 'limited' ? 'selected' : '' }}>Limited Places (< 5)</option>
                                <option value="full" {{ request('availability') == 'full' ? 'selected' : '' }}>Full (Waiting List)</option>
                            </select>
                        </div>

                        <!-- Reset & Apply Buttons -->
                        <div class="w-full md:w-auto flex items-end space-x-2">
                            <a href="{{ route('courses.browse') }}"
                                class="w-full md:w-auto bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                            </a>
                            <button type="submit"
                                class="w-full md:w-auto bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fa-solid fa-filter mr-1"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
                <!-- Course Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        @if($course->thumbnail)
                            <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                        @else
                            <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="{{ $course->title }}">
                        @endif
                        <div
                            class="absolute top-0 right-0 bg-blue-500 text-white px-3 py-1 m-2 rounded-full text-sm font-semibold">
                            @if($course->date->isPast())
                                Completed
                            @elseif($course->date->isToday())
                                Today
                            @elseif($course->date->diffInDays(now()) <= 7)
                                This Week
                            @else
                                Upcoming
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $course->title }}</h3>
                            @php
                                $bookedCount = $course->reservations()->where('status', 'confirmed')->count();
                                $remainingPlaces = $course->available_places - $bookedCount;
                            @endphp
                            <span class="bg-{{ $remainingPlaces > 5 ? 'green' : ($remainingPlaces > 0 ? 'yellow' : 'red') }}-100 text-{{ $remainingPlaces > 5 ? 'green' : ($remainingPlaces > 0 ? 'yellow' : 'red') }}-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                {{ $remainingPlaces > 5 ? 'Available' : ($remainingPlaces > 0 ? 'Limited' : 'Full') }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                        <div class="flex items-center text-gray-500 text-sm mb-3">
                            <i class="fa-solid fa-calendar-days mr-1"></i>
                            <span>{{ $course->date->format('M d, Y - g:i A') }}</span>
                        </div>
                        <div class="flex items-center text-gray-500 text-sm mb-3">
                            <i class="fa-solid fa-clock mr-1"></i>
                            <span>Duration: {{ $course->duration }} minutes</span>
                        </div>
                        <div class="flex items-center text-gray-500 text-sm mb-4">
                            <i class="fa-solid fa-user-tie mr-1"></i>
                            <span>Coach: <a href="{{ route('courses.coach', $course->coach) }}" class="text-blue-600 hover:underline">{{ $course->coach->name ?? 'Unknown' }}</a></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-600 font-bold">€{{ $course->price ?? '45.00' }}</span>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">{{ $remainingPlaces }} places left</span>
                                @if($remainingPlaces > 0)
                                    @if(Auth::check() && Auth::user()->role === 'surfeur')
                                        <form action="{{ route('courses.book', $course) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fa-solid fa-bookmark mr-1"></i> Book Now
                                            </button>
                                        </form>
                                    @elseif(!Auth::check())
                                        <a href="{{ route('login') }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fa-solid fa-right-to-bracket mr-1"></i> Login to Book
                                        </a>
                                    @else
                                        <a href="{{ route('courses.show', $course) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fa-solid fa-circle-info mr-1"></i> View Details
                                        </a>
                                    @endif
                                @else
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                                        <i class="fa-solid fa-list-check mr-1"></i> Join Waitlist
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <div class="text-gray-500">
                        <i class="fa-solid fa-face-sad-tear text-4xl mb-4"></i>
                        <h3 class="text-xl font-medium mb-2">No courses available</h3>
                        <p>There are currently no upcoming courses scheduled.</p>
                        <p class="mt-4">Please check back later or contact us for more information.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection 
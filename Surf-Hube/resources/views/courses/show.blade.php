@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <div class="bg-gray-50 py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header with Breadcrumbs -->
            <div class="mb-6">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fa-solid fa-home mr-2"></i> Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('courses.browse') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-600">Courses</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">{{ $course->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
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

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Course Image Banner -->
                <div class="relative h-64 sm:h-96">
                    @if ($course->thumbnail)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $course->thumbnail) }}"
                            alt="{{ $course->title }}">
                    @else
                        <img class="w-full h-full object-cover"
                            src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="{{ $course->title }}">
                    @endif

                    <!-- Course Status Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="bg-blue-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-md">
                            @if ($course->date->isPast())
                                Completed
                            @elseif($course->date->isToday())
                                Today
                            @elseif($course->date->diffInDays(now()) <= 7)
                                This Week
                            @else
                                Upcoming
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start">
                        <!-- Left Column: Course Details -->
                        <div class="lg:w-2/3 lg:pr-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

                            <div class="flex items-center space-x-4 text-gray-500 text-sm mb-6">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-calendar-days mr-1.5"></i>
                                    <span>{{ $course->date->format('l, M d, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa-solid fa-clock mr-1.5"></i>
                                    <span>{{ $course->date->format('g:i A') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa-solid fa-hourglass-half mr-1.5"></i>
                                    <span>{{ $course->duration }} min</span>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                                <div class="prose max-w-none text-gray-700">
                                    <p>{{ $course->description }}</p>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-3">What to Expect</h2>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-2"></i>
                                        <span>Professional instruction tailored to your skill level</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-2"></i>
                                        <span>All necessary equipment provided (surfboard, wetsuit)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-2"></i>
                                        <span>Safety instructions and water safety supervision</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-2"></i>
                                        <span>Small group size for personalized attention</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-green-500 mt-1 mr-2"></i>
                                        <span>Photos of your session (available for purchase)</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-3">What to Bring</h2>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-arrow-right text-blue-500 mt-1 mr-2"></i>
                                        <span>Swimsuit or swim trunks</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-arrow-right text-blue-500 mt-1 mr-2"></i>
                                        <span>Towel</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-arrow-right text-blue-500 mt-1 mr-2"></i>
                                        <span>Sunscreen</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-arrow-right text-blue-500 mt-1 mr-2"></i>
                                        <span>Water bottle</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-arrow-right text-blue-500 mt-1 mr-2"></i>
                                        <span>Positive attitude and energy!</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Right Column: Booking Card -->
                        <div class="lg:w-1/3 mt-8 lg:mt-0">
                            <div class="bg-gray-50 rounded-lg shadow-md p-6">
                                <div class="text-center mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900">€{{ $course->price ?? '45.00' }}</h3>
                                    <p class="text-sm text-gray-500">per person</p>
                                </div>

                                @php
                                    $bookedCount = $course->reservations()->where('status', 'confirmed')->count();
                                    $remainingPlaces = $course->available_places - $bookedCount;
                                @endphp

                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Available spots</span>
                                        <span
                                            class="font-medium {{ $remainingPlaces > 5 ? 'text-green-600' : ($remainingPlaces > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $remainingPlaces }} / {{ $course->available_places }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-{{ $remainingPlaces > 5 ? 'green' : ($remainingPlaces > 0 ? 'yellow' : 'red') }}-600 h-2.5 rounded-full"
                                            style="width: {{ (($course->available_places - $remainingPlaces) / $course->available_places) * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <div class="flex items-center mb-3">
                                        <img class="h-10 w-10 rounded-full mr-3"
                                            src="{{ $course->coach->profile_photo ? asset('storage/' . $course->coach->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($course->coach->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                            alt="{{ $course->coach->name }}">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $course->coach->name }}</p>
                                            <p class="text-xs text-gray-500">Professional Surf Coach</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('coach.public.courses', $course->coach) }}"
                                        class="text-sm text-blue-600 hover:underline">View all courses by this coach</a>
                                </div>

                                <!-- Booking Actions -->
                                @if (Auth::check() && Auth::user()->role === 'surfeur')
                                    @if ($remainingPlaces > 0 && !$course->date->isPast())
                                        <form action="{{ route('courses.book', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-3">
                                                <i class="fa-solid fa-bookmark mr-2"></i> Book Now
                                            </button>
                                        </form>
                                    @elseif($course->date->isPast())
                                        <div class="text-center mb-3">
                                            <span
                                                class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md text-gray-600">
                                                <i class="fa-solid fa-calendar-xmark mr-2"></i> Course Completed
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-center mb-3">
                                            <button type="button"
                                                class="w-full bg-gray-400 text-white py-3 px-4 rounded-md font-medium cursor-not-allowed">
                                                <i class="fa-solid fa-list-check mr-2"></i> Join Waitlist
                                            </button>
                                        </div>
                                    @endif
                                @elseif(!Auth::check())
                                    <a href="{{ route('login') }}"
                                        class="block text-center w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-3">
                                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Login to Book
                                    </a>
                                @endif

                                <div class="mt-4 text-center">
                                    <a href="{{ route('courses.browse') }}"
                                        class="text-blue-600 hover:underline text-sm">
                                        <i class="fa-solid fa-arrow-left mr-1"></i> Back to all courses
                                    </a>
                                </div>
                            </div>

                            <!-- Refund Policy -->
                            <div class="mt-6 bg-white rounded-lg border border-gray-200 p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Refund Policy</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-check text-green-500 mt-0.5 mr-1.5 text-xs"></i>
                                        <span>Full refund if cancelled 48+ hours before start</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-check text-green-500 mt-0.5 mr-1.5 text-xs"></i>
                                        <span>50% refund if cancelled 24-48 hours before</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-xmark text-red-500 mt-0.5 mr-1.5 text-xs"></i>
                                        <span>No refund if cancelled less than 24 hours</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Courses -->
            @if (isset($relatedCourses) && $relatedCourses->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">More Courses by {{ $course->coach->name }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($relatedCourses as $relatedCourse)
                            <!-- Related Course Card -->
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative">
                                    @if ($relatedCourse->thumbnail)
                                        <img class="w-full h-48 object-cover"
                                            src="{{ asset('storage/' . $relatedCourse->thumbnail) }}"
                                            alt="{{ $relatedCourse->title }}">
                                    @else
                                        <img class="w-full h-48 object-cover"
                                            src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                            alt="{{ $relatedCourse->title }}">
                                    @endif
                                    <div
                                        class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $relatedCourse->date->format('M d') }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 mb-2">{{ $relatedCourse->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">
                                        {{ Str::limit($relatedCourse->description, 80) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-blue-600 font-semibold">€{{ $relatedCourse->price ?? '45.00' }}</span>
                                        <a href="{{ route('courses.show', $relatedCourse) }}"
                                            class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

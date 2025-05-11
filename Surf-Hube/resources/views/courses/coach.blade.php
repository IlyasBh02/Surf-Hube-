@extends('layouts.app')

@section('title', 'Coach Profile - ' . $coach->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Coach Profile Header -->
        <div class="bg-blue-600 px-6 py-8 text-white">
            <div class="flex flex-col md:flex-row items-center">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center text-blue-600 text-4xl overflow-hidden">
                        @if($coach->profile_picture)
                            <img src="{{ asset('storage/' . $coach->profile_picture) }}" alt="{{ $coach->name }}" class="h-full w-full object-cover">
                        @else
                            <span>{{ substr($coach->name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold">{{ $coach->name }}</h1>
                    <div class="mt-1 text-blue-200">
                        <span class="px-2 py-1 bg-blue-700 rounded-full text-xs font-medium">Surf Coach</span>
                        @if($coach->coach_approved)
                            <span class="ml-2 px-2 py-1 bg-green-500 rounded-full text-xs font-medium">Verified</span>
                        @endif
                    </div>
                    <p class="mt-3 max-w-2xl">{{ $coach->bio ?? 'Professional surf coach passionate about teaching surfing skills to enthusiasts of all levels.' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Coach Experience and Stats -->
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 text-blue-600">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Experience</div>
                        <div class="text-lg font-medium text-gray-900">{{ $coach->years_experience ?? rand(1, 10) }}+ years</div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 text-blue-600">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Courses Given</div>
                        <div class="text-lg font-medium text-gray-900">{{ $totalCourses ?? count($courses) }}</div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 text-blue-600">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-500">Students Taught</div>
                        <div class="text-lg font-medium text-gray-900">{{ $totalStudents ?? $courses->sum('reservations_count') ?? '50+' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coach Description -->
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-3">About Me</h2>
            <div class="prose max-w-none text-gray-700">
                <p>{{ $coach->description ?? 'I am a passionate surf instructor with years of experience teaching surfers of all levels. My teaching philosophy centers around building confidence in the water while ensuring safety and fun are the top priorities. I tailor each lesson to match the individual needs and goals of my students.' }}</p>
            </div>
        </div>
        
        <!-- Coaching Style -->
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-3">Teaching Style</h2>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Patient</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Encouraging</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Detailed</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Safety-focused</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Fun</span>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Courses -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Upcoming Courses with {{ $coach->name }}</h2>
        
        @if(count($courses) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                        <!-- Course Image -->
                        <div class="h-48 bg-blue-600 relative">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-500">
                                    <i class="fa-solid fa-water text-5xl"></i>
                                </div>
                            @endif
                            <!-- Date badge -->
                            <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-medium text-blue-600">
                                {{ $course->date->format('d M Y') }}
                            </div>
                        </div>
                        
                        <!-- Course Details -->
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                            <div class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ Str::limit($course->description, 100) }}
                            </div>
                            
                            <!-- Course Metadata -->
                            <div class="flex flex-wrap gap-y-2">
                                <div class="w-1/2 flex items-center text-sm text-gray-600">
                                    <i class="fa-solid fa-clock mr-2 text-blue-500"></i>
                                    {{ $course->date->format('H:i') }} ({{ $course->duration }}min)
                                </div>
                                <div class="w-1/2 flex items-center text-sm text-gray-600">
                                    <i class="fa-solid fa-users mr-2 text-blue-500"></i>
                                    @php
                                        $bookedCount = $course->reservations()->where('status', 'confirmed')->count();
                                        $remainingPlaces = $course->available_places - $bookedCount;
                                    @endphp
                                    {{ $bookedCount }}/{{ $course->available_places }} booked
                                </div>
                            </div>
                            
                            <!-- Course Action Button -->
                            <div class="mt-4">
                                <a href="{{ route('courses.show', $course) }}" class="inline-block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md text-center transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="inline-block rounded-full bg-blue-100 p-4 mb-4">
                    <i class="fa-solid fa-calendar-xmark text-blue-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No upcoming courses</h3>
                <p class="text-gray-600">This coach doesn't have any upcoming courses scheduled at the moment.</p>
                <a href="{{ route('courses.browse') }}" class="mt-4 inline-block py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                    Browse All Courses
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 
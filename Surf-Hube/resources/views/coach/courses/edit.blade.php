@extends('layouts.dashboard')

@section('title', 'Edit Course')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('page-title', 'Edit Course')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Course</h1>
            <p class="text-gray-600">Update your course details</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('coach.courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-eye mr-2"></i> View Course
            </a>
            <a href="{{ route('coach.courses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Courses
            </a>
        </div>
    </div>

    <div class="content-card">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Course Information</h2>

        <form action="{{ route('coach.courses.update', $course->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">Please fix the following errors:</p>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Course Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Course Title <span class="text-red-600">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="e.g., Beginner Surf Lessons">
                <p class="mt-1 text-xs text-gray-500">Give your course a descriptive title (max 255 characters)</p>
            </div>

            <!-- Course Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Course Description <span class="text-red-600">*</span></label>
                <textarea name="description" id="description" rows="5" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course Thumbnail -->
            <div>
                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Course Thumbnail</label>
                
                @if($course->thumbnail)
                <div class="mt-2 mb-4">
                    <p class="text-sm text-gray-500 mb-2">Current thumbnail:</p>
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-48 h-auto rounded-md border border-gray-200">
                </div>
                @endif
                
                <input type="file" name="thumbnail" id="thumbnail" 
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                       accept="image/*">
                <p class="mt-1 text-xs text-gray-500">Upload a new image for your course (JPEG, PNG, GIF - max 2MB)</p>
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Course Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Course Date <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="date" id="date" 
                        value="{{ old('date', $course->date->format('Y-m-d\TH:i')) }}" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Select date and start time</p>
                </div>

                <!-- Course Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes) <span class="text-red-600">*</span></label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration', $course->duration) }}" required min="15" max="480"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Duration in minutes (15-480)</p>
                </div>
            </div>

            <!-- Available Places -->
            <div>
                <label for="available_places" class="block text-sm font-medium text-gray-700">Available Places <span class="text-red-600">*</span></label>
                <input type="number" name="available_places" id="available_places" value="{{ old('available_places', $course->available_places) }}" required min="1" max="100"
                    class="mt-1 block w-full md:w-1/4 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Maximum number of students who can join</p>
                
                @php
                    $bookedCount = $course->reservations->where('status', 'confirmed')->count();
                @endphp
                
                @if($bookedCount > 0)
                    <div class="mt-2 text-sm text-amber-600">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        This course already has {{ $bookedCount }} confirmed reservations. The available places cannot be set below this number.
                    </div>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (€) <span class="text-red-600">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price', $course->price ?? 45.00) }}" required min="0" step="0.01"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Course price in euros</p>
                </div>
                
                <!-- Skill Level -->
                <div>
                    <label for="level" class="block text-sm font-medium text-gray-700">Skill Level <span class="text-red-600">*</span></label>
                    <select name="level" id="level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Required skill level for participants</p>
                </div>
                
                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $course->location) }}" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="e.g., North Beach">
                    <p class="mt-1 text-xs text-gray-500">Where the course will take place</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-save mr-2"></i> Update Course
                </button>
                <a href="{{ route('coach.courses.index') }}" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection 
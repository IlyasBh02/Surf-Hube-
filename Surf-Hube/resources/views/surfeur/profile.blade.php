@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('dashboard-type', 'surfeur')
@section('user-role', 'surfeur')
@section('user-name', Auth::user()->name)
@section('status-message', 'Surfeur')

@section('sidebar-menu')
    @include('partials.surfeur-sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="mt-1 text-gray-600">Manage your account information</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                </div>
                
                <form method="POST" action="{{ route('surfer.profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    @if(session('status') === 'profile-updated')
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            <span class="font-medium">Success!</span> Your profile information has been updated.
                        </div>
                    @endif
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Profile Photo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                        <div class="mt-1 flex items-center">
                            <div class="mr-4">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('profile_photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Update Password -->
            <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a secure password.</p>
                </div>
                
                <form method="POST" action="{{ route('password.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    @if(session('status') === 'password-updated')
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            <span class="font-medium">Success!</span> Your password has been updated.
                        </div>
                    @endif
                    
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Account Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Account Type -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Account Type</h4>
                            <p class="mt-1 text-gray-900 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-user mr-1"></i> Surfeur
                                </span>
                            </p>
                        </div>
                        
                        <!-- Member Since -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Member Since</h4>
                            <p class="mt-1 text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                        
                        <!-- Last Login -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Last Login</h4>
                            <p class="mt-1 text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('F j, Y \a\t g:i A') : 'Never' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Surf Stats -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Surf Stats</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Courses Completed -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Courses Completed</h4>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $completedCoursesCount ?? 0 }}</p>
                        </div>
                        
                        <!-- Upcoming Courses -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Upcoming Courses</h4>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $upcomingCoursesCount ?? 0 }}</p>
                        </div>
                        
                        <!-- Skill Level -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Skill Level</h4>
                            <p class="mt-1 flex items-center">
                                @php
                                    $skillLevel = 'Newcomer';
                                    if (isset($completedCoursesCount)) {
                                        if ($completedCoursesCount >= 10) {
                                            $skillLevel = 'Advanced';
                                        } elseif ($completedCoursesCount >= 5) {
                                            $skillLevel = 'Intermediate';
                                        } elseif ($completedCoursesCount >= 1) {
                                            $skillLevel = 'Beginner';
                                        }
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($skillLevel === 'Advanced') bg-purple-100 text-purple-800
                                    @elseif($skillLevel === 'Intermediate') bg-blue-100 text-blue-800
                                    @elseif($skillLevel === 'Beginner') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $skillLevel }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Actions -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Account Actions</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Delete Account -->
                    <a href="#" class="block w-full px-4 py-2 bg-red-600 text-center text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fa-solid fa-trash-alt mr-2"></i> Delete Account
                    </a>
                    
                    <!-- Download Personal Data -->
                    <a href="#" class="block w-full px-4 py-2 bg-gray-200 text-center text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fa-solid fa-download mr-2"></i> Download Data
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection 
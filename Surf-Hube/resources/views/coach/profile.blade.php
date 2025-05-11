@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-600">Manage your personal information and account settings</p>
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

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Information Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Picture Section -->
        <div class="col-span-1">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Picture</h2>
                
                <div class="flex flex-col items-center">
                    <div class="w-40 h-40 bg-gray-100 rounded-full overflow-hidden mb-4 flex items-center justify-center">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user-tie text-gray-400 text-6xl"></i>
                        @endif
                    </div>
                    
                    <form action="{{ route('coach.profile.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        <div class="mb-4">
                            <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Change Picture</label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" 
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm
                                          focus:ring-blue-500 focus:border-blue-500">
                            @error('profile_picture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Picture
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Account Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Account Status</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Account Type</h3>
                        <p class="mt-1 text-sm text-gray-900">Coach</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Account Status</h3>
                        <div class="mt-1">
                            @if(Auth::user()->coach_approved)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fa-solid fa-clock mr-1"></i> Pending Approval
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Member Since</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ Auth::user()->updated_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Information Form -->
        <div class="col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
                
                <form action="{{ route('coach.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" disabled
                               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Email cannot be changed. Contact support if you need to update your email.</p>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Bio (Short Introduction)</label>
                        <textarea id="bio" name="bio" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">A brief introduction that will appear on your profile</p>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
                        <textarea id="description" name="description" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', Auth::user()->description ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Detailed information about your teaching style, philosophy, etc.</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="years_experience" class="block text-sm font-medium text-gray-700">Years of Experience</label>
                            <input type="number" name="years_experience" id="years_experience" min="0" 
                                   value="{{ old('years_experience', Auth::user()->years_experience ?? 0) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('years_experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="specialties" class="block text-sm font-medium text-gray-700">Specialties</label>
                            <input type="text" name="specialties" id="specialties" 
                                   value="{{ old('specialties', Auth::user()->specialties ?? '') }}"
                                   placeholder="e.g., Beginners, Advanced techniques, Kids"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('specialties')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Security Settings -->
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Security</h2>
                
                <div class="border rounded-md p-4 bg-gray-50">
                    <p class="text-sm text-gray-700">
                        To change your password, please use the password reset function on the login page.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.main')

@section('title', 'Join SurfHub - Sign Up')

@section('styles')
<style>
    .form-section {
        background-image: url('https://images.unsplash.com/photo-1519789110007-0e751882be76?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .form-overlay {
        background: rgba(0, 0, 0, 0.5);
    }
    
    .form-container {
        background-color: rgba(255, 255, 255, 0.95);
    }
</style>
@endsection

@section('content')
<div class="form-section min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="form-overlay absolute inset-0"></div>
    
    <div class="form-container relative z-10 max-w-md w-full space-y-8 p-10 rounded-xl shadow-xl">
        <div>
            <div class="flex justify-center">
                <span class="text-blue-600 font-bold text-3xl">SurfHub</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 ml-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m0 0v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l2-1m-2 1l-2-1m2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Join our community and start your surfing journey
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Please fix the following errors:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="name" class="sr-only">Full Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Full Name">
                </div>
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Password">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Confirm Password">
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">I want to register as:</p>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative bg-white rounded-lg border border-gray-300 p-4 flex cursor-pointer hover:border-blue-500 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <input type="radio" name="role" value="surfeur" class="sr-only" checked>
                        <div class="flex items-center justify-center w-full">
                            <div class="flex items-center">
                                <div class="text-sm flex flex-col items-center">
                                    <i class="fa-solid fa-person-swimming text-blue-500 text-3xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Surfeur</p>
                                    <p class="text-gray-500">Join surfing courses</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                    </label>
                    
                    <label class="relative bg-white rounded-lg border border-gray-300 p-4 flex cursor-pointer hover:border-blue-500 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <input type="radio" name="role" value="coach" class="sr-only">
                        <div class="flex items-center justify-center w-full">
                            <div class="flex items-center">
                                <div class="text-sm flex flex-col items-center">
                                    <i class="fa-solid fa-user-tie text-blue-500 text-3xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Coach</p>
                                    <p class="text-gray-500">Teach surfing courses</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                    </label>
                </div>
            </div>
            
            <div class="text-xs text-gray-600 mt-4">
                <p class="mb-1">Password must include:</p>
                <ul class="list-disc list-inside pl-2 space-y-1">
                    <li>At least 8 characters</li>
                    <li>At least one uppercase letter</li>
                    <li>At least one lowercase letter</li>
                    <li>At least one number</li>
                    <li>At least one special character</li>
                </ul>
            </div>
            
            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-user-plus text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Create Account
                </button>
            </div>
            
            <div class="text-sm text-center mt-4">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign in instead
                    </a>
                </p>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-4">
                <div class="text-xs text-gray-500">
                    By registering, you agree to our
                    <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> and
                    <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>.
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    <p><i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> <strong>Note for Coaches:</strong> Your account will require admin approval before you can create courses.</p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Highlight selected role
    document.addEventListener('DOMContentLoaded', function () {
        const roleInputs = document.querySelectorAll('input[name="role"]');
        
        roleInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Remove highlight from all labels
                document.querySelectorAll('input[name="role"]').forEach(radio => {
                    const label = radio.closest('label');
                    if (label) {
                        label.querySelector('.absolute').classList.remove('border-blue-500');
                    }
                });
                
                // Add highlight to selected label
                if (this.checked) {
                    const selectedLabel = this.closest('label');
                    if (selectedLabel) {
                        selectedLabel.querySelector('.absolute').classList.add('border-blue-500');
                    }
                }
            });
        });
        
        // Initialize with first option selected
        const firstInput = document.querySelector('input[name="role"]:checked');
        if (firstInput) {
            const label = firstInput.closest('label');
            if (label) {
                label.querySelector('.absolute').classList.add('border-blue-500');
            }
        }
    });
</script>
@endsection 
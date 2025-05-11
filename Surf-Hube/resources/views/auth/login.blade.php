@extends('layouts.main')

@section('title', 'Login to SurfHub')

@section('styles')
<style>
    .form-section {
        background-image: url('https://images.unsplash.com/photo-1502208327471-d5dde4d78995?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .form-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.6) 100%);
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
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Welcome back to SurfHub
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        Forgot your password?
                    </a>
                </div>
            </div>
            
            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-right-to-bracket text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Sign in
                </button>
            </div>
            
            <div class="text-sm text-center mt-4">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign up now
                    </a>
                </p>
            </div>
        </form>
        
        <div class="flex items-center justify-center mt-6">
            <div class="bg-blue-100 rounded-md p-4 text-sm text-blue-700">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-info text-blue-500 mr-2"></i>
                    </div>
                    <div>
                        <p><strong>Coach accounts:</strong> After login, you will be able to create and manage your courses.</p>
                        <p class="mt-1"><strong>Surfeur accounts:</strong> You can browse and book courses immediately after login.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
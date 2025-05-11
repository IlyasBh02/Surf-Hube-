<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SurfHub - @yield('title', 'Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Additional Styles -->
    <style>
        /* Dashboard specific styles */
        .sidebar-transition {
            transition: width 0.3s ease-in-out;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        /* Add these new styles */
        .dashboard-stats {
            @apply bg-white rounded-lg shadow-sm p-6;
            @apply border-l-4;
        }
        
        .content-card {
            @apply bg-white rounded-lg shadow-sm p-6;
        }
        
        .sidebar-link {
            @apply flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-100 transition-colors;
        }
        
        .sidebar-active {
            @apply bg-blue-50 text-blue-600;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <!-- Mobile Menu Button -->
                    <button id="sidebar-toggle" class="p-2 rounded-md lg:hidden focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fa-solid fa-bars text-gray-600"></i>
                    </button>
                    
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex ml-2 md:mr-24 items-center">
                        <span class="self-center text-xl font-bold whitespace-nowrap text-blue-600">SurfHub</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m0 0v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l2-1m-2 1l-2-1m2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                        </svg>
                    </a>
                </div>
                
                <div class="flex items-center">
                    <!-- User Menu -->
                    <div x-data="{ isOpen: false }" class="ml-3 relative">
                        <div>
                            <button @click="isOpen = !isOpen" type="button" class="flex text-sm rounded-full focus:outline-none" id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                        </div>
                        
                        <div x-show="isOpen" 
                             @click.away="isOpen = false" 
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none" 
                             role="menu" 
                             aria-orientation="vertical" 
                             aria-labelledby="user-menu-button" 
                             tabindex="-1">
                            <div class="py-1" role="none">
                                <span class="block px-4 py-2 text-sm text-gray-900" role="menuitem">{{ Auth::user()->name }}</span>
                                <span class="block px-4 py-2 text-xs text-gray-500" role="menuitem">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="py-1" role="none">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    <i class="fa-solid fa-gauge-high mr-2"></i> Dashboard
                                </a>
                                @if(Auth::user()->role === 'surfeur')
                                    <a href="{{ route('surfer.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fa-solid fa-user mr-2"></i> My Profile
                                    </a>
                                @elseif(Auth::user()->role === 'coach')
                                    <a href="{{ route('coach.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fa-solid fa-user mr-2"></i> My Profile
                                    </a>
                                @endif
                            </div>
                            <div class="py-1" role="none">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar & Content -->
    <div class="flex pt-16 overflow-hidden bg-gray-100 h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed hidden lg:flex lg:flex-shrink-0 z-20 h-full w-64 transition-width duration-300">
            <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white">
                <!-- User Info -->
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="px-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-base font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-sm font-medium text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->role === 'coach' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        @yield('status-message')
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="mt-5 px-4 space-y-1">
                        <div class="space-y-2">
                            @yield('sidebar-menu')
                        </div>
                    </nav>
                </div>
                
                <!-- Bottom Links -->
                <div class="p-4 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:bg-gray-100 rounded-md p-2">
                        <i class="fa-solid fa-house mr-2"></i> Return to Home
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="flex w-full items-center text-gray-600 hover:bg-gray-100 rounded-md p-2">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-10 bg-gray-900 opacity-50 hidden lg:hidden"></div>
        
        <!-- Main Content -->
        <div id="main-content" class="lg:ml-64 flex-1 relative z-0 overflow-y-auto pb-10 px-4 sm:px-6 lg:px-8 py-8">
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
            
            <!-- Content -->
            @yield('content')
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const mainContent = document.getElementById('main-content');
            
            function toggleSidebar() {
                sidebar.classList.toggle('hidden');
                sidebarBackdrop.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
            
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarBackdrop.addEventListener('click', toggleSidebar);
        });
    </script>
    
    @yield('scripts')
</body>
</html> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SurfHub - @yield('title', 'Catch Your Perfect Wave')</title>
        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js for dropdowns -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <!-- Additional custom styles -->
        <style>
            .wave-bg {
                background-image: url('https://images.unsplash.com/photo-1513569771920-c9e1d31714af?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
                background-size: cover;
                background-position: center bottom;
            }
            .footer-wave {
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%231e40af" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,170.7C384,181,480,171,576,149.3C672,128,768,96,864,90.7C960,85,1056,107,1152,122.7C1248,139,1344,149,1392,154.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
                height: 75px;
                width: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                margin-bottom: -1px;
            }
        </style>
        @yield('styles')
    </head>
    <body class="bg-gray-50 min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <!-- Logo -->
                            <a href="{{ route('home') }}" class="flex items-center">
                                <span class="text-blue-600 font-bold text-2xl">SurfHub</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m0 0v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l2-1m-2 1l-2-1m2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                </svg>
                            </a>
                        </div>
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            <!-- Navigation Links -->
                            <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fa-solid fa-house mr-1"></i> Home
                            </a>
                            <a href="{{ route('courses.browse') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fa-solid fa-graduation-cap mr-1"></i> Courses
                            </a>
                            @if(Auth::check() && Auth::user()->role === 'surfeur')
                                <a href="{{ route('reservations.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    <i class="fa-solid fa-calendar-check mr-1"></i> My Reservations
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="hidden md:flex items-center">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fa-solid fa-right-to-bracket mr-1"></i> Log in
                            </a>
                            <a href="{{ route('register') }}" class="ml-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fa-solid fa-user-plus mr-1"></i> Sign up
                            </a>
                        @else
                            <div class="relative ml-3" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" type="button" class="flex text-sm bg-blue-500 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </button>
                                </div>
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                     role="menu" 
                                     aria-orientation="vertical" 
                                     aria-labelledby="user-menu-button" 
                                     tabindex="-1"
                                     style="display: none;">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fa-solid fa-gauge-high mr-1"></i> Dashboard
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fa-solid fa-user mr-1"></i> Your Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <i class="fa-solid fa-bars h-6 w-6"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        <i class="fa-solid fa-house mr-1"></i> Home
                    </a>
                    <a href="{{ route('courses.browse') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        <i class="fa-solid fa-graduation-cap mr-1"></i> Courses
                    </a>
                    @if(Auth::check() && Auth::user()->role === 'surfeur')
                        <a href="{{ route('reservations.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fa-solid fa-calendar-check mr-1"></i> My Reservations
                        </a>
                    @endif
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="mt-3 space-y-1">
                        @guest
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                <i class="fa-solid fa-right-to-bracket mr-1"></i> Log in
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                <i class="fa-solid fa-user-plus mr-1"></i> Sign up
                            </a>
                        @else
                            <div class="flex items-center px-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    <i class="fa-solid fa-gauge-high mr-1"></i> Dashboard
                                </a>
                                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    <i class="fa-solid fa-user mr-1"></i> Your Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Sign out
                                    </button>
                                </form>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content with top padding to account for fixed navbar -->
        <main class="flex-grow pt-16">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="footer-wave"></div>
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo and Description -->
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center mb-4">
                            <span class="text-blue-400 font-bold text-2xl">SurfHub</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m0 0v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l2-1m-2 1l-2-1m2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm">
                            SurfHub connects passionate surfers with professional coaches for unforgettable surf experiences.
                        </p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-400 hover:text-blue-400">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-span-1 md:col-span-1">
                        <h3 class="text-lg font-semibold text-white mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-blue-400">Home</a></li>
                            <li><a href="{{ route('courses.browse') }}" class="text-gray-400 hover:text-blue-400">Find Courses</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-blue-400">About Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-blue-400">Contact</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-blue-400">FAQ</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-span-1 md:col-span-1">
                        <h3 class="text-lg font-semibold text-white mb-4">Contact Us</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li class="flex items-start">
                                <i class="fa-solid fa-location-dot mt-1 mr-2"></i>
                                <span>123 Surf Avenue, Beach City, Ocean State 98765</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fa-solid fa-phone mr-2"></i>
                                <span>+1 (234) 567-8901</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fa-solid fa-envelope mr-2"></i>
                                <span>info@surfhub.com</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="col-span-1 md:col-span-1">
                        <h3 class="text-lg font-semibold text-white mb-4">Subscribe</h3>
                        <p class="text-gray-400 text-sm mb-4">
                            Sign up for our newsletter to receive surf tips and exclusive offers.
                        </p>
                        <form class="flex flex-col sm:flex-row gap-2">
                            <input type="email" placeholder="Your email" class="px-4 py-2 rounded-md text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} SurfHub. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-blue-400 text-sm">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            });
        </script>
        @yield('scripts')
    </body>
</html> 
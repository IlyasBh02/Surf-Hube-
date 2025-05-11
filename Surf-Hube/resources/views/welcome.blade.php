@extends('layouts.main')

@section('title', 'Catch Your Perfect Wave')

@section('styles')
    <style>
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1455729552865-3658a5d39692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%);
        }

        .parallax-cta {
            background-image: url('https://images.unsplash.com/photo-1520116468816-95b69f847357?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 500px;
        }

        .overlay-dark {
            background: rgba(0, 0, 0, 0.6);
        }

        .featured-surfer {
            position: absolute;
            right: 5%;
            bottom: 0;
            height: 80%;
            z-index: 5;
            display: none;
        }

        @media (min-width: 1024px) {
            .featured-surfer {
                display: block;
            }

            .hero-content {
                text-align: left;
                margin-right: 40%;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section with Image -->
    <div class="hero-bg relative h-screen flex items-center justify-center">
        <!-- Dark Overlay -->
        <div class="hero-overlay absolute inset-0"></div>

        <!-- Wave Animation at Bottom -->
        <div class="wave-animation z-20"></div>

        <!-- Hero Content -->
        <div class="hero-content relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
                <span class="block">Ride the Perfect Wave</span>
                <span class="block text-blue-400">Master the Art of Surfing</span>
            </h1>
            <p class="mt-6 text-xl text-white max-w-3xl mx-auto lg:mx-0">
                Join the SurfHub community and learn from professional coaches. Book your surfing courses today and
                experience the thrill of the ocean.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}"
                    class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 shadow-lg transform transition hover:-translate-y-1">
                    <i class="fa-solid fa-surfboard mr-2"></i> Get Started
                </a>
                <a href="#"
                    class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-100 md:py-4 md:text-lg md:px-10 shadow-lg transform transition hover:-translate-y-1">
                    <i class="fa-solid fa-circle-info mr-2"></i> Learn More
                </a>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Upcoming Surf Courses
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Book your spot in one of our professionally guided surf lessons
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 rounded-lg shadow-sm p-6 mb-10">
                <form method="GET" action="{{ route('courses.browse') }}">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <h3 class="text-lg font-medium text-gray-900">Filter Courses</h3>
                        <div class="flex flex-wrap gap-4">
                            <!-- Date Filter -->
                            <div class="w-full md:w-auto">
                                <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <select id="dateFilter" name="date"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="all">All Dates</option>
                                    <option value="today">Today</option>
                                    <option value="tomorrow">Tomorrow</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                </select>
                            </div>

                            <!-- Coach Filter -->
                            <div class="w-full md:w-auto">
                                <label for="coachFilter" class="block text-sm font-medium text-gray-700 mb-1">Coach</label>
                                <select id="coachFilter" name="coach_id"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">All Coaches</option>
                                    @foreach($coaches as $coach)
                                        <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Available Places Filter -->
                            <div class="w-full md:w-auto">
                                <label for="placesFilter"
                                    class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                                <select id="placesFilter" name="availability"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="all">Any Availability</option>
                                    <option value="available">Available Places</option>
                                    <option value="limited">Limited Places (< 5)</option>
                                    <option value="full">Full (Waiting List)</option>
                                </select>
                            </div>

                            <!-- Reset & Apply Buttons -->
                            <div class="w-full md:w-auto flex items-end space-x-2">
                                <a href="{{ route('home') }}"
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
                                <span>Coach: {{ $course->coach->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-bold">€{{ $course->price ?? '45.00' }}</span>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">{{ $remainingPlaces }} places left</span>
                                    @if($remainingPlaces > 0)
                                        <a href="{{ route('courses.show', $course->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fa-solid fa-bookmark mr-1"></i> Book Now
                                        </a>
                                    @else
                                        <a href="#"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                                            <i class="fa-solid fa-list-check mr-1"></i> Join Waitlist
                                        </a>
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

            <!-- View All Courses Button -->
            <div class="mt-12 text-center">
                <a href="{{ route('courses.browse') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-graduation-cap mr-2"></i> View All Courses
                </a>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="bg-gradient-to-b from-blue-50 to-white py-16 px-4 sm:px-6 lg:px-8 lg:py-24">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block mb-1">
                    <i class="fa-solid fa-award text-blue-500 text-4xl"></i>
                </span>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Why Choose SurfHub
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Experience the difference with our world-class coaching and premium learning environment.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Benefits List -->
                <div>
                    <div class="space-y-10">
                        <!-- Benefit 1: Professional Coaches -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <i class="fa-solid fa-medal"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Professional Coaches</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Learn from certified coaches with international competitive experience, each passionate
                                    about helping you succeed.
                                </p>
                            </div>
                        </div>

                        <!-- Benefit 2: Prime Locations -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Prime Beach Locations</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Train at the best surf spots with perfect waves for your skill level, carefully selected
                                    for optimal learning conditions.
                                </p>
                            </div>
                        </div>

                        <!-- Benefit 3: Small Group Sizes -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Small Group Sizes</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Enjoy personalized attention with our limited group sizes ensuring safety and maximizing
                                    your learning experience.
                                </p>
                            </div>
                        </div>

                        <!-- Benefit 4: All Equipment Provided -->
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <i class="fa-solid fa-kit-medical"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Premium Equipment</h3>
                                <p class="mt-2 text-base text-gray-500">
                                    Use top-quality surfboards and wetsuits suitable for your level, all included in the
                                    course price.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Image -->
                <div class="relative lg:mt-0 overflow-hidden rounded-lg shadow-xl">
                    <img class="relative inset-0 h-full w-full object-cover"
                        src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1473&q=80"
                        alt="Perfect beach for surfing">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/50 to-transparent mix-blend-multiply">
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        <blockquote class="mt-4">
                            <p class="text-lg font-medium italic">
                                "The coaches at SurfHub transformed my surfing skills in just a few sessions. Best decision
                                I ever made!"
                            </p>
                            <footer class="mt-2 text-sm">
                                <div class="flex items-center">
                                    <div class="text-yellow-400">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="ml-2">— Michael R., SurfHub Student</span>
                                </div>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <!-- Stats Banner -->
            <div class="mt-20 bg-blue-600 rounded-xl shadow-2xl overflow-hidden">
                <div class="px-6 py-10 sm:px-12 sm:py-16 lg:flex lg:items-center">
                    <div class="lg:w-0 lg:flex-1">
                        <h2 class="text-3xl font-extrabold tracking-tight text-white">
                            Join our growing community of surfers
                        </h2>
                        <p class="mt-4 max-w-3xl text-lg text-blue-100">
                            Become part of something special with thousands of happy students.
                        </p>
                    </div>
                    <div class="mt-8 lg:mt-0 lg:ml-8 grid grid-cols-3 gap-5 text-center">
                        <div class="px-4 py-5 bg-white bg-opacity-10 rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-blue-200 truncate">
                                <i class="fa-solid fa-user-graduate text-2xl mb-2"></i>
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-white">5,000+</dd>
                            <dd class="mt-1 text-sm font-medium text-blue-200">Students</dd>
                        </div>
                        <div class="px-4 py-5 bg-white bg-opacity-10 rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-blue-200 truncate">
                                <i class="fa-solid fa-chalkboard-user text-2xl mb-2"></i>
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-white">10+</dd>
                            <dd class="mt-1 text-sm font-medium text-blue-200">Expert Coaches</dd>
                        </div>
                        <div class="px-4 py-5 bg-white bg-opacity-10 rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-blue-200 truncate">
                                <i class="fa-solid fa-star text-2xl mb-2"></i>
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-white">4.9</dd>
                            <dd class="mt-1 text-sm font-medium text-blue-200">Average Rating</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action with Parallax -->
    <div class="parallax-cta relative flex items-center justify-center">
        <div class="overlay-dark absolute inset-0"></div>
        <div class="relative z-10 max-w-5xl mx-auto py-24 px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-pulse inline-block mb-6 p-3 bg-blue-500 bg-opacity-80 rounded-full">
                <i class="fa-solid fa-wave-square text-white text-4xl"></i>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-6">
                Ready to Catch Your First Wave?
            </h2>
            <p class="text-xl md:text-2xl text-white mb-10 max-w-3xl mx-auto">
                Your surfing journey begins today. Join SurfHub and learn from the best coaches in the most beautiful
                beaches.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-gray-100 transform transition duration-300 hover:scale-105 shadow-lg">
                    <i class="fa-solid fa-user-plus text-xl mr-2"></i> Sign Up for Free
                </a>
                <a href="#"
                    class="mt-3 sm:mt-0 inline-flex items-center justify-center px-8 py-4 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:bg-opacity-20 transform transition duration-300 hover:scale-105">
                    <i class="fa-solid fa-video text-xl mr-2"></i> Watch Our Story
                </a>
            </div>
            <div class="mt-10 flex justify-center items-center gap-6 text-white">
                <div class="flex flex-col items-center">
                    <span class="font-bold text-xl">No Credit Card</span>
                    <span class="text-sm">Required to Sign Up</span>
                </div>
                <div class="h-10 w-px bg-white bg-opacity-30"></div>
                <div class="flex flex-col items-center">
                    <span class="font-bold text-xl">100% Satisfaction</span>
                    <span class="text-sm">Guaranteed</span>
                </div>
                <div class="h-10 w-px bg-white bg-opacity-30 hidden md:block"></div>
                <div class="flex flex-col items-center hidden md:flex">
                    <span class="font-bold text-xl">14-Day</span>
                    <span class="text-sm">Money Back Guarantee</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Simple parallax effect enhancement
        window.addEventListener('scroll', function() {
            const parallaxElements = document.querySelectorAll('.parallax-cta');
            let scrollPosition = window.pageYOffset;

            parallaxElements.forEach(element => {
                let elementOffset = element.offsetTop;
                let elementHeight = element.offsetHeight;
                let windowHeight = window.innerHeight;

                // Only apply effect when the element is in view
                if (scrollPosition + windowHeight > elementOffset &&
                    scrollPosition < elementOffset + elementHeight) {
                    // Adjust background position for parallax effect
                    element.style.backgroundPositionY = ((scrollPosition - elementOffset) * 0.4) + 'px';
                }
            });
        });
    </script>
@endsection

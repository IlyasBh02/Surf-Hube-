@extends('layouts.dashboard')

@section('title', 'Course Management')
@section('dashboard-type', 'Admin')
@section('dashboard-icon', 'fa-solid fa-user-shield')
@section('user-role', 'Administrator')
@section('user-name', Auth::user()->name ?? 'Admin')
@section('status-message', 'Admin Access')
@section('dashboard-home-link', route('admin.dashboard'))

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-users sidebar-icon"></i>
        <span>User Management</span>
    </a>
    
    <a href="{{ route('admin.coaches') }}" class="sidebar-link {{ request()->routeIs('admin.coaches*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user-tie sidebar-icon"></i>
        <span>Coach Approvals</span>
        @if($pendingCoachesCount ?? 0 > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $pendingCoachesCount }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.courses') }}" class="sidebar-link {{ request()->routeIs('admin.courses*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>Course Management</span>
    </a>
    
    <a href="{{ route('admin.reservations') }}" class="sidebar-link {{ request()->routeIs('admin.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>Reservations</span>
    </a>
    
    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gear sidebar-icon"></i>
        <span>Settings</span>
    </a>
@endsection

@section('page-title', 'Course Management')

@section('content')
    <!-- Stats Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Upcoming Courses -->
        <div class="dashboard-stats border-blue-500">
            <div class="text-3xl font-bold text-blue-500 mb-1">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $upcomingCourses }}</div>
            <div class="text-sm text-gray-500">Upcoming Courses</div>
            <div class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-calendar-week"></i> Within the next 7 days
            </div>
        </div>
        
        <!-- Popular Courses (most reservations) -->
        <div class="dashboard-stats border-purple-500">
            <div class="text-3xl font-bold text-purple-500 mb-1">
                <i class="fa-solid fa-fire"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $popularCoursesCount }}</div>
            <div class="text-sm text-gray-500">Popular Courses</div>
            <div class="text-xs text-purple-600 mt-2">
                <i class="fa-solid fa-users"></i> With 5+ reservations
            </div>
        </div>
        
        <!-- Completed Courses -->
        <div class="dashboard-stats border-green-500">
            <div class="text-3xl font-bold text-green-500 mb-1">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="text-3xl font-bold text-gray-700">{{ $completedCourses }}</div>
            <div class="text-sm text-gray-500">Completed Courses</div>
            <div class="text-xs text-green-600 mt-2">
                <i class="fa-solid fa-history"></i> Finished successfully
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="w-full md:w-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search-courses" name="search" placeholder="Search courses by title or coach..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="upcoming">Upcoming</option>
                <option value="completed">Completed</option>
            </select>
            
            <select id="coach-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Coaches</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                @endforeach
            </select>
            
            <button id="reset-filters" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-filter-circle-xmark mr-1"></i> Reset
            </button>
        </div>
    </div>
    
    <!-- Courses Table -->
    <div class="content-card mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coach</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservations</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="courses-table-body">
                    @foreach($courses as $course)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($course->thumbnail)
                                        <div class="h-12 w-16 flex-shrink-0 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-water"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($course->coach->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div class="ml-2 text-sm text-gray-900">{{ $course->coach->name ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $course->date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $course->date->format('g:i A') }} ({{ $course->duration }} min)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $course->reservations->count() }} / {{ $course->available_places }}
                                    </span>
                                    <div class="ml-2 w-24 bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(100, ($course->reservations->count() / max(1, $course->available_places)) * 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->date->isPast())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-check text-xs mr-1 text-gray-500"></i> Completed
                                    </span>
                                @elseif($course->date->isToday())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fa-solid fa-circle text-xs mr-1 text-green-500"></i> Today
                                    </span>
                                @elseif($course->date->isFuture() && $course->date->diffInDays(now()) <= 7)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fa-solid fa-clock text-xs mr-1 text-yellow-500"></i> Soon
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-calendar text-xs mr-1 text-blue-500"></i> Upcoming
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md view-course" 
                                       data-course-id="{{ $course->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded-md view-reservations"
                                       data-course-id="{{ $course->id }}">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                    
                                    @if($course->date->isFuture())
                                        <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this course? All reservations will also be deleted.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $courses->links() }}
    </div>
    
    <!-- Course Details Modal -->
    <div id="course-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 p-4">
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <div class="sm:flex sm:items-start">
                    <div id="modal-course-thumbnail-container" class="w-full mb-4 hidden">
                        <div class="rounded-lg overflow-hidden">
                            <img id="modal-course-thumbnail" src="" alt="Course Thumbnail" class="w-full h-auto object-cover max-h-48">
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa-solid fa-water text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-course-title"></h3>
                        <div class="mt-2">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Coach</label>
                                    <p id="modal-course-coach" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Description</label>
                                    <p id="modal-course-description" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Date & Time</label>
                                    <p id="modal-course-date" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Duration</label>
                                    <p id="modal-course-duration" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Available Places</label>
                                    <p id="modal-course-places" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Reservations</label>
                                    <p id="modal-course-reservations" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Status</label>
                                    <p id="modal-course-status" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="modal-view-reservations" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        View Reservations
                    </button>
                    <button type="button" class="close-modal inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none mt-3 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // View Course Modal
        const courseModal = document.getElementById('course-modal');
        const viewCourseButtons = document.querySelectorAll('.view-course');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        
        // Functions to open and close modal
        function openModal() {
            courseModal.classList.remove('hidden');
        }
        
        function closeModal() {
            courseModal.classList.add('hidden');
        }
        
        // Open modal with course data
        viewCourseButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const courseId = this.getAttribute('data-course-id');
                const courseRow = this.closest('tr');
                
                // Get course data from the row
                const courseTitle = courseRow.querySelector('.text-gray-900').textContent;
                const courseDescription = courseRow.querySelector('.text-gray-500').textContent;
                const courseCoach = courseRow.querySelector('td:nth-child(2) .text-gray-900').textContent.trim();
                const courseDate = courseRow.querySelector('td:nth-child(3) .text-gray-900').textContent;
                const courseTime = courseRow.querySelector('td:nth-child(3) .text-gray-500').textContent;
                const coursePlacesText = courseRow.querySelector('td:nth-child(4) .px-2').textContent.trim();
                const courseStatus = courseRow.querySelector('td:nth-child(5) .inline-flex').textContent.trim();
                
                // Check if course has thumbnail
                const thumbnail = courseRow.querySelector('td:nth-child(1) img');
                const thumbnailContainer = document.getElementById('modal-course-thumbnail-container');
                const thumbnailImage = document.getElementById('modal-course-thumbnail');
                
                if (thumbnail) {
                    thumbnailImage.src = thumbnail.src;
                    thumbnailContainer.classList.remove('hidden');
                } else {
                    thumbnailContainer.classList.add('hidden');
                }
                
                // Update modal content
                document.getElementById('modal-course-title').textContent = courseTitle;
                document.getElementById('modal-course-coach').textContent = courseCoach;
                document.getElementById('modal-course-description').textContent = courseDescription;
                document.getElementById('modal-course-date').textContent = courseDate + ' at ' + courseTime.split('(')[0].trim();
                document.getElementById('modal-course-duration').textContent = courseTime.split('(')[1].replace(')', '') + 'utes';
                document.getElementById('modal-course-places').textContent = coursePlacesText;
                document.getElementById('modal-course-reservations').textContent = coursePlacesText.split('/')[0].trim() + ' reservations made';
                document.getElementById('modal-course-status').textContent = courseStatus;
                
                // Set the correct course ID for the "View Reservations" button
                document.getElementById('modal-view-reservations').setAttribute('data-course-id', courseId);
                
                openModal();
            });
        });
        
        // View reservations button in modal
        document.getElementById('modal-view-reservations').addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            window.location.href = `/admin/courses/${courseId}/reservations`;
        });
        
        // Close modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === courseModal) {
                closeModal();
            }
        });
        
        // View reservations buttons (in table)
        document.querySelectorAll('.view-reservations').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const courseId = this.getAttribute('data-course-id');
                window.location.href = `/admin/courses/${courseId}/reservations`;
            });
        });
        
        // Filter functionality
        const searchInput = document.getElementById('search-courses');
        const statusFilter = document.getElementById('status-filter');
        const coachFilter = document.getElementById('coach-filter');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const coursesTable = document.getElementById('courses-table-body');
        
        function filterCourses() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const coachValue = coachFilter.value;
            
            const rows = coursesTable.querySelectorAll('tr');
            
            rows.forEach(row => {
                const courseTitle = row.querySelector('td:nth-child(1) .text-gray-900').textContent.toLowerCase();
                const courseDescription = row.querySelector('td:nth-child(1) .text-gray-500').textContent.toLowerCase();
                const coachName = row.querySelector('td:nth-child(2) .text-gray-900').textContent.toLowerCase();
                const courseStatus = row.querySelector('td:nth-child(5) .inline-flex').textContent.toLowerCase();
                const coachId = row.querySelector('td:nth-child(6) .view-course').getAttribute('data-coach-id');
                
                const matchesSearch = courseTitle.includes(searchTerm) || 
                                      courseDescription.includes(searchTerm) ||
                                      coachName.includes(searchTerm);
                                      
                const matchesStatus = statusValue === '' || 
                                     (statusValue === 'upcoming' && (courseStatus.includes('upcoming') || courseStatus.includes('soon') || courseStatus.includes('today'))) ||
                                     (statusValue === 'completed' && courseStatus.includes('completed'));
                                     
                const matchesCoach = coachValue === '' || coachId === coachValue;
                
                if (matchesSearch && matchesStatus && matchesCoach) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterCourses);
        statusFilter.addEventListener('change', filterCourses);
        coachFilter.addEventListener('change', filterCourses);
        
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            coachFilter.value = '';
            
            const rows = coursesTable.querySelectorAll('tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        });
    });
</script>
@endsection 
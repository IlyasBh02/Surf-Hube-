@extends('layouts.dashboard')

@section('title', 'My Courses')
@section('dashboard-type', 'Coach')
@section('dashboard-icon', 'fa-solid fa-user-tie')
@section('user-role', 'Coach')
@section('user-name', Auth::user()->name ?? 'Coach')
@section('status-message', Auth::user()->coach_approved ? 'Approved Coach' : 'Pending Approval')
@section('dashboard-home-link', route('coach.dashboard'))

@section('sidebar-menu')
    @include('partials.coach-sidebar')
@endsection

@section('page-title', 'My Courses')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Courses</h1>
            <p class="text-gray-600">Manage your surf courses</p>
        </div>
        <a href="{{ route('coach.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fa-solid fa-plus mr-2"></i> Create New Course
        </a>
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

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('coach.courses.index', ['filter' => 'upcoming']) }}" class="@if(request('filter') == 'upcoming' || !request('filter')) border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Upcoming Courses
            </a>
            <a href="{{ route('coach.courses.index', ['filter' => 'past']) }}" class="@if(request('filter') == 'past') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Past Courses
            </a>
            <a href="{{ route('coach.courses.index', ['filter' => 'all']) }}" class="@if(request('filter') == 'all') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All Courses
            </a>
        </nav>
    </div>

    @if(count($courses) > 0)
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Attendance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($courses as $course)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($course->thumbnail)
                                        <div class="h-12 w-16 flex-shrink-0 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-water"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $course->date->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $course->date->format('H:i') }} - {{ $course->date->addMinutes($course->duration)->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $course->duration }} minutes</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $bookedCount = $course->reservations->where('status', 'confirmed')->count();
                                    $totalPlaces = $course->available_places;
                                    $percentBooked = $totalPlaces > 0 ? ($bookedCount / $totalPlaces * 100) : 0;
                                @endphp
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-900">{{ $bookedCount }} / {{ $totalPlaces }}</span>
                                    <div class="ml-2 w-20 bg-gray-200 rounded-full h-2.5">
                                        <div class="@if($percentBooked == 100) bg-green-600 @else bg-blue-600 @endif h-2.5 rounded-full" style="width: {{ $percentBooked }}%"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $course->available_places - $bookedCount }} spots left
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->date->isPast())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Completed
                                    </span>
                                @elseif($course->date->isFuture())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Upcoming
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        In Progress
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('coach.courses.show', $course->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('coach.course.reservations', $course->id) }}" class="text-purple-600 hover:text-purple-900" title="View Reservations">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                    
                                    @if($course->date->isFuture())
                                        <a href="{{ route('coach.courses.edit', $course->id) }}" class="text-green-600 hover:text-green-900" title="Edit Course">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('coach.courses.destroy', $course->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Delete Course"
                                                onclick="return confirm('Are you sure you want to delete this course? This will cancel all reservations.')">
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
        
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    @else
        <div class="text-center py-10 bg-white rounded-lg shadow">
            <i class="fa-solid fa-graduation-cap text-gray-300 text-5xl mb-3"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No courses found</h3>
            <p class="text-gray-500 mb-6">You haven't created any courses yet.</p>
            <a href="{{ route('coach.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fa-solid fa-plus mr-2"></i> Create Your First Course
            </a>
        </div>
    @endif
@endsection 
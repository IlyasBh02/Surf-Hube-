<div class="flex flex-col space-y-1">
    <a href="{{ route('coach.dashboard') }}" class="flex items-center px-4 py-2 text-base font-medium {{ request()->routeIs('coach.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-md transition-colors">
        <i class="fa-solid fa-gauge-high mr-3 w-5 text-center"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('coach.courses.index') ?? '#' }}" class="flex items-center px-4 py-2 text-base font-medium {{ request()->routeIs('coach.courses*') && !request()->routeIs('coach.courses.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-md transition-colors">
        <i class="fa-solid fa-graduation-cap mr-3 w-5 text-center"></i>
        <span>My Courses</span>
    </a>
    
    <a href="{{ route('coach.courses.create') ?? '#' }}" class="flex items-center px-4 py-2 text-base font-medium {{ request()->routeIs('coach.courses.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-md transition-colors">
        <i class="fa-solid fa-plus mr-3 w-5 text-center"></i>
        <span>Create Course</span>
    </a>
    
    <a href="{{ route('coach.reservations') ?? '#' }}" class="flex items-center justify-between px-4 py-2 text-base font-medium {{ request()->routeIs('coach.reservations*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-md transition-colors">
        <div class="flex items-center">
            <i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>
            <span>Reservations</span>
        </div>
        @if($pendingReservationsCount ?? 0 > 0)
            <span class="bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $pendingReservationsCount ?? 0 }}</span>
        @endif
    </a>
    
    <a href="{{ route('coach.profile') ?? '#' }}" class="flex items-center px-4 py-2 text-base font-medium {{ request()->routeIs('coach.profile*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-md transition-colors">
        <i class="fa-solid fa-user mr-3 w-5 text-center"></i>
        <span>My Profile</span>
    </a>
</div> 
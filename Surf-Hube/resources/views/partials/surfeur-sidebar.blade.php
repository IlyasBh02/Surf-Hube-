<div class="flex flex-col space-y-1">
    <a href="{{ route('surfer.dashboard') }}" class="sidebar-link {{ request()->routeIs('surfer.dashboard') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-gauge-high sidebar-icon"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('courses.browse') }}" class="sidebar-link {{ request()->routeIs('courses.browse') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-graduation-cap sidebar-icon"></i>
        <span>Browse Courses</span>
    </a>
    
    <a href="{{ route('surfer.reservations') }}" class="sidebar-link {{ request()->routeIs('surfer.reservations*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-calendar-check sidebar-icon"></i>
        <span>My Reservations</span>
    </a>
    
    <a href="{{ route('surfer.profile') }}" class="sidebar-link {{ request()->routeIs('surfer.profile*') ? 'sidebar-active' : '' }}">
        <i class="fa-solid fa-user sidebar-icon"></i>
        <span>My Profile</span>
    </a>
</div> 
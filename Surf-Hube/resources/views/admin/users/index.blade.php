@extends('layouts.dashboard')

@section('title', 'User Management')
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

@section('page-title', 'User Management')

@section('content')
    <!-- Action Bar -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="w-full md:w-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search-users" name="search" placeholder="Search users by name or email..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select id="role-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="coach">Coach</option>
                <option value="surfeur">Surfeur</option>
            </select>
            
            <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            
            <button id="reset-filters" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition-colors">
                <i class="fa-solid fa-filter-circle-xmark mr-1"></i> Reset
            </button>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="content-card mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full {{ $user->role === 'admin' ? 'bg-red-500' : ($user->role === 'coach' ? 'bg-blue-500' : 'bg-green-500') }} flex items-center justify-center text-white font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                       ($user->role === 'coach' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($user->role) }}
                                    @if($user->role === 'coach' && !$user->coach_approved)
                                        <span class="ml-1 text-yellow-600">(Pending)</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fa-solid fa-circle text-xs mr-1 {{ $user->status === 'active' ? 'text-green-500' : 'text-red-500' }}"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button type="button" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md view-user" 
                                        data-user-id="{{ $user->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    
                                    @if($user->role === 'coach' && !$user->coach_approved)
                                        <form action="{{ route('admin.approve-coach', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded-md">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.change-user-status', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-{{ $user->status === 'active' ? 'yellow' : 'green' }}-500 hover:bg-{{ $user->status === 'active' ? 'yellow' : 'green' }}-600 text-white px-2 py-1 rounded-md">
                                            <i class="fa-solid fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    
                                    @if($user->role !== 'admin' || (Auth::user()->id !== $user->id && Auth::user()->role === 'admin'))
                                        <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
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
        {{ $users->links() }}
    </div>
    
    <!-- User Details Modal -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 p-4">
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <div class="sm:flex sm:items-start">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                        id="modal-user-icon">
                        <i class="fa-solid fa-user text-white"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-user-name"></h3>
                        <div class="mt-2">
                            <div class="grid grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Email</label>
                                    <p id="modal-user-email" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Role</label>
                                    <p id="modal-user-role" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Status</label>
                                    <p id="modal-user-status" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Joined</label>
                                    <p id="modal-user-joined" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                                
                                <div id="modal-coach-approval-container" class="hidden">
                                    <label class="block text-xs font-medium text-gray-500">Coach Approval</label>
                                    <p id="modal-coach-approval" class="mt-1 text-sm text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" class="close-modal inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
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
        // View User Modal
        const userModal = document.getElementById('user-modal');
        const viewUserButtons = document.querySelectorAll('.view-user');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        
        // Functions to open and close modal
        function openModal() {
            userModal.classList.remove('hidden');
        }
        
        function closeModal() {
            userModal.classList.add('hidden');
        }
        
        // Open modal with user data
        viewUserButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userRow = this.closest('tr');
                
                // Get user data from the row
                const userName = userRow.querySelector('.text-gray-900').textContent;
                const userEmail = userRow.querySelector('.text-gray-500').textContent;
                const userRole = userRow.querySelector('.inline-flex').textContent.trim();
                const userStatus = userRow.querySelectorAll('.inline-flex')[1].textContent.trim();
                const userJoined = userRow.querySelector('td:nth-child(4)').textContent.trim();
                
                // Determine background color based on role
                let bgColorClass = 'bg-green-500';
                if (userRole.includes('Admin')) {
                    bgColorClass = 'bg-red-500';
                } else if (userRole.includes('Coach')) {
                    bgColorClass = 'bg-blue-500';
                }
                
                // Update modal content
                document.getElementById('modal-user-icon').className = `flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full ${bgColorClass} sm:mx-0 sm:h-10 sm:w-10`;
                document.getElementById('modal-user-name').textContent = userName;
                document.getElementById('modal-user-email').textContent = userEmail;
                document.getElementById('modal-user-role').textContent = userRole;
                document.getElementById('modal-user-status').textContent = userStatus;
                document.getElementById('modal-user-joined').textContent = userJoined;
                
                // Show coach approval status if applicable
                const coachApprovalContainer = document.getElementById('modal-coach-approval-container');
                const coachApproval = document.getElementById('modal-coach-approval');
                
                if (userRole.includes('Coach')) {
                    coachApprovalContainer.classList.remove('hidden');
                    if (userRole.includes('Pending')) {
                        coachApproval.textContent = 'Pending Approval';
                    } else {
                        coachApproval.textContent = 'Approved';
                    }
                } else {
                    coachApprovalContainer.classList.add('hidden');
                }
                
                openModal();
            });
        });
        
        // Close modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === userModal) {
                closeModal();
            }
        });
        
        // Filter functionality
        const searchInput = document.getElementById('search-users');
        const roleFilter = document.getElementById('role-filter');
        const statusFilter = document.getElementById('status-filter');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const usersTable = document.getElementById('users-table-body');
        
        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const roleValue = roleFilter.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            
            const rows = usersTable.querySelectorAll('tr');
            
            rows.forEach(row => {
                const userName = row.querySelector('.text-gray-900').textContent.toLowerCase();
                const userEmail = row.querySelector('.text-gray-500').textContent.toLowerCase();
                const userRole = row.querySelector('.inline-flex').textContent.toLowerCase();
                const userStatus = row.querySelectorAll('.inline-flex')[1].textContent.toLowerCase();
                
                const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                const matchesRole = roleValue === '' || userRole.includes(roleValue);
                const matchesStatus = statusValue === '' || userStatus.includes(statusValue);
                
                if (matchesSearch && matchesRole && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterUsers);
        roleFilter.addEventListener('change', filterUsers);
        statusFilter.addEventListener('change', filterUsers);
        
        resetFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            roleFilter.value = '';
            statusFilter.value = '';
            
            const rows = usersTable.querySelectorAll('tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        });
    });
</script>
@endsection 
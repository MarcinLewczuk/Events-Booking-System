@php
    $dashboardRoute = auth()->check()
        ? (auth()->user()->role === 'customer'
            ? route('customer.dashboard')
            : route('staff.dashboard'))
        : route('login');
    $userRole = auth()->user()->role ?? 'guest';
    $isStaff = in_array($userRole, ['admin', 'staff', 'approver']);
    $isAdmin = $userRole === 'admin';
@endphp

<nav x-data="{ adminOpen: false, userOpen: false }" class="bg-primary-500 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <x-application-logo class="block" />
            </a>
            <!-- Main Navigation - Only When Authenticated -->
            <div class="flex items-center space-x-1 lg:space-x-2 flex-1 justify-center">
                @auth
                    <a href="{{ $dashboardRoute }}" 
                       class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('*.dashboard') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        Dashboard
                    </a>
                    @if($userRole === 'customer')
                    <a href="{{ route('customer.inbox.index') }}" 
                    class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('inbox.*') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        Inbox
                    </a>
                    
                    <a href="{{ route('events') }}" 
                    class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('events') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        What's On
                    </a>

                    <a href="{{ route('calendar.index') }}" 
                    class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('calendar.*') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        Calendar
                    </a>

                    <a href="{{ route('customer.bookings') }}" 
                    class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('customer.bookings') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        My Bookings
                    </a>

                    @endif
                    @if($isStaff)
                        <a href="{{ route('admin.events.index') }}" 
                           class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('admin.events.*') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                            Events
                        </a>
                        @if($isAdmin)
                            <!-- Admin Dropdown -->
                            <div class="relative">
                                <button @click="adminOpen = !adminOpen" 
                                        class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium text-white hover:bg-primary-700 transition inline-flex items-center whitespace-nowrap">
                                    Admin
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="adminOpen" 
                                     @click.away="adminOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-56 rounded-none shadow-lg bg-white ring-0 border-t-4 border-secondary-500 z-50"
                                     style="display: none;">
                                    <div class="py-0">
                                        <a href="{{ route('admin.event-breakdown.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-light-400 hover:text-white border-b border-gray-200">
                                            <span class="mr-2">📅</span> Event Breakdown
                                        </a>
                                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-light-400 hover:text-white border-b border-gray-200">
                                            <span class="mr-2">📁</span> Categories
                                        </a>
                                        <a href="{{ route('admin.tags.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-light-400 hover:text-white border-b border-gray-200">
                                            <span class="mr-2">🏷️</span> Tags
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-light-400 hover:text-white border-b border-gray-200">
                                            <span class="mr-2">👥</span> Users
                                        </a>
                                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-light-400 hover:text-white">
                                            <span class="mr-2">📢</span> Announcements
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <a href="{{ route('events') }}" 
                    class="px-2 lg:px-3 py-2 text-m lg:text-m font-medium {{ request()->routeIs('events') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-700' }} transition whitespace-nowrap">
                        What's On
                    </a>
                @endauth
            </div>

            <!-- Right Side: User Menu or Login Button -->
            <div class="flex-shrink-0 flex items-center">
                @auth
                    <!-- Authenticated User Menu -->
                    <div class="relative">
                        <button @click="userOpen = !userOpen" 
                                class="flex items-center space-x-2 px-3 py-2 text-xs lg:text-sm font-medium text-white hover:bg-primary-700 transition">
                            <span class="hidden sm:inline">{{ Auth::user()->first_name ?? 'User' }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="userOpen" 
                             @click.away="userOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                    <div class="font-medium">{{ Auth::user()->first_name }} {{ Auth::user()->surname }}</div>
                                    <div class="truncate">{{ Auth::user()->email }}</div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Login Button -->
                    <a href="{{ route('login') }}" 
                       class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-white bg-primary-700 hover:bg-primary-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
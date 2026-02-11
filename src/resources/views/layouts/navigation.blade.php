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
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
             <a href="{{ route('home') }}">
            <div class="flex-shrink-0 flex items-center gap-2">
                    <x-application-logo class="block" />
                    <span class="text-lg xl:text-xl font-bold text-white sm:block">Fotheby's</span>
            </div>
            </a>
            <!-- Main Navigation - Only When Authenticated -->
            <div class="flex items-center space-x-1 lg:space-x-2 flex-1 justify-center">
                @auth
                    <a href="{{ $dashboardRoute }}" 
                       class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('*.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Dashboard
                    </a>
                    @if($userRole === 'customer')
                    <a href="{{ route('customer.inbox.index') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('inbox.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Inbox
                    </a>
                    
                    <a href="{{ route('events') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('events') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        What's On
                    </a>

                    <a href="{{ route('items.browse') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('items.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Explore
                    </a>

                    <a href="{{ route('locations.index') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('locations.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Locations
                    </a>

                    <a href="{{ route('about') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('about.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        About
                    </a>

                    <a href="{{ route('contact') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('contact.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Contact Us
                    </a>

                    <!-- Services Dropdown -->
                    <div class="relative" x-data="{ servicesOpen: false }">
                        <button @click="servicesOpen = !servicesOpen" 
                                class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium text-primary-100 hover:bg-primary-700 hover:text-white transition inline-flex items-center whitespace-nowrap">
                            Services
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="servicesOpen" 
                            @click.away="servicesOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('how-to-bid') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">How to Bid</a>
                                <a href="{{ route('sell-with-us') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sell With Us</a>
                                <a href="{{ route('valuation') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Valuations</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($isStaff)
                        <a href="{{ route('admin.items.index') }}" 
                           class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('admin.items.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                            Items
                        </a>

                        <a href="{{ route('admin.catalogues.index') }}" 
                           class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('admin.catalogues.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                            Catalogues
                        </a>

                        <a href="{{ route('admin.auctions.index') }}" 
                           class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('admin.auctions.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                            Auctions
                        </a>

                        <a href="{{ route('staff.customers.index') }}" 
                           class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('staff.customers.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                            Customers
                        </a>
                        @if($isAdmin)
                            <!-- Admin Dropdown -->
                            <div class="relative">
                                <button @click="adminOpen = !adminOpen" 
                                        class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium text-primary-100 hover:bg-primary-700 hover:text-white transition inline-flex items-center whitespace-nowrap">
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
                                     class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">🔈</span> Announcement
                                        </a>
                                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">📁</span> Categories
                                        </a>
                                        <a href="{{ route('admin.tags.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">🏷️</span> Tags
                                        </a>
                                        <a href="{{ route('admin.bands.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">💰</span> Price Bands
                                        </a>
                                        <a href="{{ route('admin.locations.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">📍</span> Locations
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">👥</span> Users
                                        </a>
                                        <a href="{{ route('admin.bids.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">💵</span> Bids
                                        </a>
                                        <a href="{{ route('admin.settlements.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <span class="mr-2">💳</span> Settlements
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                <a href="{{ route('auctions.browse') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('auctions.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Auctions
                    </a>

                    <a href="{{ route('items.browse') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('items.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Items
                    </a>

                    <a href="{{ route('locations.index') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('locations.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Locations
                    </a>

                    <a href="{{ route('about') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('about.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        About
                    </a>

                    <a href="{{ route('contact') }}" 
                    class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium {{ request()->routeIs('contact.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition whitespace-nowrap">
                        Contact Us
                    </a>

                    <!-- Services Dropdown -->
                    <div class="relative" x-data="{ servicesOpen: false }">
                        <button @click="servicesOpen = !servicesOpen" 
                                class="text-white px-2 lg:px-3 py-2 rounded-md text-m lg:text-m font-medium text-primary-100 hover:bg-primary-700 hover:text-white transition inline-flex items-center whitespace-nowrap">
                            Services
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="servicesOpen" 
                            @click.away="servicesOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('how-to-bid') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">How to Bid</a>
                                <a href="{{ route('sell-with-us') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sell With Us</a>
                                <a href="{{ route('valuation') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Valuations</a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Right Side: User Menu or Login Button -->
            <div class="flex-shrink-0 flex items-center">
                @auth
                    <!-- Authenticated User Menu -->
                    <div class="relative">
                        <button @click="userOpen = !userOpen" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-md text-xs lg:text-sm font-medium text-white hover:bg-primary-700 transition">
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
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
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
                       class="flex items-center space-x-2 px-4 py-2 rounded-md text-sm font-medium text-white bg-primary-700 hover:bg-primary-600 transition">
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
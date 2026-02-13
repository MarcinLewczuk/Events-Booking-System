
<x-layouts.app>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-900 to-primary-800 rounded-lg shadow-xl p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Staff Dashboard</h2>
                        <p class="text-primary-100 mt-1">Welcome back, <span class="font-semibold">{{ auth()->user()->first_name }} {{ auth()->user()->surname }}</span></p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        @switch($role)
                            @case('admin')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 text-white font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Administrator
                                </span>
                                @break
                            @case('approver')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 text-white font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Approver
                                </span>
                                @break
                            @case('staff')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 text-white font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                    Staff Member
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Upcoming Events</h3>
                
                @php
                    $upcomingAuctions = \App\Models\Event::with(['category'])
                        ->where('status', 'active')
                        ->where('start_datetime', '>=', now())
                        ->orderBy('start_datetime', 'asc')
                        ->take(6)
                        ->get()
                        ->groupBy(function($event) {
                            return $event->start_datetime ? $event->start_datetime->format('Y-m-d') : 'no-date';
                        });
                @endphp

                @if($upcomingAuctions->isEmpty() || ($upcomingAuctions->count() === 1 && $upcomingAuctions->has('no-date')))
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No Upcoming Events</h4>
                        <p class="text-gray-600">Create an event to see it here.</p>
                    </div>
                @else
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($upcomingAuctions as $date => $auctions)
                            @if($date !== 'no-date')
                                @foreach($auctions as $auction)
                                    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all overflow-hidden border-t-4 border-primary-600">
                                        <!-- Date Header -->
                                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-3xl font-bold">{{ $auction->start_datetime->format('d') }}</p>
                                                    <p class="text-sm uppercase tracking-wide">{{ $auction->start_datetime->format('M Y') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm">{{ $auction->start_datetime->format('l') }}</p>
                                                    <p class="text-lg font-semibold">{{ $auction->start_datetime->format('g:i A') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Event Details -->
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">{{ $auction->title }}</h4>
                                            
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <!-- Category -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.5a2 2 0 00-1 .266M7 21V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4z"/>
                                                    </svg>
                                                    <span class="truncate">{{ $auction->category?->name ?? 'No category' }}</span>
                                                </div>

                                                <!-- Days Until -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $auction->start_datetime->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            <!-- View Button -->
                                            <a href="{{ route('admin.events.show', $auction) }}" 
                                            class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition">
                                                View Details
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>

                    <!-- View All Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                            View All Events
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-3">
                <!-- Events -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Events</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\Event::count() }}</p>
                        </div>
                        <div class="bg-primary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Categories</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\Category::count() }}</p>
                        </div>
                        <div class="bg-secondary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9-4 9 4m0 0v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Users</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-primary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                @if($role === 'admin')
                <!-- Visitors -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Visitors</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\EventBooking::count() }}</p>
                        </div>
                        <div class="bg-secondary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Events -->
                    <a href="{{ route('admin.events.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition flex items-center justify-center">
                                <span class="text-2xl">📅</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Events</h4>
                        <p class="text-sm text-gray-600 mt-1">Manage events</p>
                    </a>

                    @if($role === 'admin')
                        <!-- Categories -->
                        <a href="{{ route('admin.categories.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-secondary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition flex items-center justify-center">
                                    <span class="text-2xl">📁</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition">Categories</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage categories</p>
                        </a>

                        <!-- Tags -->
                        <a href="{{ route('admin.tags.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition flex items-center justify-center">
                                    <span class="text-2xl">🏷️</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Tags</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage tags</p>
                        </a>

                        <!-- Users -->
                        <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-secondary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition flex items-center justify-center">
                                    <span class="text-2xl">👥</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition">Users</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage users</p>
                        </a>

                        <!-- Announcements -->
                        <a href="{{ route('admin.announcements.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition flex items-center justify-center">
                                    <span class="text-2xl">📢</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Announcements</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage announcements</p>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
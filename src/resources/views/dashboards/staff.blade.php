
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

            @if($role == 'approver' or $role == 'admin')
                <!-- Pending Approvals -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Pending Approvals</h3>
                        <span class="text-sm text-gray-600">Items requiring your review</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $pendingItems = \App\Models\Item::where('status', 'awaiting_approval')->count();
                            $pendingCatalogues = \App\Models\Catalogue::where('status', 'awaiting_approval')->count();
                            $pendingAuctions = \App\Models\Auction::where('approval_status', 'awaiting_approval')->count();
                        @endphp

                        <!-- Items Awaiting Approval -->
                        <a href="{{ route('admin.approvals.items') }}" class="group bg-gradient-to-br from-primary-50 to-primary-50 rounded-lg shadow-lg hover:shadow-xl p-6 border-l-4 border-primary-500 transition-all transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-primary-600">{{ $pendingItems }}</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Items</h4>
                            <p class="text-sm text-gray-600">Awaiting approval</p>
                            <div class="mt-3 flex items-center text-sm text-primary-600 font-semibold">
                                Review items
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <!-- Catalogues Awaiting Approval -->
                        <a href="{{ route('admin.approvals.catalogues', ['status' => 'awaiting_approval']) }}" class="group bg-gradient-to-br from-secondary-50 to-amber-50 rounded-lg shadow-lg hover:shadow-xl p-6 border-l-4 border-secondary-500 transition-all transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-secondary-100 rounded-lg p-3 group-hover:bg-secondary-200 transition">
                                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-secondary-600">{{ $pendingCatalogues }}</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Catalogues</h4>
                            <p class="text-sm text-gray-600">Awaiting approval</p>
                            <div class="mt-3 flex items-center text-sm text-secondary-600 font-semibold">
                                Review catalogues
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <!-- Auctions Awaiting Approval -->
                        <a href="{{ route('admin.approvals.auctions') }}" class="group bg-gradient-to-br from-primary-50 to-primary-50 rounded-lg shadow-lg hover:shadow-xl p-6 border-l-4 border-primary-500 transition-all transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="text-3xl font-bold text-primary-600">{{ $pendingAuctions }}</span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Auctions</h4>
                            <p class="text-sm text-gray-600">Awaiting approval</p>
                            <div class="mt-3 flex items-center text-sm text-primary-600 font-semibold">
                                Review auctions
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
            <!-- Upcoming Auctions -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Upcoming Auctions</h3>
                
                @php
                    $upcomingAuctions = \App\Models\Auction::with(['catalogue', 'location'])
                        ->where('status', 'scheduled')
                        ->where('auction_date', '>=', now())
                        ->orderBy('auction_date', 'asc')
                        ->take(6)
                        ->get()
                        ->groupBy(function($auction) {
                            return $auction->auction_date ? $auction->auction_date->format('Y-m-d') : 'no-date';
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
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No Upcoming Auctions</h4>
                        <p class="text-gray-600">Schedule an auction to see it here.</p>
                    </div>
                @else
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($upcomingAuctions as $date => $auctions)
                            @if($date !== 'no-date')
                                @foreach($auctions as $auction)
                                    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all overflow-hidden border-t-4 border-red-600">
                                        <!-- Date Header -->
                                        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-3xl font-bold">{{ $auction->auction_date->format('d') }}</p>
                                                    <p class="text-sm uppercase tracking-wide">{{ $auction->auction_date->format('M Y') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm">{{ $auction->auction_date->format('l') }}</p>
                                                    @if($auction->start_time)
                                                        <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($auction->start_time)->format('g:i A') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Auction Details -->
                                        <div class="p-4">
                                            <h4 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">{{ $auction->title }}</h4>
                                            
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <!-- Catalogue -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    <span class="truncate">{{ $auction->catalogue?->name ?? 'No catalogue' }}</span>
                                                </div>

                                                <!-- Location -->
                                                @if($auction->location)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="truncate">{{ $auction->location->name }}</span>
                                                    </div>
                                                @endif

                                                <!-- Days Until -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $auction->auction_date->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            <!-- View Button -->
                                            <a href="{{ route('admin.auctions.edit', $auction) }}" 
                                            class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
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
                        <a href="{{ route('admin.auctions.index') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                            View All Auctions
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-3">
                <!-- Items -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Items</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\Item::count() }}</p>
                        </div>
                        <div class="bg-primary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Catalogues -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Catalogues</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\Catalogue::count() }}</p>
                        </div>
                        <div class="bg-secondary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Auctions -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Auctions</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\Auction::count() }}</p>
                        </div>
                        <div class="bg-primary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                @if($role === 'admin')
                <!-- Customers -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary-600 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Customers</p>
                            <p class="text-3xl font-bold text-primary-900 mt-2">{{ \App\Models\User::where('role', 'customer')->count() }}</p>
                        </div>
                        <div class="bg-secondary-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
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
                    
                    <!-- Items -->
                    <a href="{{ route('admin.items.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Items</h4>
                        <p class="text-sm text-gray-600 mt-1">Manage auction items</p>
                    </a>

                    <!-- Catalogues -->
                    <a href="{{ route('admin.catalogues.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-secondary-600 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition">
                                <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-secondary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-secondary-600 transition">Catalogues</h4>
                        <p class="text-sm text-gray-600 mt-1">Create and manage catalogues</p>
                    </a>

                    <!-- Auctions -->
                    <a href="{{ route('admin.auctions.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Auctions</h4>
                        <p class="text-sm text-gray-600 mt-1">Schedule auctions</p>
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
                            <p class="text-sm text-gray-600 mt-1">Manage item tags</p>
                        </a>

                        <!-- Bands -->
                        <a href="{{ route('admin.bands.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-secondary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition flex items-center justify-center">
                                    <span class="text-2xl">💰</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition">Price Bands</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage price ranges</p>
                        </a>

                        <!-- Locations -->
                        <a href="{{ route('admin.locations.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition flex items-center justify-center">
                                    <span class="text-2xl">📍</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Locations</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage venues</p>
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

                        <!-- Bids -->
                        <a href="{{ route('admin.bids.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-primary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary-100 rounded-lg p-3 group-hover:bg-primary-200 transition flex items-center justify-center">
                                    <span class="text-2xl">💵</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition">Bids</h4>
                            <p class="text-sm text-gray-600 mt-1">View bid history</p>
                        </a>

                        <!-- Settlements -->
                        <a href="{{ route('admin.settlements.index') }}" class="group bg-white rounded-lg shadow-lg hover:shadow-xl p-6 transition-all border-l-4 border-secondary-600 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition flex items-center justify-center">
                                    <span class="text-2xl">💳</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition">Settlements</h4>
                            <p class="text-sm text-gray-600 mt-1">Manage payments</p>
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

<x-app-layout>
    <div class="py-8 bg-gradient-to-br from-primary-50 to-secondary-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-primary-600">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-primary-900">Event Categories</h1>
                        <p class="text-gray-600 mt-1">Create and manage event categories for organizing your events</p>
                    </div>
                    <x-buttons.link :href="route('admin.categories.create')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create New Category
                    </x-buttons.link>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-red-800 font-medium">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <form method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search categories by name..." 
                           class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <x-buttons.primary type="submit">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </x-buttons.primary>
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg shadow-md transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-primary-900 to-primary-800 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Parent Category</th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">Subcategories</th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">Events</th>
                                <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <tr class="hover:bg-primary-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($category->parent_id)
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            @endif
                                            <span class="text-sm font-bold text-gray-900">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($category->parent)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                {{ $category->parent->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Top Level</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $category->children_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            {{ $category->events_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                            class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <p class="mt-4 text-lg font-semibold text-gray-900">No categories found</p>
                                        <p class="mt-2 text-sm text-gray-600">Create your first category to organize events</p>
                                        <div class="mt-6">
                                            <x-buttons.link :href="route('admin.categories.create')">
                                                Create New Category
                                            </x-buttons.link>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                    <div class="bg-white px-6 py-4 border-t border-gray-200">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
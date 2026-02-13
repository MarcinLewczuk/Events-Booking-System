<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow-lg p-6 mb-6 border-l-4 border-primary-900">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage system users and access control</p>
                    </div>
                    <x-buttons.primary href="{{ route('admin.users.create') }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create User
                    </x-buttons.primary>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-800 font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Search & Filter Form -->
            <div class="mb-6 bg-white shadow-sm border border-gray-200 p-4">
                <form method="GET" class="flex flex-col gap-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Search by name or email..." 
                                class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                        </div>
                        <div class="sm:w-48">
                            <select name="role" class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <x-buttons.primary type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </x-buttons.primary>
                        @if(request()->hasAny(['search', 'role']))
                            <x-buttons.secondary href="{{ route('admin.users.index') }}">
                                Clear
                            </x-buttons.secondary>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#15404e] text-white">
                                <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-primary-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br {{ $user->role === 'admin' ? 'from-red-500 to-red-600' : ($user->role === 'staff' ? 'from-blue-500 to-blue-600' : 'from-gray-500 to-gray-600') }} flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">
                                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->surname, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">
                                                    {{ $user->title }} {{ $user->first_name }} {{ $user->surname }}
                                                </div>
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-purple-600 font-medium">(You)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold 
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?');" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-500">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No users found</p>
                                            <p class="text-gray-400 text-sm mt-1">Try adjusting your search criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


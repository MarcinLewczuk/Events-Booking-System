
@props(['route', 'icon', 'title', 'description', 'color' => 'primary'])

@php
    $colors = [
        'primary' => 'from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800',
        'secondary' => 'from-secondary-600 to-secondary-700 hover:from-secondary-700 hover:to-secondary-800',
        'green' => 'from-green-600 to-green-700 hover:from-green-700 hover:to-green-800',
        'purple' => 'from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800',
        'yellow' => 'from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800',
        'indigo' => 'from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800',
        'pink' => 'from-pink-600 to-pink-700 hover:from-pink-700 hover:to-pink-800',
        'gray' => 'from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800',
    ];
    $gradient = $colors[$color] ?? $colors['primary'];
@endphp

<a href="{{ $route }}" class="group block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border-t-4 border-{{ $color }}-600">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-gradient-to-br {{ $gradient }} rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-{{ $color }}-600 group-hover:translate-x-2 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 group-hover:text-{{ $color }}-700 transition-colors mb-2">{{ $title }}</h3>
        <p class="text-gray-600 text-sm">{{ $description }}</p>
    </div>
</a>

@props(['href' => '#']) <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-primary-900 hover:bg-primary-800 text-white font-semibold shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2']) }}> {{ $slot }}
</a>
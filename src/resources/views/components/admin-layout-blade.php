<?php
@props(['title' => 'Admin Panel'])
?>
<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-t-4 border-primary-900">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
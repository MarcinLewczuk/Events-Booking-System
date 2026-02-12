<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Delapré Abbey') }}</title>
        <link rel="icon" type="image/png" href="https://delapreabbey.org/wp-content/uploads/2024/09/cropped-favicon-1-150x150.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Print Styles -->
        <style>
            @media print {
                @page {
                    margin: 1cm;
                }
                
                body {
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    background: white !important;
                }
                
                /* Hide navigation, footer, and other page elements */
                nav, 
                footer, 
                .no-print,
                header {
                    display: none !important;
                }
                
                /* Hide everything except the receipt */
                body > div > * {
                    display: none !important;
                }
                
                body > div > main {
                    display: block !important;
                }
                
                main > * {
                    display: none !important;
                }
                
                main #receipt {
                    display: block !important;
                }
                
                /* Remove background colors and shadows */
                #receipt {
                    box-shadow: none !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                /* Ensure the receipt content is visible */
                #receipt * {
                    display: revert !important;
                }
                
                .print\:hidden {
                    display: none !important;
                }
                
                .print\:bg-green-600 {
                    background-color: #16a34a !important;
                }
                
                .print\:text-3xl {
                    font-size: 1.875rem !important;
                    line-height: 2.25rem !important;
                }
                
                .print\:text-2xl {
                    font-size: 1.5rem !important;
                    line-height: 2rem !important;
                }
                
                .print\:text-lg {
                    font-size: 1.125rem !important;
                    line-height: 1.75rem !important;
                }
                
                .print\:text-base {
                    font-size: 1rem !important;
                    line-height: 1.5rem !important;
                }
                
                .print\:w-16 {
                    width: 4rem !important;
                }
                
                .print\:h-16 {
                    height: 4rem !important;
                }
                
                .print\:w-5 {
                    width: 1.25rem !important;
                }
                
                .print\:h-5 {
                    height: 1.25rem !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 py-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-footer />
        </div>
    </body>
</html>
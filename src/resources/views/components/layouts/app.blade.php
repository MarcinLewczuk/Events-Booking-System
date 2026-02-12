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
                    size: auto;
                }
                
                * {
                    overflow: visible !important;
                }
                
                html, body {
                    height: auto !important;
                    overflow: visible !important;
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                }
                
                body {
                    background: white !important;
                }
                
                /* Hide everything by default */
                body * {
                    visibility: hidden;
                }
                
                /* Show only the receipt and its contents */
                #receipt,
                #receipt * {
                    visibility: visible;
                }
                
                /* Position receipt at top of page */
                #receipt {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    max-width: 100%;
                    box-shadow: none !important;
                    border-radius: 0 !important;
                    margin: 0 !important;
                    transform: scale(0.5);
                    transform-origin: top left;
                }
                
                /* Explicitly hide these elements */
                nav, 
                footer, 
                .no-print,
                header {
                    display: none !important;
                    visibility: hidden !important;
                }
                
                .print\:hidden {
                    display: none !important;
                    visibility: hidden !important;
                }
                
                .print\:bg-green-600 {
                    background-color: #16a34a !important;
                }
                
                /* Scale down text sizes for print */
                #receipt h1 {
                    font-size: 1.5rem !important;
                    line-height: 2rem !important;
                }
                
                #receipt h2 {
                    font-size: 1.25rem !important;
                    line-height: 1.75rem !important;
                }
                
                #receipt h3 {
                    font-size: 1rem !important;
                    line-height: 1.5rem !important;
                }
                
                #receipt p,
                #receipt span,
                #receipt div {
                    font-size: 0.75rem !important;
                    line-height: 1.25rem !important;
                }
                
                #receipt .text-3xl,
                #receipt .text-4xl,
                #receipt .text-5xl {
                    font-size: 1.5rem !important;
                }
                
                #receipt .text-2xl {
                    font-size: 1.25rem !important;
                }
                
                #receipt .text-xl {
                    font-size: 1rem !important;
                }
                
                #receipt .text-lg {
                    font-size: 0.875rem !important;
                }
                
                #receipt svg {
                    width: 1rem !important;
                    height: 1rem !important;
                }
                
                #receipt .px-8 { padding-left: 1rem !important; padding-right: 1rem !important; }
                #receipt .py-12 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
                #receipt .px-6 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
                #receipt .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
                #receipt .py-6 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
                #receipt .py-4 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
                #receipt .py-3 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
                #receipt .py-2 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
                #receipt .p-6 { padding: 0.75rem !important; }
                #receipt .p-4 { padding: 0.5rem !important; }
                #receipt .mb-8 { margin-bottom: 1rem !important; }
                #receipt .mb-6 { margin-bottom: 0.75rem !important; }
                #receipt .mb-4 { margin-bottom: 0.5rem !important; }
                #receipt .mb-3 { margin-bottom: 0.375rem !important; }
                #receipt .mb-2 { margin-bottom: 0.25rem !important; }
                #receipt .space-y-6 > * + * { margin-top: 0.75rem !important; }
                #receipt .space-y-4 > * + * { margin-top: 0.5rem !important; }
                #receipt .space-y-3 > * + * { margin-top: 0.375rem !important; }
                #receipt .space-y-2 > * + * { margin-top: 0.25rem !important; }
                
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
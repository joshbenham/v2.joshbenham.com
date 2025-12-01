<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 - Page Not Found | {{ config('app.name') }}</title>
    <meta name="description" content="The page you're looking for could not be found.">
    <meta name="robots" content="noindex, nofollow">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 to-indigo-100">
    {{-- Header --}}
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-4xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                    {{ config('app.name') }}
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-orange-600 px-8 py-16 text-white text-center">
                    <div class="text-8xl font-bold mb-4">404</div>
                    <h1 class="text-4xl font-bold mb-4">Page Not Found</h1>
                    <p class="text-xl opacity-90">Oops! The page you're looking for doesn't exist.</p>
                </div>
                
                <div class="px-8 py-12 text-center">
                    <p class="text-lg text-gray-600 mb-8">
                        The page you requested could not be found. It might have been moved, deleted, or never existed.
                    </p>
                    
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Go Home
                        </a>
                        
                        <button onclick="window.history.back()" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>
                </div>
            </div>

            {{-- Navigation to available pages --}}
            @php
                $pages = \App\Models\Page::query()->published()->ordered()->get()->filter(fn($p) => !$p->is_homepage);
            @endphp
            
            @if($pages->isNotEmpty())
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Or explore these pages</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($pages as $page)
                            <a href="{{ route('page.show', $page->slug) }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $page->title }}</h4>
                                <p class="text-gray-600">Explore this page →</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

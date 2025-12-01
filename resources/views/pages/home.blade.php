<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Primary Meta Tags --}}
    <title>{{ $page->seo['meta_title'] ?? $page->title }}</title>
    @if(isset($page->seo['meta_description']))
        <meta name="description" content="{{ $page->seo['meta_description'] }}">
    @endif
    @if(isset($page->seo['meta_keywords']))
        <meta name="keywords" content="{{ $page->seo['meta_keywords'] }}">
    @endif
    @if(isset($page->seo['robots']))
        <meta name="robots" content="{{ $page->seo['robots'] }}">
    @endif
    @if(isset($page->seo['canonical']))
        <link rel="canonical" href="{{ $page->seo['canonical'] }}">
    @else
        <link rel="canonical" href="{{ url('/') }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="{{ $page->seo['og_type'] ?? 'website' }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $page->seo['og_title'] ?? $page->seo['meta_title'] ?? $page->title }}">
    @if(isset($page->seo['og_description']))
        <meta property="og:description" content="{{ $page->seo['og_description'] }}">
    @endif
    @if(isset($page->seo['og_image']))
        <meta property="og:image" content="{{ $page->seo['og_image'] }}">
    @endif

    {{-- Twitter --}}
    <meta property="twitter:card" content="{{ $page->seo['twitter_card'] ?? 'summary_large_image' }}">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ $page->seo['twitter_title'] ?? $page->seo['meta_title'] ?? $page->title }}">
    @if(isset($page->seo['twitter_description']))
        <meta property="twitter:description" content="{{ $page->seo['twitter_description'] }}">
    @endif
    @if(isset($page->seo['twitter_image']))
        <meta property="twitter:image" content="{{ $page->seo['twitter_image'] }}">
    @endif

    {{-- Schema.org JSON-LD --}}
    @if(isset($page->seo['schema_type']) && $page->seo['schema_type'])
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "{{ $page->seo['schema_type'] }}",
            "name": "{{ $page->title }}",
            "url": "{{ url('/') }}"
            @if(isset($page->seo['schema_data']) && is_array($page->seo['schema_data']))
                @foreach($page->seo['schema_data'] as $key => $value)
                    ,"{{ $key }}": "{{ $value }}"
                @endforeach
            @endif
        }
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 to-indigo-100">
    {{-- Custom Homepage Header --}}
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900">{{ config('app.name') }}</h1>
                <p class="mt-2 text-lg text-gray-600">Custom Homepage View</p>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-16 text-white text-center">
                    <h2 class="text-5xl font-bold mb-4">{{ $page->title }}</h2>
                    <p class="text-xl opacity-90">Welcome to our homepage</p>
                </div>
                
                <div class="px-8 py-12">
                    <article class="prose prose-lg max-w-none">
                        {!! $page->content !!}
                    </article>
                </div>
            </div>

            {{-- Navigation to other pages --}}
            @php
                $pages = \App\Models\Page::query()->published()->ordered()->get()->filter(fn($p) => !$p->is_homepage);
            @endphp
            
            @if($pages->isNotEmpty())
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Explore More</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($pages as $navPage)
                            <a href="{{ route('page.show', $navPage->slug) }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $navPage->title }}</h4>
                                <p class="text-gray-600">Learn more →</p>
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

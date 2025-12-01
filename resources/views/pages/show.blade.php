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
        <link rel="canonical" href="{{ $isHomepage ? url('/') : route('page.show', $page->slug) }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="{{ $page->seo['og_type'] ?? 'website' }}">
    <meta property="og:url" content="{{ $isHomepage ? url('/') : route('page.show', $page->slug) }}">
    <meta property="og:title" content="{{ $page->seo['og_title'] ?? $page->seo['meta_title'] ?? $page->title }}">
    @if(isset($page->seo['og_description']))
        <meta property="og:description" content="{{ $page->seo['og_description'] }}">
    @endif
    @if(isset($page->seo['og_image']))
        <meta property="og:image" content="{{ $page->seo['og_image'] }}">
    @endif

    {{-- Twitter --}}
    <meta property="twitter:card" content="{{ $page->seo['twitter_card'] ?? 'summary_large_image' }}">
    <meta property="twitter:url" content="{{ $isHomepage ? url('/') : route('page.show', $page->slug) }}">
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
            "url": "{{ $isHomepage ? url('/') : route('page.show', $page->slug) }}"
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
<body class="antialiased">
    {{-- Navigation --}}
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-xl font-bold">{{ config('app.name') }}</span>
                    </a>
                    
                    {{-- Main Navigation --}}
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @foreach(\App\Models\Page::query()->published()->ordered()->get() as $navPage)
                            @if(!$navPage->is_homepage)
                                <a 
                                    href="{{ route('page.show', $navPage->slug) }}" 
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ $page->id === $navPage->id ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}"
                                >
                                    {{ $navPage->title }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="prose prose-lg max-w-none">
                <h1>{{ $page->title }}</h1>
                
                <div class="content">
                    {!! $page->content !!}
                </div>
            </article>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-50 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>

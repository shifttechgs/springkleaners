@extends('layouts.app')
@section('title', 'Cleaning Tips & Guides Cape Town | SpringKleaners Blog')
@section('description', 'Practical cleaning guides for Cape Town homes and businesses — end-of-tenancy checklists, deep cleaning pricing, post-construction clean-ups and more from SpringKleaners.')
@if ($posts->currentPage() > 1)
@section('robots', 'noindex, follow')
@endif
@section('content')
    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-20 lg:pt-44 lg:pb-24 relative overflow-hidden">
        <div class="section-wrap relative z-10 text-center max-w-3xl mx-auto">
            <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
            <p class="text-white/70 text-[11px] font-semibold uppercase tracking-[0.18em] mb-4">The SpringKleaners Blog</p>
            <h1 class="text-4xl sm:text-5xl lg:text-[62px] font-extrabold text-white leading-[1.1] tracking-tight mb-5 wow fadeInUp" data-wow-duration="0.7s">
                Cleaning tips, guides<br>&amp; local know-how.
            </h1>
            <p class="text-white/50 text-[15px] sm:text-[16px] leading-relaxed max-w-xl mx-auto wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                Practical advice on deep cleaning, end-of-tenancy checklists and post-construction clean-ups — written for homes and businesses across Cape Town's Northern Suburbs.
            </p>
        </div>
    </section>

    <section class="bg-light section-py !pt-14 lg:!pt-16">
        <div class="section-wrap">

            <div class="flex flex-wrap items-center justify-center gap-2.5 mb-14 wow fadeInUp" data-wow-duration="0.6s">
                <a href="{{ route('blog.index') }}"
                   class="px-5 py-2.5 rounded-full text-[13px] font-bold tracking-tight transition-colors {{ ! $activeCategory ? 'bg-[#081d3a] text-white' : 'bg-white text-navy border border-black/10 hover:border-navy/30' }}">
                    All Posts
                </a>
                @foreach ($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category]) }}"
                   class="px-5 py-2.5 rounded-full text-[13px] font-bold tracking-tight transition-colors {{ $activeCategory === $category ? 'bg-[#081d3a] text-white' : 'bg-white text-navy border border-black/10 hover:border-navy/30' }}">
                    {{ $category }}
                </a>
                @endforeach
            </div>

            @if ($posts->isEmpty())
            <p class="text-center text-muted text-[15px]">No posts in this category yet.</p>
            @else

            @if ($posts->currentPage() === 1)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <x-blog-card :post="$posts[0]" :featured="true" />
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts->currentPage() === 1 ? $posts->skip(1) : $posts as $post)
                <div class="wow fadeInUp" data-wow-duration="0.6s">
                    <x-blog-card :post="$post" />
                </div>
                @endforeach
            </div>

            @if ($posts->hasPages())
            <nav class="flex items-center justify-center gap-2 mt-14" aria-label="Blog pagination">
                @if ($posts->onFirstPage())
                <span class="w-10 h-10 rounded-full flex items-center justify-center text-[#c3c9d4] cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
                @else
                <a href="{{ $posts->previousPageUrl() }}" class="w-10 h-10 rounded-full flex items-center justify-center text-navy bg-white border border-black/10 hover:border-navy/30 transition-colors" aria-label="Previous page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endif

                @for ($i = 1; $i <= $posts->lastPage(); $i++)
                <a href="{{ $posts->url($i) }}"
                   class="w-10 h-10 rounded-full flex items-center justify-center text-[13px] font-bold transition-colors {{ $i === $posts->currentPage() ? 'bg-[#081d3a] text-white' : 'bg-white text-navy border border-black/10 hover:border-navy/30' }}"
                   @if ($i === $posts->currentPage()) aria-current="page" @endif>
                    {{ $i }}
                </a>
                @endfor

                @if ($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="w-10 h-10 rounded-full flex items-center justify-center text-navy bg-white border border-black/10 hover:border-navy/30 transition-colors" aria-label="Next page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else
                <span class="w-10 h-10 rounded-full flex items-center justify-center text-[#c3c9d4] cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
                @endif
            </nav>
            @endif

            @endif

        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection

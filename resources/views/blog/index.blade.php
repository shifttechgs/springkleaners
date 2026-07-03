@extends('layouts.app')
@section('title', 'Cleaning Tips & Guides Cape Town | SpringKleaners Blog')
@section('description', 'Practical cleaning guides for Cape Town homes and businesses — end-of-tenancy checklists, deep cleaning pricing, post-construction clean-ups and more from SpringKleaners.')
@section('content')
    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-20 lg:pt-44 lg:pb-24 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
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

    <section class="bg-light section-py !pt-14 lg:!pt-16" x-data="{ active: 'All' }">
        <div class="section-wrap">

            <div class="flex flex-wrap items-center justify-center gap-2.5 mb-14 wow fadeInUp" data-wow-duration="0.6s">
                <button @click="active = 'All'"
                        :class="active === 'All' ? 'bg-[#081d3a] text-white' : 'bg-white text-navy border border-black/10 hover:border-navy/30'"
                        class="px-5 py-2.5 rounded-full text-[13px] font-bold tracking-tight transition-colors">
                    All Posts
                </button>
                @foreach ($categories as $category)
                <button @click="active = '{{ $category }}'"
                        :class="active === '{{ $category }}' ? 'bg-[#081d3a] text-white' : 'bg-white text-navy border border-black/10 hover:border-navy/30'"
                        class="px-5 py-2.5 rounded-full text-[13px] font-bold tracking-tight transition-colors">
                    {{ $category }}
                </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @if($posts->isNotEmpty())
                <div x-show="active === 'All' || active === '{{ $posts[0]['category'] }}'" x-cloak class="lg:col-span-2">
                    <x-blog-card :post="$posts[0]" :featured="true" />
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts->skip(1) as $post)
                <div x-show="active === 'All' || active === '{{ $post['category'] }}'" x-cloak class="wow fadeInUp" data-wow-duration="0.6s">
                    <x-blog-card :post="$post" />
                </div>
                @endforeach
            </div>

        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection

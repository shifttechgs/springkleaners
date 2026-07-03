@php
    $latestPosts = collect(config('blog.posts'))->sortByDesc('date')->take(3)->values();
@endphp

@if($latestPosts->isNotEmpty())
<section class="bg-white section-py" id="blog">
    <div class="section-wrap">

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-12 wow fadeInUp" data-wow-duration="0.7s">
            <div class="max-w-xl">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <p class="text-navy/60 text-[11px] font-semibold uppercase tracking-[0.18em] mb-4">From The Blog</p>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-navy leading-[1.1] tracking-tight">
                    Cleaning tips &amp; local guides.
                </h2>
            </div>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-navy font-bold text-[14px] tracking-tight hover:opacity-70 transition-opacity flex-shrink-0">
                View all articles
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($latestPosts as $post)
            <div class="wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <x-blog-card :post="$post" />
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

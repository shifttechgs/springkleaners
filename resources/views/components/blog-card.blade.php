@props(['post', 'featured' => false])

<a href="{{ route('blog.show', $post['slug']) }}"
   class="group block bg-white rounded-2xl overflow-hidden border border-black/5 shadow-[0_2px_20px_rgba(8,29,58,0.06)] hover:shadow-[0_20px_40px_rgba(8,29,58,0.12)] hover:-translate-y-1.5 transition-all duration-300 {{ $featured ? 'lg:col-span-2 lg:row-span-1' : '' }}">
    <div class="relative overflow-hidden {{ $featured ? 'aspect-[16/8]' : 'aspect-[16/10]' }}">
        <img src="{{ $post['cover'] }}"
             alt="{{ $post['title'] }}"
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-[#040f1f]/40 via-transparent to-transparent"></div>
        <span class="absolute top-4 left-4 bg-[#f6e304] text-[#081d3a] text-[11px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full">
            {{ $post['category'] }}
        </span>
    </div>
    <div class="p-6 {{ $featured ? 'lg:p-8' : '' }}">
        <div class="flex items-center gap-2 text-muted text-[12px] font-medium mb-3">
            <span>{{ \Illuminate\Support\Carbon::parse($post['date'])->format('d M Y') }}</span>
            <span class="w-1 h-1 rounded-full bg-muted/50"></span>
            <span>{{ $post['read_time'] }} min read</span>
        </div>
        <h3 class="font-extrabold text-navy tracking-tight leading-snug mb-3 group-hover:text-[#081d3a]/80 transition-colors {{ $featured ? 'text-2xl lg:text-3xl' : 'text-lg' }}">
            {{ $post['title'] }}
        </h3>
        <p class="text-muted text-[14px] leading-relaxed {{ $featured ? 'max-w-xl' : 'line-clamp-2' }}">
            {{ $post['excerpt'] }}
        </p>
        <span class="inline-flex items-center gap-2 mt-5 text-navy font-bold text-[13px] tracking-tight">
            Read article
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </span>
    </div>
</a>

@extends('layouts.app')
@section('title', $post['title'].' | SpringKleaners')
@section('description', $post['excerpt'])
@section('content')
    @php
        $publishedIso = \Illuminate\Support\Carbon::parse($post['date'])->toIso8601String();

        // Render the guide first so the contents list and checklist total come from what is actually on the page:
        // each <h2> gets an id for the contents links, and ticking tasks are counted for the progress card.
        $toc = [];
        $body = preg_replace_callback('#<h2\b([^>]*)>(.*?)</h2>#s', function ($m) use (&$toc) {
            $text = trim(html_entity_decode(strip_tags($m[2]), ENT_QUOTES));
            $id = \Illuminate\Support\Str::slug($text) ?: 'section';
            while (collect($toc)->contains('id', $id)) {
                $id .= '-2';
            }
            $toc[] = ['id' => $id, 'text' => $text];

            return '<h2 id="'.$id.'"'.$m[1].'>'.$m[2].'</h2>';
        }, view('blog.posts.'.$post['slug'])->render());
        $taskTotal = preg_match_all('/data-guide-item="/', $body);
        // The first task list is where "Start the checklist" jumps to.
        $body = preg_replace('/<div class="guide-tasks" data-guide-tasks/', '<div id="checklist" class="guide-tasks" data-guide-tasks', $body, 1);
        $summary = $post['summary'] ?? [];
        $shareText = $post['title'].' '.url('/blog/'.$post['slug']);
    @endphp

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": {!! json_encode($post['title']) !!},
        "description": {!! json_encode($post['excerpt']) !!},
        "image": {!! json_encode(url($post['cover'])) !!},
        "datePublished": {!! json_encode($publishedIso) !!},
        "dateModified": {!! json_encode($publishedIso) !!},
        "author": { "@@type": "Organization", "name": "SpringKleaners" },
        "publisher": {
            "@@type": "Organization",
            "name": "SpringKleaners",
            "logo": { "@@type": "ImageObject", "url": {!! json_encode(url('/images/fav.png')) !!} }
        },
        "mainEntityOfPage": {!! json_encode(url('/blog/'.$post['slug'])) !!}
    }
    </script>

    @php
        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'How-To Guides', 'item' => route('blog.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => url()->current()],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @if ($taskTotal)
    <script>
        // Ticked tasks are kept on this device only (localStorage), keyed by guide. Nothing is sent anywhere.
        document.addEventListener('alpine:init', function () {
            var key = 'sk-guide:{{ $post['slug'] }}';
            var load = function () { try { return JSON.parse(localStorage.getItem(key)) || {}; } catch (e) { return {}; } };
            Alpine.store('guide', {
                total: {{ $taskTotal }},
                done: load(),
                get count() { var d = this.done; return Array.prototype.filter.call(document.querySelectorAll('[data-guide-item]'), function (el) { return d[el.dataset.guideItem]; }).length; },
                toggle: function (id) {
                    var next = Object.assign({}, this.done);
                    if (next[id]) { delete next[id]; } else { next[id] = true; }
                    this.done = next;
                    try { localStorage.setItem(key, JSON.stringify(next)); } catch (e) {}
                },
                clear: function () { this.done = {}; try { localStorage.removeItem(key); } catch (e) {} },
            });
        });
    </script>
    @endif

    <div class="print:hidden">@include('components.navbar')</div>

    {{-- Header: the headline carries the page; the real photo sits on the bottom edge. --}}
    <header class="guide-hero">
        <div class="section-wrap guide-hero__grid">
            <div class="guide-hero__copy">
                <nav class="guide-hero__crumbs print:hidden" aria-label="Breadcrumb">
                    <a href="/">Home</a><span aria-hidden="true">/</span><a href="{{ route('blog.index') }}">How-To Guides</a><span aria-hidden="true">/</span><a href="{{ route('blog.index', ['category' => $post['category']]) }}">{{ $post['category'] }}</a>
                </nav>
                <h1 class="guide-hero__title">{{ $post['title'] }}</h1>
                <p class="guide-hero__dek">{{ $post['excerpt'] }}</p>
                <p class="guide-hero__meta">By the {{ $post['author'] }}. {{ \Illuminate\Support\Carbon::parse($post['date'])->format('j F Y') }}. {{ $post['read_time'] }}-minute read.</p>
                @if ($taskTotal)
                <a href="#checklist" class="guide-hero__start print:hidden">
                    Start the checklist
                    <span class="guide-hero__start-count">{{ $taskTotal }} tasks</span>
                </a>
                @endif
            </div>
            <div class="guide-hero__photo print:hidden">
                <img src="{{ $post['cover'] }}"{!! \App\Support\Img::attrs($post['cover'], '(min-width: 1024px) 40vw, calc(100vw - 32px)') !!} alt="{{ $post['cover_alt'] ?? $post['title'] }}" fetchpriority="high" width="800" height="600"@if (! empty($post['cover_position'])) style="object-position: {{ $post['cover_position'] }}"@endif>
            </div>
        </div>
    </header>

    <div class="guide-wrap">
        <div class="section-wrap guide-layout">

            {{-- Desktop rail: where you are, your checklist, and sharing. --}}
            <aside class="guide-rail print:hidden" aria-label="Guide tools">
                <div class="guide-rail__inner">
                    @if (count($toc) > 1)
                    {{-- The current section is the last heading scrolled past the top third of the screen. --}}
                    <nav aria-label="Contents" x-data="{ active: null }"
                         x-init="const hs = [...document.querySelectorAll('.guide-body h2[id]')]; const pick = () => { const line = innerHeight * 0.35; let id = null; for (const h of hs) { if (h.getBoundingClientRect().top < line) id = h.id; } active = id; }; let raf = 0; addEventListener('scroll', () => { if (!raf) raf = requestAnimationFrame(() => { raf = 0; pick(); }); }, { passive: true }); pick()">
                        <p class="guide-rail__title">Contents</p>
                        <ol class="guide-toc">
                            @foreach ($toc as $item)
                            <li><a href="#{{ $item['id'] }}" :class="active === '{{ $item['id'] }}' && 'is-active'" :aria-current="active === '{{ $item['id'] }}' ? 'location' : null">{{ $item['text'] }}</a></li>
                            @endforeach
                        </ol>
                    </nav>
                    @endif

                    @if ($taskTotal)
                    <div class="guide-progress" x-data>
                        <p class="guide-rail__title">Your checklist</p>
                        <p class="guide-progress__count"><span x-text="$store.guide ? $store.guide.count : 0">0</span> of {{ $taskTotal }} done</p>
                        <div class="guide-progress__bar" aria-hidden="true"><span :style="`transform: scaleX(${($store.guide ? $store.guide.count : 0) / {{ $taskTotal }}})`"></span></div>
                        <p class="guide-progress__note">Saved on this device only.</p>
                        <div class="guide-progress__actions">
                            <button type="button" @click="window.print()">Print checklist</button>
                            <button type="button" x-show="$store.guide && $store.guide.count" x-cloak @click="$store.guide.clear()">Clear ticks</button>
                        </div>
                    </div>
                    @endif

                    <div class="guide-share">
                        <p class="guide-rail__title">Share this guide</p>
                        <div class="guide-share__links">
                            <a href="https://wa.me/?text={{ rawurlencode($shareText) }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                            <a href="mailto:?subject={{ rawurlencode($post['title']) }}&body={{ rawurlencode(url('/blog/'.$post['slug'])) }}">Email</a>
                        </div>
                    </div>
                </div>
            </aside>

            <article class="guide-body">
                @if ($summary)
                <section class="guide-summary" aria-labelledby="short-version">
                    <h2 id="short-version" class="guide-summary__title">The short version</h2>
                    <ul>
                        @foreach ($summary as $line)
                        <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                {{-- Phones: contents and checklist tools fold into one panel under the summary. --}}
                @if (count($toc) > 1 || $taskTotal)
                <details class="guide-mobile-tools print:hidden">
                    <summary>
                        <span>Contents{{ $taskTotal ? ' and checklist' : '' }}</span>
                        @if ($taskTotal)
                        <span class="guide-mobile-tools__count" x-data><span x-text="$store.guide ? $store.guide.count : 0">0</span>/{{ $taskTotal }}</span>
                        @endif
                    </summary>
                    <ol class="guide-toc">
                        @foreach ($toc as $item)
                        <li><a href="#{{ $item['id'] }}" onclick="this.closest('details').open = false">{{ $item['text'] }}</a></li>
                        @endforeach
                    </ol>
                    @if ($taskTotal)
                    <div class="guide-progress__actions" x-data>
                        <button type="button" @click="window.print()">Print checklist</button>
                        <button type="button" x-show="$store.guide && $store.guide.count" x-cloak @click="$store.guide.clear()">Clear ticks</button>
                    </div>
                    <p class="guide-progress__note">Ticks are saved on this device only.</p>
                    @endif
                </details>
                @endif

                {!! $body !!}

                <div class="guide-share guide-share--end print:hidden">
                    <p class="guide-rail__title">Found this useful? Pass it on.</p>
                    <div class="guide-share__links">
                        <a href="https://wa.me/?text={{ rawurlencode($shareText) }}" target="_blank" rel="noopener noreferrer">Share on WhatsApp</a>
                        <a href="mailto:?subject={{ rawurlencode($post['title']) }}&body={{ rawurlencode(url('/blog/'.$post['slug'])) }}">Share by email</a>
                    </div>
                </div>
            </article>
        </div>
    </div>

    @if($related->isNotEmpty())
    <section class="bg-[#f4f6f9] section-py !py-16 print:hidden">
        <div class="section-wrap">
            <div class="flex items-end justify-between mb-10">
                <h2 class="text-2xl lg:text-3xl font-extrabold text-navy tracking-tight">More how-to guides</h2>
                <a href="{{ route('blog.index') }}" class="text-navy font-bold text-[14px] tracking-tight hidden sm:inline-flex underline underline-offset-4 decoration-[#081d3a]/30 hover:decoration-[#081d3a]">All guides</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $item)
                <x-blog-card :post="$item" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="print:hidden">
        @include('components.final-cta')
        @include('components.footer')
    </div>
@endsection

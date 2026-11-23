@extends('layouts.app')
@section('title', 'How-To Cleaning Guides & Checklists, Cape Town | SpringKleaners')
@section('description', 'How-to cleaning guides for Cape Town homes, rentals and offices: move-out checklists, after-builders cleans, cleaning costs and keeping on top of it.')
{{-- One guides page: a filtered view (?topic=) is the same list, so it isn't indexed separately. --}}
@if ($activeTopic || request()->hasAny(['topic', 'category', 'page']))
@section('robots', 'noindex, follow')
@endif
@section('content')
    @php
        $featured = $posts->first();
        $topicLabels = $topics->pluck('label', 'key');
        $matches = fn ($post, $topic) => ! $topic || in_array($topic, $post['topics'] ?? [], true);
    @endphp

    @include('components.navbar')

    <div x-data="{
            topic: @js($activeTopic),
            stuck: false,
            counts: @js($topics->pluck('count', 'key')),
            total: {{ $posts->count() }},
            labels: @js($topicLabels),
            pick(key, url) {
                this.topic = key;
                history.replaceState(null, '', url);
                const list = document.getElementById('guides-list');
                if (list && list.getBoundingClientRect().top < 0) list.scrollIntoView({ block: 'start' });
            },
            has(topics) { return !this.topic || topics.includes(this.topic) },
            get shown() { return this.topic ? this.counts[this.topic] : this.total },
        }">

        <header class="guides-hero">
            <div class="section-wrap guides-hero__grid">
                <h1 class="guides-hero__title">How-to guides for Cape Town homes, rentals and offices.</h1>
                <div class="guides-hero__side">
                    <p class="guides-hero__dek">Checklists, what things cost and what to expect, written by the team that does the cleaning.</p>
                    <h2 class="guides-hero__ask" id="picker-title">What are you dealing with?</h2>
                </div>
            </div>
        </header>

        {{-- Situation filter: sits at the foot of the header, then stays pinned under the menu while you scroll. --}}
        {{-- Marker: when it scrolls up under the menu, the bar below is pinned. --}}
        <div aria-hidden="true" x-ref="barMarker"></div>
        <div class="guides-bar" :class="stuck && 'is-stuck'"
             x-init="new IntersectionObserver(([e]) => { stuck = !e.isIntersecting && e.boundingClientRect.top < 200 }, { rootMargin: '-110px 0px 0px 0px' }).observe($refs.barMarker)">
            <nav class="section-wrap guides-bar__options" aria-labelledby="picker-title">
                <a href="{{ route('blog.index') }}" @click.prevent="pick(null, '{{ route('blog.index') }}')"
                   class="guides-chip {{ ! $activeTopic ? 'is-on' : '' }}" :class="!topic && 'is-on'" :aria-current="!topic ? 'true' : null" @if (! $activeTopic) aria-current="true" @endif>
                    All guides <span class="guides-chip__count">{{ $posts->count() }}</span>
                </a>
                @foreach ($topics as $topic)
                @php($url = route('blog.index', ['topic' => $topic['key']]))
                <a href="{{ $url }}" @click.prevent="pick('{{ $topic['key'] }}', '{{ $url }}')"
                   class="guides-chip {{ $activeTopic === $topic['key'] ? 'is-on' : '' }}" :class="topic === '{{ $topic['key'] }}' && 'is-on'"
                   :aria-current="topic === '{{ $topic['key'] }}' ? 'true' : null" @if ($activeTopic === $topic['key']) aria-current="true" @endif>
                    {{ $topic['label'] }} <span class="guides-chip__count">{{ $topic['count'] }}</span>
                </a>
                @endforeach
            </nav>
        </div>

        <div class="guides" id="guides-list">
            <div class="section-wrap">
                <p class="guides__status" aria-live="polite">
                    <span x-text="topic ? `${shown} ${shown === 1 ? 'guide' : 'guides'} for “${labels[topic].toLowerCase()}”` : `All ${total} guides, newest first`">{{ $activeTopic ? $topics->firstWhere('key', $activeTopic)['count'].' guides for “'.strtolower($topicLabels[$activeTopic]).'”' : 'All '.$posts->count().' guides, newest first' }}</span>
                </p>

                {{-- Newest guide, shown big on the unfiltered list. --}}
                @if ($featured)
                <a href="{{ route('blog.show', $featured['slug']) }}" class="guides-feature" x-show="!topic" @if ($activeTopic) style="display:none" @endif>
                    <span class="guides-feature__photo">
                        <img src="{{ $featured['cover'] }}"{!! \App\Support\Img::attrs($featured['cover'], '(min-width: 1024px) 55vw, calc(100vw - 32px)') !!} alt="{{ $featured['cover_alt'] ?? $featured['title'] }}" fetchpriority="high" width="800" height="500"@if (! empty($featured['cover_position'])) style="object-position: {{ $featured['cover_position'] }}"@endif>
                    </span>
                    <span class="guides-feature__copy">
                        <span class="guides-feature__kicker">Newest guide</span>
                        <span class="guides-feature__title">{{ $featured['title'] }}</span>
                        <span class="guides-feature__excerpt">{{ $featured['excerpt'] }}</span>
                        <span class="guides-row__meta">
                            @foreach (($featured['topics'] ?? []) as $t)
                                @if (isset($topicLabels[$t]))<span class="guides-row__topic">{{ $topicLabels[$t] }}</span>@endif
                            @endforeach
                            <span>{{ $featured['read_time'] }} min read</span>
                        </span>
                    </span>
                </a>
                @endif

                <ol class="guides-list">
                    @foreach ($posts as $post)
                    @php($isFeatured = $loop->first)
                    <li x-show="{{ $isFeatured ? 'topic && ' : '' }}has(@js($post['topics'] ?? []))"
                        @if (($isFeatured && ! $activeTopic) || ! $matches($post, $activeTopic)) style="display:none" @endif>
                        <a href="{{ route('blog.show', $post['slug']) }}" class="guides-row">
                            <span class="guides-row__photo">
                                <img src="{{ $post['cover'] }}"{!! \App\Support\Img::attrs($post['cover'], '(min-width: 1024px) 176px, 104px') !!} alt="{{ $post['cover_alt'] ?? $post['title'] }}" loading="lazy" decoding="async" width="176" height="132"@if (! empty($post['cover_position'])) style="object-position: {{ $post['cover_position'] }}"@endif>
                            </span>
                            <span class="guides-row__body">
                                <span class="guides-row__title">{{ $post['title'] }}</span>
                                <span class="guides-row__excerpt">{{ $post['excerpt'] }}</span>
                                <span class="guides-row__meta">
                                    @foreach (($post['topics'] ?? []) as $t)
                                        @if (isset($topicLabels[$t]))<span class="guides-row__topic">{{ $topicLabels[$t] }}</span>@endif
                                    @endforeach
                                    <span>{{ $post['read_time'] }} min read</span>
                                </span>
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    @include('components.final-cta')
    @include('components.footer')
@endsection

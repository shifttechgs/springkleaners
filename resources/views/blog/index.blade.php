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
            counts: @js($topics->pluck('count', 'key')),
            total: {{ $posts->count() }},
            labels: @js($topicLabels),
            pick(key, url) {
                this.topic = key;
                history.replaceState(null, '', url);
            },
            has(topics) { return !this.topic || topics.includes(this.topic) },
            get shown() { return this.topic ? this.counts[this.topic] : this.total },
        }">
    <header class="guides-hero">
        <div class="section-wrap guides-hero__grid">
            <div class="guides-hero__intro">
                <h1 class="guides-hero__title">How-to guides for Cape Town homes, rentals and offices.</h1>
                <p class="guides-hero__dek">Checklists, what things cost and what to expect, written by the team that does the cleaning.</p>
            </div>
            <section class="guides-picker" aria-labelledby="picker-title">
                <h2 id="picker-title" class="guides-picker__title">What are you dealing with?</h2>
                <nav class="guides-picker__options" aria-label="Filter guides by situation">
                    <a href="{{ route('blog.index') }}" @click.prevent="pick(null, '{{ route('blog.index') }}')"
                       class="guides-chip" :class="!topic && 'is-on'" :aria-current="!topic ? 'true' : null" @if (! $activeTopic) aria-current="true" @endif>
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
                <p class="guides-picker__status" aria-live="polite">
                    <span x-text="topic ? `${shown} ${shown === 1 ? 'guide' : 'guides'} for “${labels[topic].toLowerCase()}”` : `All ${total} guides, newest first`">{{ $activeTopic ? $topics->firstWhere('key', $activeTopic)['count'].' guides for “'.strtolower($topicLabels[$activeTopic]).'”' : 'All '.$posts->count().' guides, newest first' }}</span>
                </p>
            </section>
        </div>
    </header>

    <div class="guides">
        <div class="section-wrap">

            {{-- Newest guide, shown big on the unfiltered list. --}}
            @if ($featured)
            <a href="{{ route('blog.show', $featured['slug']) }}" class="guides-feature" x-show="!topic" @if ($activeTopic) style="display:none" @endif>
                <div class="guides-feature__photo">
                    <img src="{{ $featured['cover'] }}"{!! \App\Support\Img::attrs($featured['cover'], '(min-width: 1024px) 55vw, calc(100vw - 32px)') !!} alt="{{ $featured['cover_alt'] ?? $featured['title'] }}" fetchpriority="high" width="800" height="500"@if (! empty($featured['cover_position'])) style="object-position: {{ $featured['cover_position'] }}"@endif>
                </div>
                <div class="guides-feature__copy">
                    <p class="guides-feature__kicker">Newest guide</p>
                    <h3 class="guides-feature__title">{{ $featured['title'] }}</h3>
                    <p class="guides-feature__excerpt">{{ $featured['excerpt'] }}</p>
                    <p class="guides-row__meta">{{ $featured['read_time'] }}-minute read</p>
                </div>
            </a>
            @endif

            <ol class="guides-list">
                @foreach ($posts as $post)
                @php($isFeatured = $loop->first)
                <li x-show="{{ $isFeatured ? 'topic && ' : '' }}has(@js($post['topics'] ?? []))"
                    @if (($isFeatured && ! $activeTopic) || ! $matches($post, $activeTopic)) style="display:none" @endif>
                    <a href="{{ route('blog.show', $post['slug']) }}" class="guides-row">
                        <span class="guides-row__date">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j M Y') }}</span>
                        <span class="guides-row__main">
                            <span class="guides-row__title">{{ $post['title'] }}</span>
                            <span class="guides-row__excerpt">{{ $post['excerpt'] }}</span>
                        </span>
                        <span class="guides-row__meta">
                            @foreach (($post['topics'] ?? []) as $t)
                                @if (isset($topicLabels[$t]))<span class="guides-row__topic">{{ $topicLabels[$t] }}</span>@endif
                            @endforeach
                            <span>{{ $post['read_time'] }} min</span>
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

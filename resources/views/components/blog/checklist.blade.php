@props(['items', 'interactive' => false])
{{-- interactive: a task list the reader ticks off (saved on their device by $store.guide in blog/show).
     Leave it off for lists that describe things (what's included, warning signs): those aren't tasks. --}}
@if ($interactive)
    @php
        $tasks = collect($items)->map(fn ($text) => ['id' => substr(md5($text), 0, 10), 'text' => $text])->all();
        $total = count($tasks);
    @endphp
    <div class="guide-tasks" data-guide-tasks
         x-data="{ ids: @js(array_column($tasks, 'id')), get done() { const s = this.$store.guide; return s ? this.ids.filter(i => s.done[i]).length : 0 } }"
         :class="done === ids.length && 'is-complete'">
        <div class="guide-tasks__head">
            <span class="guide-tasks__hint" x-text="done === ids.length ? 'Room done' : 'Tick off as you go'">Tick off as you go</span>
            <span class="guide-tasks__count"><span x-text="done">0</span> of {{ $total }} done</span>
        </div>
        <div class="guide-tasks__bar" aria-hidden="true"><span :style="`transform: scaleX(${done / ids.length})`"></span></div>
        <ul class="guide-tasks__list">
            @foreach ($tasks as $task)
            <li>
                <label class="guide-task">
                    <input type="checkbox" class="guide-task__input" data-guide-item="{{ $task['id'] }}"
                           :checked="$store.guide && $store.guide.done['{{ $task['id'] }}']"
                           @change="$store.guide && $store.guide.toggle('{{ $task['id'] }}')">
                    <span class="guide-task__box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                    </span>
                    <span class="guide-task__text">{{ $task['text'] }}</span>
                </label>
            </li>
            @endforeach
        </ul>
    </div>
@else
    <ul class="guide-list">
        @foreach ($items as $item)
        <li>{{ $item }}</li>
        @endforeach
    </ul>
@endif

{{-- Phone homepage, app chrome: the tab bar and the bottom sheets (booking, more). Shown below 1024px only. --}}
{{-- Tab bar: one icon family (1.8 stroke, rounded). The tab for where you are turns navy with a bar above it;
     Book is active while the booking sheet is open, More while its sheet is open. --}}
<nav class="tab-safe fixed inset-x-0 bottom-0 z-40 border-t border-[#081d3a]/10 bg-white/95 backdrop-blur" aria-label="App">
    <ul class="grid grid-cols-5 items-end text-[11px] font-semibold">
        <li><a href="#" x-on:click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })" class="press relative flex w-full flex-col items-center gap-0.5 pb-1 pt-2 transition-colors" :class="tab === 'home' ? 'text-[#081d3a]' : 'text-[#5b6779]'" :aria-current="tab === 'home' ? 'page' : null">
            <span class="absolute top-0 h-[3px] w-7 rounded-b-full bg-[#081d3a] transition-opacity" :class="tab === 'home' ? 'opacity-100' : 'opacity-0'" aria-hidden="true"></span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11l9-7 9 7v9a1 1 0 01-1 1h-5v-6h-6v6H4a1 1 0 01-1-1v-9z"/></svg>Home</a></li>
        <li><a href="#app-services" class="press relative flex w-full flex-col items-center gap-0.5 pb-1 pt-2 transition-colors" :class="tab === 'services' ? 'text-[#081d3a]' : 'text-[#5b6779]'" :aria-current="tab === 'services' ? 'page' : null">
            <span class="absolute top-0 h-[3px] w-7 rounded-b-full bg-[#081d3a] transition-opacity" :class="tab === 'services' ? 'opacity-100' : 'opacity-0'" aria-hidden="true"></span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>Services</a></li>
        <li class="flex justify-center"><button type="button" x-on:click="openQuote()" class="press -mt-6 flex flex-col items-center gap-0.5 pb-1 text-[#081d3a]">
            <span class="grid h-14 w-14 place-items-center rounded-full bg-[#f6e304] shadow-[0_6px_16px_-6px_rgba(8,29,58,0.5)] ring-4 ring-white transition-transform" :class="tab === 'book' && 'scale-105'">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
            </span>Book</button></li>
        <li><a href="{{ \App\Support\WhatsApp::link() }}" target="_blank" rel="noopener noreferrer" class="press relative flex w-full flex-col items-center gap-0.5 pb-1 pt-2 text-[#5b6779]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.5 8.5 0 01-12.6 7.4L3 20.5l1.6-5.2A8.5 8.5 0 1121 11.5z"/></svg>WhatsApp</a></li>
        <li><button type="button" x-on:click="sheet = 'more'" class="press relative flex w-full flex-col items-center gap-0.5 pb-1 pt-2 transition-colors" :class="tab === 'more' ? 'text-[#081d3a]' : 'text-[#5b6779]'">
            <span class="absolute top-0 h-[3px] w-7 rounded-b-full bg-[#081d3a] transition-opacity" :class="tab === 'more' ? 'opacity-100' : 'opacity-0'" aria-hidden="true"></span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>More</button></li>
    </ul>
</nav>

{{-- Sheets: booking and "more". Swipe down on the handle to close. --}}
<div x-show="sheet" x-cloak x-transition.opacity.duration.200ms x-on:click="close()" class="fixed inset-0 z-50 bg-black/50"></div>
<div x-show="sheet" x-cloak role="dialog" aria-modal="true" :aria-label="sheet === 'quote' ? 'Book a clean' : 'More'"
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
     class="app-sheet fixed inset-x-0 bottom-0 z-50 mx-auto flex max-h-[92svh] max-w-lg flex-col rounded-t-3xl bg-[#f6f8fb]"
     :class="sheet === 'quote' && 'h-[92svh]'"
     :style="dragY ? `transform: translateY(${dragY}px); transition: none` : ''">
    <div class="flex-shrink-0 px-4 pt-2.5" x-on:touchstart.passive="dragBegin($event)" x-on:touchmove.passive="dragMove($event)" x-on:touchend="dragEnd()">
        <div class="mx-auto mb-2 h-1.5 w-10 rounded-full bg-[#081d3a]/20"></div>
        <div x-show="sheet === 'more'" class="flex items-center justify-between py-2">
            <p class="text-[13px] font-semibold uppercase tracking-[0.12em] text-[#5b6779]">More</p>
            <button type="button" x-on:click="close()" aria-label="Close" class="press grid h-10 w-10 place-items-center rounded-full text-[#081d3a]/70 hover:bg-[#081d3a]/[0.06]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    </div>
    <div class="min-h-0 flex-1" x-show="sheet === 'quote'">
        @include('components.app-booking')
    </div>
    <nav class="flex-1 overflow-y-auto px-4 pb-8 tab-safe" x-show="sheet === 'more'" aria-label="More">
        @foreach ([['About us', '/about'], ['Pricing', '/#pricing'], ['Areas we serve', route('areas.index')], ['Blog', '/blog'], ['Full booking form', route('booking.show')]] as [$label, $href])
            <a href="{{ $href }}" class="press flex items-center justify-between border-b border-[#081d3a]/10 py-4 text-[16px] font-semibold text-[#081d3a]">{{ $label }}
                <svg class="h-4 w-4 text-[#081d3a]/40" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
        @endforeach
    </nav>
</div>

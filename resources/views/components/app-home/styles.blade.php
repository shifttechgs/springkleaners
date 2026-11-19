{{-- Styles and state for the phone (app) layout of the homepage: see welcome.blade.php. The tab bar replaces the
     site-wide floating WhatsApp/Quote bar here. --}}
@push('styles')
<style>
    [data-mobile-bar] { display: none !important; }
    .no-bar { scrollbar-width: none; } .no-bar::-webkit-scrollbar { display: none; }
    .press { transition: transform 0.12s ease; } .press:active { transform: scale(0.97); }
    .app-pill { border: 1px solid transparent; }
    .app-pill.is-pill {
        border-color: rgba(255, 255, 255, 0.1); border-radius: 2.5rem; padding-inline: 0.75rem 0.5rem;
        background-color: rgba(13, 27, 51, 0.55);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 8px 40px rgba(0, 0, 0, 0.35);
        -webkit-backdrop-filter: blur(20px) saturate(180%); backdrop-filter: blur(20px) saturate(180%);
    }
    .tab-safe { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }
    @media (prefers-reduced-motion: reduce) { .press, .press:active { transition: none; transform: none; } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', function () {
    // Which sheet is open, which tab is lit, and swipe-down-to-close on the sheet handle.
    Alpine.data('appShell', function () {
        return {
            sheet: null, section: 'home', dragY: 0, dragStart: null,
            init() { this.$watch('sheet', v => document.documentElement.style.overflow = v ? 'hidden' : ''); },
            get tab() { return this.sheet === 'quote' ? 'book' : (this.sheet === 'more' ? 'more' : this.section) },
            onScroll() {
                if (window.innerWidth >= 1024) return;
                const r = document.getElementById('app-services')?.getBoundingClientRect();
                this.section = r && r.top < window.innerHeight * 0.45 && r.bottom > 120 ? 'services' : 'home';
            },
            openQuote(detail = {}) { this.sheet = 'quote'; this.$nextTick(() => window.dispatchEvent(new CustomEvent('open-quote', { detail }))); },
            close() { this.sheet = null; this.dragY = 0; },
            dragBegin(e) { this.dragStart = e.touches[0].clientY; },
            dragMove(e) { if (this.dragStart !== null) this.dragY = Math.max(0, e.touches[0].clientY - this.dragStart); },
            dragEnd() { if (this.dragY > 110) this.close(); this.dragY = 0; this.dragStart = null; },
        };
    });
});
</script>
@endpush

{{-- Phones (below 1024px): the app shell for inner pages, the same tab bar and booking/More sheets as the homepage
     (components/app-home/chrome). Rendered by layouts/app.blade.php; pages opt out with @section('no_app_shell').
     Pages can set:
       @section('app_tab', 'services')   which tab is lit (else none)
       @section('book_service', $slug)   service the Book sheet opens on (bookable services only)
       @section('book_suburb', $name)    suburb the Book sheet opens with --}}
@php
    $appTab = trim($__env->yieldContent('app_tab')) ?: null;
    $appBook = array_filter([
        'service' => trim($__env->yieldContent('book_service')) ?: null,
        'suburb' => html_entity_decode(trim($__env->yieldContent('book_suburb')), ENT_QUOTES) ?: null,
    ]);
@endphp
@include('components.app-home.styles')

<div class="lg:hidden" data-app-shell x-data="appShell()" x-init="section = @js($appTab)"
     x-on:keydown.escape.window="close()" x-on:close-sheet="close()">
    @include('components.app-home.chrome', ['inner' => true, 'book' => $appBook])
</div>
{{-- Room for the tab bar, so the end of the page isn't hidden behind it. --}}
<div class="lg:hidden h-20" aria-hidden="true"></div>

{{-- Computers never see the shell: skip setting it up (Alpine x-ignore) unless the screen narrows later. --}}
<script>
    (function () {
        var wide = window.matchMedia('(min-width: 1024px)');
        var shell = document.querySelector('[data-app-shell]');
        if (!shell || !wide.matches) return;
        shell.setAttribute('x-ignore', '');
        wide.addEventListener('change', function onChange(e) {
            if (e.matches || !shell.hasAttribute('x-ignore')) return;
            shell.removeAttribute('x-ignore');
            delete shell._x_ignore; delete shell._x_ignoreSelf;
            if (window.Alpine) window.Alpine.initTree(shell);
            wide.removeEventListener('change', onChange);
        });
    })();
</script>

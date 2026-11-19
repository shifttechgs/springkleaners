@props(['model', 'id' => 'photo-consent'])
{{-- Optional consent for before & after photos, shared by the hero quote form and the full booking form so the
     wording is identical. Off by default and never required to book (POPIA: consent must be freely given and name
     its purpose). What's agreed is printed on the quote, the PDFs and the emails (ServiceSnapshot::photoConsentLabel).
     `model` is the Alpine property to bind, e.g. "photoConsent" or "form.photoConsent". --}}
<label for="{{ $id }}" {{ $attributes->class(['flex items-start gap-3 rounded-lg border border-[#081d3a]/10 bg-white px-3.5 py-3 cursor-pointer']) }}>
    <input id="{{ $id }}" type="checkbox" x-model="{{ $model }}"
           class="mt-0.5 h-5 w-5 flex-shrink-0 cursor-pointer rounded border-[#081d3a]/30 accent-[#081d3a]">
    <span class="min-w-0">
        <span class="block text-[#081d3a] text-[13.5px] font-semibold leading-snug">Yes, take before &amp; after photos of my clean</span>
        <span class="block text-[#5b6779] text-[12px] leading-relaxed mt-0.5">
            It's proof of the work we did for you, and it helps a small local team show what we can do.
            We only photograph the rooms we clean, never people, house numbers or anything with your name on it.
        </span>
    </span>
</label>

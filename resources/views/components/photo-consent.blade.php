@props(['model', 'id' => 'photo-consent'])
{{-- Optional consent for before & after photos, shared by the hero quote form and the full booking form so the
     wording is identical. Off by default and never required to book (POPIA: consent must be freely given and name
     its purpose). What's agreed is printed on the quote, the PDFs and the emails (ServiceSnapshot::photoConsentLabel).
     `model` is the Alpine property to bind, e.g. "photoConsent" or "form.photoConsent". --}}
<label for="{{ $id }}" {{ $attributes->class(['flex items-start gap-3 rounded-lg border border-[#081d3a]/10 bg-white px-3.5 py-3 cursor-pointer']) }}>
    <input id="{{ $id }}" type="checkbox" x-model="{{ $model }}"
           class="mt-0.5 h-5 w-5 flex-shrink-0 cursor-pointer rounded border-[#081d3a]/30 accent-[#081d3a]">
    <span class="min-w-0">
        <span class="block text-[#081d3a] text-[13.5px] font-semibold leading-snug">Yes, you may take before &amp; after photos</span>
        <span class="block text-[#5b6779] text-[12px] leading-relaxed mt-0.5">
            Of the rooms you clean, for your records and to show your work online. Never people, house numbers,
            documents or anything that shows whose home it is. Optional, and you can change your mind any time on WhatsApp.
        </span>
    </span>
</label>

@extends('layouts.app')
@section('title', 'Terms of Service | SpringKleaners')
@section('description', 'The terms that apply when you book a deep clean, end-of-tenancy clean or post-construction clean with SpringKleaners.')
@section('content')

    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        <div class="section-wrap relative z-10 max-w-3xl">
            <div class="flex items-center gap-2 text-white/40 text-[12px] font-medium mb-6">
                <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                <span>/</span>
                <span class="text-white/60">Terms of Service</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-[1.08] tracking-tight mb-4">
                Terms of Service
            </h1>
            <p class="text-white/50 text-[13px]">Last updated: July 2026</p>
        </div>
    </section>

    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="max-w-2xl mx-auto prose-legal text-[#081d3a]">

                <p class="text-[15px] leading-relaxed text-[#647082] mb-8">
                    These terms apply whenever you request a quote or book a service with SpringKleaners. By booking with us, you agree to the terms below.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">1. Services offered</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    We offer Deep Cleaning, End-of-Tenancy Cleaning, and Post-Construction Cleaning across Cape Town's Northern Suburbs. Full details of what's included in each service are listed on the relevant service page.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">2. Quotes &amp; pricing</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    Prices shown on our site are estimates only. A final, itemised quote is confirmed after a free, no-obligation inspection of your property (or the details you provide during booking), and you approve that quote before any work begins. Prices may vary based on property size, condition, and any add-ons selected.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">3. Booking &amp; scheduling</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    We currently operate on weekends (Saturday and Sunday), with a limited number of jobs per day. Booking a date does not guarantee availability until confirmed by us — our online calendar reflects real-time capacity, but slots can fill between viewing and submitting a request.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">4. Cancellations &amp; rescheduling</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    Please give us at least 24 hours' notice if you need to cancel or reschedule, so we can offer the slot to another client. Message us on WhatsApp as early as possible.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">5. Your responsibilities</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-3">To help us do the best possible job, please:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-[14px] leading-relaxed text-[#647082] mb-6">
                    <li>Provide accurate property details when booking (size, bedrooms, bathrooms, condition)</li>
                    <li>Ensure our team has safe access to the property at the agreed time</li>
                    <li>Secure or remove valuable, fragile, or sentimental items ahead of the clean</li>
                    <li>Let us know about any existing damage, so it isn't mistaken for something caused during the clean</li>
                </ul>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">6. Insurance &amp; liability</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    SpringKleaners is fully insured and every team member is background-checked before joining us. We take reasonable care on every job, but we are not liable for pre-existing damage, normal wear and tear, or issues arising from inaccurate information provided at booking.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">7. Satisfaction guarantee</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    If you're not happy with any aspect of the clean, let us know within 24 hours and we'll return to address it at no additional charge.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">8. Payment</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    Payment terms are confirmed with your quote. Invoices are issued after the quote is accepted and the job is scheduled.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">9. Changes to these terms</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    We may update these terms from time to time. The "last updated" date at the top of this page reflects the most recent revision.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">10. Governing law</h2>
                <p class="text-[14px] leading-relaxed text-[#647082] mb-6">
                    These terms are governed by the laws of South Africa.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">11. Contact us</h2>
                <ul class="list-none space-y-1.5 text-[14px] leading-relaxed text-[#081d3a] font-medium mb-6">
                    <li>WhatsApp / Phone: <a href="tel:+27815274711" class="text-[#081d3a] hover:text-[#a9791f]">+27 81 527 4711</a></li>
                    <li>Email: <a href="mailto:bookings@springkleaners.co.za" class="text-[#081d3a] hover:text-[#a9791f]">bookings@springkleaners.co.za</a></li>
                </ul>

            </div>
        </div>
    </section>

    @include('components.final-cta')
    @include('components.footer')
@endsection

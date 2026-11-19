@extends('layouts.app')
@section('title', 'Privacy Policy | SpringKleaners')
@section('description', 'How SpringKleaners collects, uses and protects your personal information, in line with South Africa\'s Protection of Personal Information Act (POPIA).')
@section('content')

    @include('components.navbar')

    <section class="bg-[#081d3a] pt-36 pb-14 lg:pt-44 lg:pb-16 relative overflow-hidden">
        <div class="section-wrap relative z-10 max-w-3xl">
            <div class="flex items-center gap-2 text-white/60 text-[12px] font-medium mb-6">
                <a href="/" class="hover:text-[#f6e304] transition-colors">Home</a>
                <span>/</span>
                <span class="text-white/60">Privacy Policy</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-[1.08] tracking-tight mb-4">
                Privacy Policy
            </h1>
            <p class="text-white/50 text-[13px]">Last updated: November 2026</p>
        </div>
    </section>

    <section class="bg-white section-py">
        <div class="section-wrap">
            <div class="max-w-2xl mx-auto prose-legal text-[#081d3a]">

                <p class="text-[15px] leading-relaxed text-[#5b6779] mb-8">
                    SpringKleaners ("we", "us", "our") respects your privacy and is committed to protecting your personal information in accordance with South Africa's Protection of Personal Information Act, 2013 (POPIA). This policy explains what information we collect, why we collect it, and how we use it.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">1. Information we collect</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">When you request a quote, make a booking, or contact us, we collect:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    <li>Your name, phone number and email address</li>
                    <li>Your property address or suburb, and details about the property (size, number of bedrooms/bathrooms, access instructions)</li>
                    <li>Details of the service requested, scheduling preferences, and any notes you provide</li>
                    <li>Messages you send us via WhatsApp, phone, or the booking form</li>
                    <li>Before and after photos of the rooms we clean, only if you tick the box for it when you book. We use them as proof of the work and to showcase it online. They never show people, house numbers or anything with your name on it. If you change your mind, tell us and we'll stop taking them and delete the ones we have.</li>
                </ul>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">2. How we use your information</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    We use your information to prepare quotes, schedule and deliver cleaning services, communicate with you about your booking, and maintain a record of past jobs so we can serve you better in future. We do not use your information for unrelated marketing without your consent.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">3. How we share your information</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    We do not sell or rent your personal information to third parties. Your details are only accessible to SpringKleaners staff who need them to fulfil your booking. Where a quote or invoice is generated, that document is only shared with you directly. Google processes website usage data for us, as set out in section 5.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">4. WhatsApp communication</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    Bookings and quotes are commonly confirmed over WhatsApp. When you message us there, that conversation is also subject to WhatsApp's own privacy policy, operated by Meta. We only use WhatsApp messages to manage your booking.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">5. Cookies &amp; analytics</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">
                    We use Google Analytics to see how people use this site: which pages get visited, what kind of device people are on, roughly which city they're in, and how far they get through our quote form. It shows us which pages need fixing and where booking gets stuck.
                </p>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">
                    Google Analytics never gets your name, phone number, email, street address or anything you type into our forms. On the quote form we only record the step you reached, the service you picked, the property type and the price shown.
                </p>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">
                    Google Analytics puts cookies in your browser (their names start with <code class="text-[13px]">_ga</code>) so it can tell one visit from another. Google processes this data for us and may store it outside South Africa. We don't use it for advertising and we don't sell it.
                </p>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">
                    The site also sets two cookies it needs to work. One keeps your session going while you book, the other protects our forms from being misused. Neither tracks you.
                </p>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    To switch Google Analytics off, block cookies in your browser settings or install Google's <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer" class="text-[#081d3a] underline decoration-[#081d3a]/30 underline-offset-2 hover:decoration-[#081d3a]">opt-out add-on</a>.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">6. Data storage &amp; security</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    Your information is stored in our booking system with reasonable technical and organisational safeguards against loss, misuse, or unauthorised access.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">7. Your rights under POPIA</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-3">You have the right to:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    <li>Request a copy of the personal information we hold about you</li>
                    <li>Ask us to correct inaccurate information</li>
                    <li>Ask us to delete your information, subject to any legal record-keeping requirements</li>
                    <li>Object to how we process your information</li>
                </ul>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    To exercise any of these rights, contact us using the details below.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">8. Changes to this policy</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-6">
                    We may update this policy from time to time. The "last updated" date at the top of this page reflects the most recent revision.
                </p>

                <h2 class="text-xl font-extrabold text-[#081d3a] mt-10 mb-3">9. Contact us</h2>
                <p class="text-[14px] leading-relaxed text-[#5b6779] mb-2">
                    If you have any questions about this policy or how your information is handled, contact us:
                </p>
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

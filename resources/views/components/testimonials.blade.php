<section id="testimonials" class="bg-white section-py">
    <div class="section-wrap">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-16">

            {{-- Left column: heading, rating summary, CTA, photo --}}
            <div class="lg:col-span-4 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.1s">
                <div class="w-8 h-[2px] bg-[#f6e304] mb-3"></div>
                <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Customer Reviews</span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-6">
                    Loved by <br>Cape Town Homeowners.
                </h2>

                <div class="bg-[#f8f9fc] border border-gray-100 rounded-2xl p-5 mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-extrabold text-[#081d3a] leading-none">4.9</span>
                        <div>
                            <div class="text-[#f6e304] text-base leading-none">★★★★★</div>
                            <p class="text-[#647082] text-[13px] mt-1.5">Based on real Google reviews</p>
                        </div>
                    </div>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query=Spring+Kleaners+1+Stepney+Rd+Parklands+Cape+Town"
                   target="_blank" rel="noopener noreferrer"
                   class="flex items-center justify-center gap-2 bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-[#081d3a] font-semibold text-[14px] hover:border-[#f6e304] hover:bg-[#f8f9fc] transition-colors mb-6">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3h3.89c2.27-2.09 3.58-5.17 3.58-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.9l-3.89-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.11C3.24 21.3 7.29 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.27a7.2 7.2 0 010-4.54V6.62H1.26a11.98 11.98 0 000 10.76l4.01-3.11z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.61 4.58 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.26 6.62l4.01 3.11C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
                    Read all reviews on Google
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>

                <div class="rounded-2xl overflow-hidden hidden lg:block" style="min-height:220px">
                    <img src="/images/works/1.png" alt="SpringKleaners professional at work" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Right column: two auto-scrolling review columns --}}
            <div class="lg:col-span-8 wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.2s">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[600px] overflow-hidden relative"
                     style="mask-image: linear-gradient(to bottom, transparent, black 8%, black 92%, transparent); -webkit-mask-image: linear-gradient(to bottom, transparent, black 8%, black 92%, transparent);">

                    <div class="review-scroll review-scroll-up flex flex-col gap-6">
                        @php
                            $columnA = [
                                ['initials' => 'TK', 'name' => 'Tracy Kardolus', 'meta' => 'Local Guide, Google Review', 'source' => 'google', 'quote' => 'I am very impressed with the service we received. Charity is on point, very diligent and their cleaning is great. They were on time and thorough with the job. I have already booked our rental clean with them.'],
                                ['initials' => 'MD', 'name' => 'Modesta De Wet', 'meta' => 'Deep Cleaning · Bark Review', 'source' => 'bark', 'quote' => 'Everything was done according to the current protocol regulations. What a friendly, clean looking group.'],
                                ['initials' => 'SM', 'name' => 'Sandra Matthew', 'meta' => 'Google Review', 'source' => 'google', 'quote' => 'Extremely happy with the service that Spring Kleaners provided — they did an amazing job and got our carpets looking brand new again. I will definitely use their services again.'],
                            ];
                        @endphp
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($columnA as $r)
                                <div class="flex-shrink-0 bg-[#f8f9fc] rounded-2xl p-6 border border-gray-100 relative overflow-hidden">
                                    <span class="absolute -top-2 -right-1 text-[80px] font-serif text-[#081d3a]/[0.05] leading-none select-none">"</span>
                                    <div class="flex items-center justify-between mb-4 relative">
                                        <div class="text-[#f6e304] text-sm">★★★★★</div>
                                        @if ($r['source'] === 'google')
                                            <div class="flex items-center gap-1.5 text-[#647082] text-[12px] font-medium">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3h3.89c2.27-2.09 3.58-5.17 3.58-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.9l-3.89-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.11C3.24 21.3 7.29 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.27a7.2 7.2 0 010-4.54V6.62H1.26a11.98 11.98 0 000 10.76l4.01-3.11z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.61 4.58 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.26 6.62l4.01 3.11C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
                                                Google
                                            </div>
                                        @else
                                            <span class="inline-block text-[11px] bg-white text-[#081d3a]/60 px-2.5 py-1 rounded-full font-medium border border-gray-100">Bark</span>
                                        @endif
                                    </div>
                                    <p class="text-[15px] text-[#081d3a] leading-relaxed italic relative mb-5">"{{ $r['quote'] }}"</p>
                                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                        <div class="w-10 h-10 rounded-full bg-[#081d3a] flex items-center justify-center text-[#f6e304] font-bold text-[13px] flex-shrink-0">{{ $r['initials'] }}</div>
                                        <div>
                                            <p class="text-[#081d3a] text-[14px] font-semibold">{{ $r['name'] }}</p>
                                            <p class="text-[#647082] text-[12px]">{{ $r['meta'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endfor
                    </div>

                    <div class="review-scroll review-scroll-down hidden md:flex flex-col gap-6">
                        @php
                            $columnB = [
                                ['initials' => 'EP', 'name' => 'Elizabeth Pierson', 'meta' => 'Bark Review', 'source' => 'bark', 'quote' => 'Impressed with their punctuality. My bathroom looks new and I am happy my old stove stains are gone.'],
                                ['initials' => 'MV', 'name' => 'Marili van der Merwe', 'meta' => 'Local Guide, Google Review', 'source' => 'google', 'quote' => 'Thanks so much for the cleaning service you provided! The house looks brand new and it is ready for the new owners to move in! Highly recommend their services to anyone.'],
                                ['initials' => 'M', 'name' => 'Monique', 'meta' => 'House Cleaning · Bark Review', 'source' => 'bark', 'quote' => 'The cleaning was 100% perfect. Loved meeting Charity and Prosper.'],
                            ];
                        @endphp
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($columnB as $r)
                                <div class="flex-shrink-0 bg-[#f8f9fc] rounded-2xl p-6 border border-gray-100 relative overflow-hidden">
                                    <span class="absolute -top-2 -right-1 text-[80px] font-serif text-[#081d3a]/[0.05] leading-none select-none">"</span>
                                    <div class="flex items-center justify-between mb-4 relative">
                                        <div class="text-[#f6e304] text-sm">★★★★★</div>
                                        @if ($r['source'] === 'google')
                                            <div class="flex items-center gap-1.5 text-[#647082] text-[12px] font-medium">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3h3.89c2.27-2.09 3.58-5.17 3.58-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.9l-3.89-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.11C3.24 21.3 7.29 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.27a7.2 7.2 0 010-4.54V6.62H1.26a11.98 11.98 0 000 10.76l4.01-3.11z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.61 4.58 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.26 6.62l4.01 3.11C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
                                                Google
                                            </div>
                                        @else
                                            <span class="inline-block text-[11px] bg-white text-[#081d3a]/60 px-2.5 py-1 rounded-full font-medium border border-gray-100">Bark</span>
                                        @endif
                                    </div>
                                    <p class="text-[15px] text-[#081d3a] leading-relaxed italic relative mb-5">"{{ $r['quote'] }}"</p>
                                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                        <div class="w-10 h-10 rounded-full bg-[#081d3a] flex items-center justify-center text-[#f6e304] font-bold text-[13px] flex-shrink-0">{{ $r['initials'] }}</div>
                                        <div>
                                            <p class="text-[#081d3a] text-[14px] font-semibold">{{ $r['name'] }}</p>
                                            <p class="text-[#647082] text-[12px]">{{ $r['meta'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endfor
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

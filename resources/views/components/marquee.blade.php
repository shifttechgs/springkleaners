@php
    $trustReviews = [
        ['initials' => 'TK', 'name' => 'Tracy K.', 'meta' => 'Google Review', 'quote' => 'Found the job on point — very diligent, on time and thorough.'],
        ['initials' => 'SM', 'name' => 'Sandra M.', 'meta' => 'Google Review', 'quote' => 'Got our carpets looking brand new again. Definitely using them again.'],
        ['initials' => 'MV', 'name' => 'Marili v.d.M.', 'meta' => 'Google Review', 'quote' => 'The house looks brand new — highly recommend their services.'],
        ['initials' => 'MD', 'name' => 'Modesta D.W.', 'meta' => 'Bark Review', 'quote' => 'Friendly, professional, followed every protocol regulation.'],
        ['initials' => 'EP', 'name' => 'Elizabeth P.', 'meta' => 'Bark Review', 'quote' => 'My bathroom looks new — old stove stains gone. Impressed with punctuality.'],
        ['initials' => 'M', 'name' => 'Monique', 'meta' => 'Bark Review', 'quote' => 'The cleaning was 100% perfect. Loved meeting the team.'],
    ];
    $serviceAreas = ['Milnerton', 'Blouberg', 'Table View', 'Parklands', 'Century City', 'Sunningdale', 'Sea Point', 'Green Point', 'Big Bay', 'Bloubergstrand', 'Edgemead', 'Bothasig'];
@endphp
<section class="bg-[#040f1f] border-y border-white/10 py-10 overflow-hidden">

    <div class="text-center mb-8 px-5">
        <span class="text-[#f6e304] text-lg tracking-tight">★★★★★</span>
        <span class="text-white font-bold text-lg ml-2">4.9<span class="text-white/40 font-normal">/5</span></span>
        <span class="text-white/50 text-[15px]"> · rated by <span class="text-white font-semibold">10</span> Cape Town clients</span>
    </div>

    <div class="relative flex mb-8">
        <div class="marquee-track marquee-track-lg flex items-stretch gap-5">
            @for ($i = 0; $i < 2; $i++)
                @foreach ($trustReviews as $r)
                    <div class="flex-shrink-0 w-[300px] bg-white/5 border border-white/10 rounded-2xl p-5">
                        <div class="text-[#f6e304] text-sm mb-3">★★★★★</div>
                        <p class="text-white/80 text-[14px] leading-relaxed mb-4">"{{ $r['quote'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#f6e304] flex items-center justify-center text-[#081d3a] font-bold text-[12px] flex-shrink-0">{{ $r['initials'] }}</div>
                            <div>
                                <p class="text-white text-[13px] font-semibold">{{ $r['name'] }}</p>
                                <p class="text-white/40 text-[11px]">{{ $r['meta'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endfor
        </div>
    </div>

    <div class="border-t border-white/10 pt-6">
        <div class="relative flex">
            <div class="marquee-track marquee-track-reverse flex items-center gap-0">
                @for ($i = 0; $i < 2; $i++)
                    @foreach ($serviceAreas as $area)
                        <span class="text-white/50 text-[15px] font-medium flex-shrink-0 px-6">{{ $area }}</span>
                        <span class="text-[#f6e304] flex-shrink-0">•</span>
                    @endforeach
                @endfor
            </div>
        </div>
    </div>

</section>

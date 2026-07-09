@php
    $areas = [
        'Milnerton', 'Blouberg', 'Table View', 'Parklands', 'Century City', 'Sunningdale',
        'Sea Point', 'Green Point', 'Big Bay', 'Bloubergstrand', 'West Beach', 'Monte Vista',
        'Edgemead', 'Bothasig', 'Montague Gardens', 'Paarden Eiland', 'Richwood',
        'Burgundy Estate', 'Flamingo Vlei',
    ];
    $areaSlugs = [
        'Milnerton' => 'milnerton', 'Blouberg' => 'blouberg', 'Table View' => 'table-view',
        'Parklands' => 'parklands', 'Century City' => 'century-city', 'Sunningdale' => 'sunningdale',
        'Big Bay' => 'big-bay', 'Bloubergstrand' => 'bloubergstrand',
        'Sea Point' => 'sea-point', 'Green Point' => 'green-point', 'West Beach' => 'west-beach',
        'Monte Vista' => 'monte-vista', 'Edgemead' => 'edgemead', 'Bothasig' => 'bothasig',
        'Montague Gardens' => 'montague-gardens', 'Paarden Eiland' => 'paarden-eiland',
        'Richwood' => 'richwood', 'Burgundy Estate' => 'burgundy-estate', 'Flamingo Vlei' => 'flamingo-vlei',
    ];
@endphp
<section id="areas" class="bg-[#f8f9fc] section-py">
    <div class="section-wrap">

        <div class="text-center max-w-2xl mx-auto mb-14 wow fadeInUp" data-wow-duration="0.7s">
            <div class="w-8 h-[2px] bg-[#f6e304] mb-3 mx-auto"></div>
            <span class="text-[#081d3a] text-[11px] font-semibold uppercase tracking-[0.18em]">Areas We Serve</span>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-[#081d3a] tracking-tight leading-tight mt-3 mb-5">
                Cleaning services across<br>Cape Town suburbs.
            </h2>
            <p class="text-[#647082] text-lg font-normal">
                From the Northern Suburbs to the Atlantic Seaboard, our teams are on the road every day.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-10">
            @foreach ($areas as $area)
                @php $slug = $areaSlugs[$area] ?? null; @endphp
                <{{ $slug ? 'a' : 'div' }}
                    @if ($slug) href="{{ route('areas.show', $slug) }}" @endif
                    class="wow fadeInUp flex items-center gap-3 bg-white border border-gray-100 rounded-2xl px-5 py-4 hover:border-[#f6e304] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group" data-wow-duration="0.6s" data-wow-delay="{{ 0.03 * $loop->index }}s">
                    <div class="w-9 h-9 rounded-full bg-[#081d3a]/5 flex items-center justify-center flex-shrink-0 group-hover:bg-[#f6e304]/15 transition-colors duration-200">
                        <svg class="w-4 h-4 text-[#081d3a] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-[#081d3a] font-semibold text-[14px] tracking-tight">{{ $area }}</span>
                </{{ $slug ? 'a' : 'div' }}>
            @endforeach
        </div>

        <div class="text-center wow fadeInUp" data-wow-duration="0.7s">
            <p class="text-[#647082] text-[14px]">
                Servicing Cape Town's Northern Suburbs &amp; Atlantic Seaboard — don't see your area?
                <a href="https://wa.me/27815274711?text=Hi%20SpringKleaners!%20Do%20you%20service%20my%20area%3F" target="_blank" rel="noopener noreferrer" class="text-[#081d3a] font-semibold underline hover:text-[#f6e304] transition-colors">
                    Ask us on WhatsApp
                </a>
            </p>
        </div>

    </div>
</section>

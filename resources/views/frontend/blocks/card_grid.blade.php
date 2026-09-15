@php
    $title = $data['title'] ?? null;
    $subtitle = $data['subtitle'] ?? null;
    $items = $data['items'] ?? [];
@endphp

@if(count($items) > 0)
    <section class="w-full bg-white py-16 sm:py-24 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Section (Desain Tegas Rata Tengah) --}}
            @if(!empty($title) || !empty($subtitle))
                <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-20 flex flex-col items-center">
                    @if(!empty($title))
                        <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-brand-dark">
                            {{ $title }}
                        </h2>
                        <!-- Garis Merah Aksen di Bawah Judul -->
                        <div class="w-20 h-1.5 bg-brand-red mt-5 mb-5"></div>
                    @endif
                    
                    @if(!empty($subtitle))
                        <p class="text-base sm:text-lg text-gray-600 font-medium leading-relaxed">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            @endif

            {{-- Grid Kartu (Lebih kotak, solid, hover efek tegas) --}}
            <div class="flex flex-wrap justify-center gap-5 sm:gap-6">
                @foreach($items as $item)
                    @php
                        $rawIcon = $item['icon'] ?? '';
                        $iconUrl = !empty($rawIcon)
                            ? (str_starts_with($rawIcon, 'http') ? $rawIcon : \Storage::url($rawIcon))
                            : null;
                    @endphp

                    <!-- Card dengan Border Bawah yang menebal saat di-hover -->
                    <div class="w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] xl:w-[calc(16.666%-20px)] flex flex-col items-center text-center p-6 sm:p-8 bg-gray-50 border border-gray-200 border-b-4 hover:border-brand-red shadow-sm hover:shadow-lg transition-all duration-300 group cursor-default">

                        @if($iconUrl)
                            <!-- Kotak Ikon Gelap -> Merah saat hover (Sudut tajam) -->
                            <div class="w-16 h-16 bg-brand-dark flex items-center justify-center mb-6 group-hover:bg-brand-red transition-all duration-300 group-hover:scale-110 shadow-md">
                                <!-- Filter brightness-0 invert memaksa ikon apapun menjadi putih solid -->
                                <img src="{{ $iconUrl }}" alt="{{ $item['title'] ?? '' }}"
                                     class="w-8 h-8 object-contain brightness-0 invert">
                            </div>
                        @endif

                        @if(!empty($item['title']))
                            <h3 class="font-bold text-brand-dark text-base sm:text-lg uppercase tracking-wide mb-3 group-hover:text-brand-red transition-colors">
                                {{ $item['title'] }}
                            </h3>
                        @endif

                        @if(!empty($item['description']))
                            <p class="text-xs sm:text-sm text-gray-500 font-medium leading-relaxed group-hover:text-gray-700 transition-colors">
                                {{ $item['description'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
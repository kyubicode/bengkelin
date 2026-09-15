<section class="hero relative w-full h-[500px] lg:h-[650px] bg-cover bg-center flex items-center overflow-hidden" 
         style="background-image:url('{{ e(\Storage::url($data['image'] ?? '')) }}')">
    
    <!-- Overlay Gelap -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent"></div>

    <!-- Konten Hero -->
    <div class="hero-content relative max-w-7xl mx-auto px-6 lg:px-12 w-full text-white z-10">
        <div class="max-w-2xl space-y-6">
            @if(!empty($data['heading']))
                <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight leading-tight drop-shadow-md">
                    {{ e($data['heading']) }}
                </h1>
            @endif

            @if(!empty($data['subheading']))
                <p class="text-lg lg:text-xl text-gray-200 font-normal leading-relaxed drop-shadow">
                    {{ e($data['subheading']) }}
                </p>
            @endif

            @if(!empty($data['button_text']))
                <div class="pt-2">
                    <a href="{{ e($data['button_url'] ?? '#') }}" 
                       class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out focus:ring-4 focus:ring-indigo-500/20">
                        {{ e($data['button_text']) }}
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
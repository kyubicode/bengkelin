@php
    $slides = $data['slides'] ?? [];
    $interval = $data['interval'] ?? 5000;
@endphp

@if(count($slides) > 0)
    <div x-data="{
            activeSlide: 0,
            slidesCount: {{ count($slides) }},
            interval: {{ $interval }},
            progress: 0,
            timer: null,
            isPaused: false,
            touchStartX: 0,
            touchEndX: 0,
            init() {
                if (this.slidesCount > 1) {
                    this.startAutoplay();
                }
            },
            startAutoplay() {
                this.stopAutoplay();
                const step = 50;
                this.timer = setInterval(() => {
                    if (!this.isPaused) {
                        this.progress += (step / this.interval) * 100;
                        if (this.progress >= 100) {
                            this.next();
                        }
                    }
                }, step);
            },
            stopAutoplay() {
                if (this.timer) clearInterval(this.timer);
            },
            next() {
                this.progress = 0;
                this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
            },
            prev() {
                this.progress = 0;
                this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
            },
            goTo(index) {
                this.progress = 0;
                this.activeSlide = index;
            },
            handleTouchStart(e) {
                this.isPaused = true;
                if (e.changedTouches && e.changedTouches[0]) {
                    this.touchStartX = e.changedTouches[0].clientX;
                }
            },
            handleTouchEnd(e) {
                this.isPaused = false;
                if (e.changedTouches && e.changedTouches[0]) {
                    this.touchEndX = e.changedTouches[0].clientX;
                    const diff = this.touchStartX - this.touchEndX;
                    if (Math.abs(diff) > 40) {
                        if (diff > 0) this.next();
                        else this.prev();
                    }
                }
            }
         }"
         @mouseenter="isPaused = true"
         @mouseleave="isPaused = false"
         @touchstart="handleTouchStart($event)"
         @touchend="handleTouchEnd($event)"
         @keydown.right.window="next()"
         @keydown.left.window="prev()"
         class="relative w-full h-[400px] sm:h-[550px] lg:h-[650px] overflow-hidden bg-brand-dark select-none font-sans group">

        <!-- Progress Bar Top -->
        @if(count($slides) > 1)
            <div class="absolute top-0 left-0 right-0 h-1 bg-white/10 z-40">
                <div class="h-full bg-brand-red transition-all duration-75 ease-linear"
                     :style="`width: ${progress}%`"></div>
            </div>
        @endif

        <!-- Slides Loop -->
        @foreach($slides as $i => $slide)
            @php
                $slideData  = is_array($slide) ? ($slide['data'] ?? $slide) : (array) $slide;

                $heading    = $slideData['heading'] ?? $slideData['title'] ?? $slideData['caption'] ?? null;
                $subheading = $slideData['subheading'] ?? $slideData['subtitle'] ?? $slideData['description'] ?? $slideData['text'] ?? null;
                $buttonText = $slideData['button_text'] ?? $slideData['button_label'] ?? $slideData['btn_text'] ?? $slideData['btn_label'] ?? null;
                $buttonUrl  = $slideData['button_url'] ?? $slideData['button_link'] ?? $slideData['btn_url'] ?? $slideData['url'] ?? '#';

                $rawImg     = $slideData['image'] ?? $slideData['bg_image'] ?? $slideData['photo'] ?? '';
                $imageUrl   = !empty($rawImg) ? (str_starts_with($rawImg, 'http') ? $rawImg : \Storage::url($rawImg)) : '';
            @endphp

            <!-- Pembungkus Slide Utama -->
            <div x-show="activeSlide === {{ $i }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full"
                 x-cloak>

                <!-- 1. GAMBAR BACKGROUND (Ken Burns Zoom Effect) -->
                <div class="absolute inset-0 w-full h-full bg-cover bg-center transition-transform duration-[7000ms] ease-out"
                     :class="activeSlide === {{ $i }} ? 'scale-110' : 'scale-100'"
                     style="background-image: url('{{ $imageUrl }}')">
                </div>

                <!-- 2. OVERLAY GELAP -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent z-10"></div>
                <div class="absolute inset-0 bg-black/20 z-10"></div>

                <!-- 3. KONTEN TEKS & TOMBOL (Staggered Animation) -->
                <div class="relative z-20 max-w-7xl mx-auto h-full flex flex-col justify-center px-4 sm:px-6 lg:px-8 text-white">
                    <div class="max-w-3xl space-y-4 sm:space-y-5">

                        <!-- Heading Utama -->
                    @if(!empty($heading))
                        @php
                            // Pisahkan string menjadi array kata
                            $words = explode(' ', trim(strip_tags($heading)));
                            $totalWords = count($words);
                            
                            if ($totalWords > 2) {
                                // Ambil 2 kata terakhir
                                $lastTwo = array_slice($words, -2);
                                // Ambil sisa kata di awal
                                $firstPart = array_slice($words, 0, $totalWords - 2);
                                
                                // Gabungkan kembali dengan span warna merah di 2 kata terakhir
                                $formattedHeading = implode(' ', $firstPart) . ' <span class="text-red-500">' . implode(' ', $lastTwo) . '</span>';
                            } else {
                                // Jika kata <= 2, warnai semuanya merah
                                $formattedHeading = '<span class="text-red-500">' . $heading . '</span>';
                            }
                        @endphp

                        <h1 x-show="activeSlide === {{ $i }}"
                            x-transition:enter="transition ease-out duration-700 delay-100"
                            x-transition:enter-start="opacity-0 translate-y-6"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="text-4xl sm:text-5xl lg:text-[64px] font-black uppercase tracking-wider leading-[1.1] text-white drop-shadow-md">
                            {!! $formattedHeading !!}
                        </h1>
                    @endif
                        <!-- Subheading -->
                        @if(!empty($subheading))
                            <p x-show="activeSlide === {{ $i }}"
                               x-transition:enter="transition ease-out duration-700 delay-200"
                               x-transition:enter-start="opacity-0 translate-y-6"
                               x-transition:enter-end="opacity-100 translate-y-0"
                               class="text-base sm:text-lg lg:text-xl text-white font-normal leading-relaxed drop-shadow max-w-2xl">
                                {{ $subheading }}
                            </p>
                        @endif

                        <!-- Tombol Akses -->
                        @if(!empty($buttonText))
                            <div x-show="activeSlide === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-700 delay-300"
                                 x-transition:enter-start="opacity-0 translate-y-6"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="pt-4">
                                <a href="{{ e($buttonUrl) }}"
                                   class="inline-flex items-center justify-center px-8 py-3.5 text-sm sm:text-base font-semibold text-white bg-brand-red hover:bg-red-800 active:scale-95 shadow-md transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out focus:ring-4 focus:ring-red-900/20 rounded-0">
                                    {{ e($buttonText) }}
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

        @if(count($slides) > 1)
            <!-- Tombol Navigasi Kiri & Kanan -->
            <button @click="prev()" 
                    type="button"
                    aria-label="Slide sebelumnya"
                    class="absolute left-3 sm:left-6 lg:left-8 top-1/2 -translate-y-1/2 p-2.5 sm:p-3.5 rounded-full bg-brand-dark/60 hover:bg-brand-red text-white/90 hover:text-white backdrop-blur-md opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-all duration-300 focus:outline-none z-30 active:scale-90">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button @click="next()" 
                    type="button"
                    aria-label="Slide selanjutnya"
                    class="absolute right-3 sm:right-6 lg:right-8 top-1/2 -translate-y-1/2 p-2.5 sm:p-3.5 rounded-full bg-brand-dark/60 hover:bg-brand-red text-white/90 hover:text-white backdrop-blur-md opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-all duration-300 focus:outline-none z-30 active:scale-90">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Indikator Dots / Pagination -->
            <div class="absolute bottom-5 sm:bottom-6 left-1/2 -translate-x-1/2 flex items-center space-x-2 sm:space-x-2.5 z-30">
                @foreach($slides as $i => $slide)
                    <button @click="goTo({{ $i }})"
                            type="button"
                            aria-label="Ke slide {{ $i + 1 }}"
                            class="h-2 sm:h-2.5 rounded-full transition-all duration-300 focus:outline-none"
                            :class="activeSlide === {{ $i }} ? 'w-6 sm:w-8 bg-brand-red shadow-lg shadow-red-900/50' : 'w-2 sm:w-2.5 bg-white/40 hover:bg-white/70'">
                    </button>
                @endforeach
            </div>
        @endif

    </div>
@endif
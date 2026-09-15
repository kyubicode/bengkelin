@php
    $gallery = \App\Models\Gallery::find($data['gallery_id'] ?? null);
@endphp

@if($gallery)
    @php
        $photos = is_array($gallery->image_path) ? $gallery->image_path : [];
        $photoUrls = array_map(fn($photo) => Storage::url($photo), $photos);
    @endphp

    @if(count($photoUrls))
        <div x-data="{ 
                isOpen: false, 
                currentIndex: 0, 
                photos: {{ json_encode($photoUrls) }},
                title: '{{ e($gallery->title) }}',
                next() { this.currentIndex = (this.currentIndex + 1) % this.photos.length },
                prev() { this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length }
               }"
             @keydown.escape.window="isOpen = false"
             @keydown.right.window="if(isOpen) next()"
             @keydown.left.window="if(isOpen) prev()"
             class="my-8 space-y-4">

            <!-- Judul Galeri (Opsional jika ingin senada) -->
            @if(!empty($gallery->title))
                <div class="border-l-4 border-brand-red pl-3 mb-6">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white bg-brand-dark px-2.5 py-1 inline-block">
                        Dokumentasi Galeri
                    </span>
                    <h2 class="text-lg sm:text-xl font-black text-brand-dark mt-2 tracking-tight uppercase">
                        {{ $gallery->title }}
                    </h2>
                </div>
            @endif

            <!-- Grid Foto (Gaya Industrial: tajam, border tegas, tanpa rounded-2xl) -->
            <div class="gallery-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photoUrls as $index => $photoUrl)
                    <div @click="isOpen = true; currentIndex = {{ $index }}"
                         class="group relative overflow-hidden rounded-none border border-gray-300 shadow-sm hover:shadow-md transition-all duration-300 bg-gray-100 aspect-square cursor-pointer">

                        <!-- Gambar -->
                        <img src="{{ e($photoUrl) }}"
                             alt="{{ e($gallery->title) }}"
                             loading="lazy"
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-out">

                        <!-- Hover Overlay Industrial (Background Brand Dark) -->
                        <div class="absolute inset-0 bg-brand-dark/80 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-4">
                            <div class="flex justify-end">
                                <span class="bg-brand-red text-white p-2 shadow-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-white text-xs font-black uppercase tracking-wider truncate">
                                    {{ $gallery->title }}
                                </p>
                                <span class="text-[10px] text-brand-red font-bold uppercase tracking-widest mt-0.5 block">Klik untuk memperbesar</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Lightbox / Modal Fullscreen (Tema Industrial) -->
            <template x-teleport="body">
                <div x-show="isOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4 sm:p-8"
                     role="dialog"
                     aria-modal="true">

                    <!-- Tombol Close (X) -->
                    <button @click="isOpen = false" 
                            type="button"
                            aria-label="Tutup foto"
                            class="absolute top-5 right-5 text-white bg-brand-dark hover:bg-brand-red p-3 rounded-none transition z-50 focus:outline-none border border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Tombol Prev -->
                    <button x-show="photos.length > 1" 
                            @click="prev()" 
                            type="button"
                            aria-label="Foto sebelumnya"
                            class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 text-white bg-brand-dark hover:bg-brand-red p-3.5 rounded-none transition z-50 focus:outline-none border border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <!-- Container Gambar Utama -->
                    <div class="relative max-w-5xl max-h-[85vh] flex flex-col items-center justify-center select-none"
                         @click.outside="isOpen = false">
                        <img :src="photos[currentIndex]" 
                             :alt="title"
                             class="max-w-full max-h-[75vh] object-contain rounded-none border border-gray-800 shadow-2xl">

                        <!-- Detail & Counter di Bawah Foto -->
                        <div class="mt-4 text-center space-y-1">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider" x-text="title"></h3>
                            <p class="text-[10px] text-gray-400 font-mono uppercase tracking-widest">
                                FOTO <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Next -->
                    <button x-show="photos.length > 1" 
                            @click="next()" 
                            type="button"
                            aria-label="Foto selanjutnya"
                            class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 text-white bg-brand-dark hover:bg-brand-red p-3.5 rounded-none transition z-50 focus:outline-none border border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                </div>
            </template>

        </div>
    @endif
@endif
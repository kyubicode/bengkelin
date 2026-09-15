@extends('frontend.layout')

@section('meta_title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
    <!-- Background Terang dengan Nuansa Abu-abu -->
    <div class="py-10 md:py-16 lg:py-20 bg-gray-50 min-h-[calc(100vh-16rem)] flex flex-col justify-start">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header (Aksen Garis Merah Kiri: Tegas, Elegan & Minimalis) -->
            <header class="mb-10 sm:mb-12 border-l-4 sm:border-l-8 border-brand-red pl-4 sm:pl-6 py-1 flex flex-col gap-3">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-dark uppercase tracking-tight leading-none">
                    {{ $page->title }}
                </h1>
                
                @if(!empty($page->subtitle))
                    <p class="text-base sm:text-lg font-medium text-gray-600 max-w-3xl leading-relaxed">
                        {{ $page->subtitle }}
                    </p>
                @endif
            </header>

     <!-- Main Content Body (Block Builder) -->
        <div class="w-full max-w-none text-gray-800 prose prose-red prose-lg lg:prose-xl">
            @if(is_array($page->content))
                @foreach($page->content as $block)
                    @includeIf('frontend.blocks.'.$block['type'], ['data' => $block['data'] ?? []])
                @endforeach
            @else
                {!! $page->content ?? '' !!}
            @endif
        </div>

            <!-- Dynamic Modules Integration -->
            @if(in_array($page->module_type ?? null, ['booking', 'booking_tracker', 'gallery']))
                <div class="mt-12 lg:mt-16 border-0 pt-0 lg:pt-0">
                    @if($page->module_type === 'booking')
                        <!-- Wrapper Form Booking -->
                        <div class="w-full bg-white p-6 sm:p-8 lg:p-10 shadow-sm border-t-4 border-brand-red">
                            @livewire(\App\Http\Livewire\BookingWizard::class)
                        </div>

                    @elseif($page->module_type === 'booking_tracker')
                        <!-- Wrapper Tracker -->
                        <div class="w-full bg-white p-6 sm:p-8 lg:p-10 shadow-sm border-t-4 border-brand-dark">
                            @livewire(\App\Http\Livewire\BookingTracker::class)
                        </div>

                    @elseif($page->module_type === 'gallery')
                        <div class="w-full">
                            <!-- Judul Galeri (Mengikuti bahasa visual Header Utama) -->
                            <div class="border-l-4 border-brand-red pl-3 sm:pl-4 mb-6 sm:mb-8">
                                <h2 class="text-2xl sm:text-3xl font-black text-brand-dark tracking-tight uppercase">
                                    Galeri <span class="text-brand-red">Foto</span>
                                </h2>
                            </div>
                            
                            <!-- Frame Galeri Rapat -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5 sm:gap-2 bg-brand-dark p-1.5 sm:p-2">
                                @foreach(\App\Models\Gallery::where('is_active', true)->orderBy('order')->get() as $photo)
                                    <div class="group relative overflow-hidden aspect-square bg-gray-900 cursor-pointer">
                                        <img
                                            src="{{ $photo->image_url }}"
                                            alt="{{ $photo->title ?? 'Galeri Foto Bengkelin' }}"
                                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 ease-out group-hover:scale-110"
                                            loading="lazy"
                                        >
                                        
                                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/95 via-brand-dark/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        
                                        @if(!empty($photo->title))
                                            <div class="absolute inset-x-0 bottom-0 p-4 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                <p class="text-sm sm:text-base font-black text-white uppercase tracking-wider leading-snug border-l-2 border-brand-red pl-2 drop-shadow-md">
                                                    {{ $photo->title }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            @endif

        </div>
    </div>
@endsection
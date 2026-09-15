<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags & Performance Optimization -->
    <title>@yield('meta_title', 'BENGKELIN - Authorized Motul Partner Garage & Layanan Servis Profesional')</title>
    <meta name="description" content="@yield('meta_description', 'Bengkelin adalah Authorized Motul Partner Garage terpercaya yang menyediakan layanan servis motor profesional.')">
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio,forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1f1f1f', /* Warna gelap header */
                            red: '#cc1e1e',  /* Warna merah khas tombol & bar atas */
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased flex flex-col min-h-screen selection:bg-brand-red selection:text-white">

    <!-- INISIALISASI VARIABEL CMS LUNA -->
    @php
        $allNavs = $globalNavigations ?? collect();
        // Mencari menu yang mengandung kata 'booking' untuk dijadikan tombol CTA merah
        $ctaNav = $allNavs->first(fn($nav) => str_contains(strtolower($nav->label), 'booking') || str_contains(strtolower($nav->label), 'appointment'));
        // Sisanya masuk ke menu navigasi utama
        $mainNavs = $ctaNav
            ? $allNavs->reject(fn($nav) => $nav->id === $ctaNav->id)
            : $allNavs;
    @endphp

    <!-- 1. TOP BAR (Merah) -->
    <div class="bg-brand-red text-white py-1.5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-xs sm:text-sm font-medium">
        <!-- Social Icons -->
        <div class="flex space-x-3 items-center">
            <!-- Instagram Icon -->
            <a href="#" class="hover:opacity-80 transition-opacity border border-white/30 rounded-full p-1">
                <img src="http://127.0.0.1:8000/storage/galleries/01M2FBB95065S65GFB20HRZ67C.png" alt="Instagram" class="w-5 h-5 object-contain brightness-0 invert">
            </a>
            
            <!-- Facebook Icon -->
            <a href="#" class="hover:opacity-80 transition-opacity border border-white/30 rounded-full p-1">
                <img src="http://127.0.0.1:8000/storage/galleries/01M2FBN2XJSTYSHPGQTX0TPEDC.png" alt="Facebook" class="w-5 h-5 object-contain brightness-0 invert">
            </a>
        </div>
            <!-- Jam Operasional -->
           <div class="font-extrabold">Senin-Jumat 08:30 - 17:00</div>
        </div>
    </div>

    <!-- 2. HEADER LOGO & CTA (Gelap) -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-brand-dark py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <!-- Logo BENGKELIN -->
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('bengkelin-logo.png') }}" alt="Bengkelin Logo" class="h-10 sm:h-12 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="font-extrabold text-2xl tracking-tight text-white uppercase">
                        BENGKEL<span class="text-brand-red">IN</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] text-gray-400 tracking-widest uppercase">
                       AUTHORIZED MOTUL PARTNER GARAGE
                    </span>
                </div>
            </a>

            <!-- Search & Button Area -->
           <div class="hidden md:flex items-center gap-6">
                <!-- WhatsApp Icon -->
                <button class="transition hover:opacity-80">
                    <img src="http://127.0.0.1:8000/storage/galleries/01M2J9X8NFK2X28Z57GKEQA14E.png" alt="WhatsApp" class="w-9 h-9 object-contain">
                </button>

                <!-- CTA Button dari Database -->
                @if($ctaNav)
                    <a href="{{ $ctaNav->computed_url }}" target="{{ $ctaNav->target }}" class="bg-brand-red hover:bg-red-800 text-white px-6 py-2.5 font-bold text-sm rounded-0 shadow-md transition-colors">
                        {{ $ctaNav->label }}
                    </a>
                @endif
            </div>
            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white p-2" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Nav -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white mt-4 border-t border-gray-200">
            <div class="px-4 pt-2 pb-4 space-y-1 shadow-lg">
                @foreach($mainNavs as $nav)
                    <a href="{{ $nav->computed_url }}" target="{{ $nav->target }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-800 hover:text-brand-red hover:bg-gray-50">
                        {{ $nav->label }}
                    </a>
                @endforeach
                
                @if($ctaNav)
                    <a href="{{ $ctaNav->computed_url }}" target="{{ $ctaNav->target }}" class="block text-center mt-4 bg-brand-red hover:bg-red-800 text-white px-6 py-3 font-bold text-sm rounded shadow-md">
                        {{ $ctaNav->label }}
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- 3. NAVIGATION BAR (Putih) Desktop -->
    <nav class="bg-white border-b border-gray-200 hidden md:flex justify-center shadow-sm relative">
        <div class="flex items-center text-sm font-bold text-gray-700">
            @foreach($mainNavs as $nav)
                <!-- Pengecekan aktif atau tidak berdasarkan URL -->
                @php
                    // Logika sederhana untuk ngecek apakah URL menu = halaman saat ini
                    $isActive = request()->url() == rtrim($nav->computed_url, '/') || 
                               (request()->path() == '/' && $nav->computed_url == url('/'));
                @endphp

                <a href="{{ $nav->computed_url }}" target="{{ $nav->target }}" 
                   class="px-6 py-4 transition-colors duration-200 {{ $isActive ? 'bg-brand-red text-white hover:bg-red-800' : 'hover:text-brand-red hover:bg-gray-50' }}">
                    {{ $nav->label }}
                </a>
            @endforeach
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow w-full">
        <!-- ==========================================
             CONTOH HERO SECTION
             Pindahkan ini ke file view/blade Anda jika diperlukan 
             ========================================== -->
        <!-- Akhir Contoh Hero Section -->

        @yield('content')
    </main>

    <!-- 4. FOOTER (Dipertahankan gaya gelap yang rapi) -->
    <footer class="bg-brand-dark text-gray-400 mt-auto border-t-4 border-brand-red">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('bengkelin-logo.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-xl font-extrabold text-white tracking-tight uppercase">BENGKEL<span class="text-brand-red">IN</span></span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Authorized Motul Partner Garage terdepan yang menghadirkan standar perawatan mesin profesional.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase text-sm border-l-2 border-brand-red pl-2">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm font-medium">
                        @foreach(array_slice($globalNavigations->toArray() ?? [], 0, 4) as $nav)
                            <li>
                                <a href="{{ $nav['computed_url'] ?? '#' }}" class="hover:text-brand-red transition-colors flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                                    {{ $nav['label'] ?? '' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase text-sm border-l-2 border-brand-red pl-2">Hubungi Kami</h4>
                    <p class="text-sm mb-2">Dapatkan konsultasi servis dan informasi suku cadang resmi.</p>
                    <a href="mailto:sigits@luna.dev" class="text-sm text-brand-red hover:text-white transition">contactme@bengkelin.com</a>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-700 text-xs text-center flex flex-col md:flex-row justify-between items-center gap-4">
                <p>&copy; {{ date('Y') }} BENGKELIN - Authorized Motul Partner Garage. All rights reserved.</p>
                <p>Powered by <span class="text-white font-bold">LunaCMS</span></p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
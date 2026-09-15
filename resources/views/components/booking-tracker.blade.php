<div class="max-w-3xl mx-auto space-y-8">

    {{-- FORM PENCARIAN --}}
    <!-- Menggunakan border atas tebal warna merah untuk kesan tegas -->
    <div class="bg-white border-l-4 border-brand-red shadow-sm p-6 sm:p-8">
        <form wire:submit.prevent="search" class="space-y-4">
            <label for="bookingCode" class="block text-sm font-black text-brand-dark uppercase tracking-wider">
                Cek Status Booking
            </label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <!-- Input Kotak Tajam (rounded-none) -->
                    <input
                        id="bookingCode"
                        type="text"
                        wire:model="bookingCode"
                        placeholder="Masukkan kode booking (Mis: BKG-2026...)"
                        class="w-full pl-11 pr-4 py-3 rounded-none border-gray-300 bg-gray-50 shadow-inner focus:border-brand-red focus:ring-1 focus:ring-brand-red text-sm font-medium text-brand-dark placeholder-gray-400 transition-colors"
                    >
                </div>
                <!-- Tombol Kotak Solid -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="search"
                    class="inline-flex items-center justify-center rounded-none bg-brand-dark px-8 py-3 text-white font-bold text-sm uppercase tracking-wide hover:bg-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2 disabled:opacity-60 transition-colors shrink-0"
                >
                    <span wire:loading.remove wire:target="search">Lacak</span>
                    <span wire:loading wire:target="search" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mencari...
                    </span>
                </button>
            </div>
            @error('bookingCode')
                <p class="text-xs font-bold text-brand-red flex items-center gap-1.5 uppercase mt-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </form>
    </div>

    {{-- HASIL: TIDAK DITEMUKAN --}}
    @if ($searched && ! $booking)
        <div class="bg-gray-50 border-l-4 border-brand-red p-4 sm:p-5 flex items-start gap-3 shadow-sm">
            <svg class="w-6 h-6 text-brand-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h4 class="font-black text-brand-dark uppercase text-sm mb-1">Booking Tidak Ditemukan</h4>
                <p class="text-sm text-gray-600">Kode booking <strong class="text-brand-red">{{ $bookingCode }}</strong> tidak valid. Silakan periksa kembali kode Anda.</p>
            </div>
        </div>
    @endif

    {{-- HASIL: DITEMUKAN --}}
    @if ($booking)
        <div class="border-t-4 border-brand-dark bg-white shadow-md overflow-hidden transition-all">

            {{-- Header Hasil --}}
            <div class="bg-gray-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-200 gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Kode Booking</p>
                        <p class="text-xl font-black text-brand-dark tracking-tight">{{ $booking->booking_code }}</p>
                    </div>

                    {{-- NOMOR ANTRIAN: ditarik dari controller --}}
                    @if (!empty($booking->queue_number))
                        <div class="sm:border-l-2 sm:border-gray-300 sm:pl-6">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">No. Antrian</p>
                            <p class="text-xl font-black text-brand-red tracking-tight">
                                {{ $booking->queue_number }}
                            </p>
                        </div>
                    @endif
                </div>
                <button
                    wire:click="resetSearch"
                    class="inline-flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-dark border-2 border-brand-dark hover:bg-brand-dark hover:text-white px-4 py-2 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cek Kode Lain
                </button>
            </div>

            {{-- Timeline Status (Desain Kotak Mekanikal) --}}
            <div class="px-6 py-8 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between">
                    @foreach ($this->statusSteps as $step)
                        <div class="flex-1 flex flex-col items-center relative group">
                            {{-- Connector Line --}}
                            @if (!$loop->last)
                                <div class="absolute left-1/2 top-3 w-full h-1 {{ $step['done'] && !$step['active'] ? 'bg-brand-dark' : 'bg-gray-200' }}"></div>
                            @endif

                            {{-- Indicator (Square instead of circle for industrial look) --}}
                            <div class="z-10 w-7 h-7 flex items-center justify-center transition-all duration-300 transform group-hover:scale-110
                                {{ $step['cancelled'] ?? false
                                    ? 'bg-brand-red rotate-45'
                                    : ($step['active']
                                        ? 'bg-brand-red shadow-[0_0_10px_rgba(220,38,38,0.5)]'
                                        : ($step['done'] ? 'bg-brand-dark' : 'bg-gray-200 border-2 border-gray-300')) }}">

                                @if ($step['done'] && !($step['cancelled'] ?? false))
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @elseif ($step['cancelled'] ?? false)
                                    <svg class="w-4 h-4 text-white -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>

                            {{-- Step Label --}}
                            <span class="mt-4 text-[11px] sm:text-xs text-center px-1 uppercase tracking-wider transition-colors
                                {{ $step['active'] ? 'font-black text-brand-red' : ($step['done'] ? 'font-bold text-brand-dark' : 'font-medium text-gray-400') }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Detail Booking --}}
            <div class="px-6 py-6 space-y-8 text-sm">

                <!-- Grid Informasi Utama -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 border-l-4 border-gray-300 hover:border-brand-red transition-colors">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Pelanggan</p>
                        <p class="font-black text-brand-dark uppercase">{{ $booking->customer_name }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 border-l-4 border-gray-300 hover:border-brand-red transition-colors">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">No. Polisi</p>
                        <p class="font-black text-brand-dark uppercase tracking-widest text-base">{{ $booking->license_plate }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 border-l-4 border-gray-300 hover:border-brand-red transition-colors">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Kendaraan</p>
                        <p class="font-black text-brand-dark uppercase">
                            {{ $booking->vehicle_brand }} {{ $booking->vehicle_model }}
                        </p>
                        <p class="text-xs font-semibold text-gray-500 mt-1 uppercase">
                            {{ $booking->vehicle_type }} &bull; {{ $booking->transmission }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 border-l-4 border-gray-300 hover:border-brand-red transition-colors">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Metode Servis</p>
                        <p class="font-black text-brand-dark uppercase">
                            {{ match($booking->service_method) {
                                'workshop' => 'Datang ke Bengkel',
                                'home_service' => 'Servis di Rumah',
                                'pickup' => 'Jemput Kendaraan',
                                default => $booking->service_method,
                            } }}
                        </p>
                    </div>

                    <div class="bg-brand-dark p-4 border-l-4 border-brand-red sm:col-span-2 text-white">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jadwal Servis</p>
                        <p class="font-black flex items-center gap-2 text-base">
                            <svg class="w-5 h-5 text-brand-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                            <span class="text-gray-500 mx-1">&bull;</span>
                            <span class="text-brand-red">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} WIB</span>
                        </p>
                    </div>

                    @if ($booking->service_address)
                        <div class="bg-gray-50 p-4 border-l-4 border-gray-300 sm:col-span-2">
                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Alamat Tujuan</p>
                            <p class="font-bold text-brand-dark leading-relaxed">{{ $booking->service_address }}</p>
                        </div>
                    @endif
                </div>

                {{-- Layanan yang dipilih --}}
                @if ($booking->services->isNotEmpty())
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-black text-brand-dark uppercase tracking-wider mb-4 border-l-4 border-brand-red pl-2">Layanan yang Dipilih</h4>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach ($booking->services as $service)
                                <span class="inline-flex items-center gap-1.5 bg-white border-2 border-gray-200 text-brand-dark px-3 py-1.5 text-[11px] font-black uppercase tracking-wider hover:border-brand-dark transition-colors">
                                    <svg class="w-3.5 h-3.5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    {{ $service->name ?? $service->title ?? 'Layanan #' . $service->id }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Keluhan / Hasil Assessment --}}
                @if ($booking->symptoms->isNotEmpty())
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-black text-brand-dark uppercase tracking-wider mb-4 border-l-4 border-brand-red pl-2">Keluhan & Jawaban Assessment</h4>
                        <div class="space-y-3">
                            @foreach ($booking->symptoms as $symptom)
                                <div class="bg-white border border-gray-200 p-4 shadow-sm">
                                    <p class="font-black text-brand-dark text-sm uppercase">
                                        &bull; {{ $symptom->name ?? $symptom->title ?? 'Gejala #' . $symptom->id }}
                                    </p>
                                    @if ($symptom->pivot->assessment_answer)
                                        <p class="text-gray-700 font-medium mt-2 pl-3 border-l-2 border-brand-red text-sm">
                                            {{ $symptom->pivot->assessment_answer }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Catatan --}}
                @if ($booking->notes)
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-black text-brand-dark uppercase tracking-wider mb-4 border-l-4 border-brand-red pl-2">Catatan Tambahan</h4>
                        <div class="bg-gray-100 border-l-4 border-brand-dark p-4 text-sm font-medium text-gray-700 italic">
                            "{{ $booking->notes }}"
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
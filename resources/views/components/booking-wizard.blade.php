<div class="w-full bg-white border-l-4 border-brand-red shadow-md p-6 sm:p-8 my-4 space-y-6 rounded-none">

    <!-- Flash Success Message -->
    @if (session()->has('success'))
        <div class="mb-5 p-4 bg-emerald-50 border-l-4 border-emerald-600 text-emerald-950 flex items-center space-x-3 text-sm animate-fade-in rounded-none">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-bold uppercase text-xs tracking-wider">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header & Progress Indicator -->
    <div class="mb-6 pb-5 border-b-2 border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-white bg-brand-dark px-2.5 py-1 inline-block">
                    Booking Servis Eksklusif
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-brand-dark mt-2 tracking-tight uppercase">
                    Formulir Pendaftaran Servis
                </h1>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-gray-400 block tracking-widest">TAHAP</span>
                <span class="text-lg font-black text-brand-dark font-mono">
                    0{{ $currentStep }} <span class="text-xs font-bold text-gray-400">/ 09</span>
                </span>
            </div>
        </div>

        <!-- Progress Bar (Industrial Style) -->
        <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 mt-4">
            <span>Progress Pengisian</span>
            <span class="text-brand-red font-black">{{ round(($currentStep / 9) * 100) }}% SELESAI</span>
        </div>
        <div class="w-full bg-gray-200 h-2.5 rounded-none overflow-hidden p-0.5 border border-gray-300">
            <div class="bg-brand-red h-full transition-all duration-300" style="width: {{ ($currentStep / 9) * 100 }}%"></div>
        </div>
    </div>

    <form wire:submit.prevent="submitBooking">

        <!-- Tahap 01 - Kendaraan -->
        @if ($currentStep == 1)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Informasi Kendaraan</h2>
                    <p class="text-xs font-medium text-gray-500">Tentukan jenis kendaraan dan spesifikasi dasarnya.</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Tipe Kendaraan</label>
                    <select wire:model="vehicle_type" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-3 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition">
                        <option value="">-- PILIH TIPE KENDARAAN --</option>
                        @foreach ($vehicleTypesList as $type)
                            <option value="{{ $type->name }}">{{ strtoupper($type->name) }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Transmisi</label>
                    <select wire:model="transmission" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-3 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition">
                        <option value="">-- PILIH TRANSMISI --</option>
                        <option value="Automatic">AUTOMATIC (MATIC)</option>
                        <option value="Manual">MANUAL</option>
                    </select>
                    @error('transmission') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Merk <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" wire:model="brand" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400" placeholder="Contoh: Toyota">
                        @error('brand') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Model <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" wire:model="model_name" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400" placeholder="Contoh: Avanza">
                        @error('model_name') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Tahun <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" wire:model="year" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400" placeholder="2022">
                        @error('year') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        @endif

        <!-- Tahap 02 - Layanan -->
        @if ($currentStep == 2)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Pilih Layanan Servis Utama</h2>
                    <p class="text-xs font-medium text-gray-500">Anda dapat memilih lebih dari satu layanan sesuai kebutuhan kendaraan.</p>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @foreach ($servicesList as $srv)
                        <label class="flex items-center space-x-3 p-4 border-2 border-gray-200 rounded-none cursor-pointer hover:border-brand-dark hover:bg-gray-50 transition group">
                            <input type="checkbox" wire:model="selected_services" value="{{ $srv->id }}" class="rounded-none text-brand-red focus:ring-brand-red h-5 w-5 border-gray-400">
                            <span class="text-sm font-black text-brand-dark group-hover:text-brand-red uppercase tracking-wide">{{ $srv->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selected_services') <span class="text-brand-red text-xs block font-bold uppercase">{{ $message }}</span> @enderror
                @error('selected_services.*') <span class="text-brand-red text-xs block font-bold uppercase">Salah satu layanan yang dipilih tidak valid.</span> @enderror
            </div>
        @endif

        <!-- Tahap 03 - Keluhan -->
        @if ($currentStep == 3)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Keluhan Kendaraan</h2>
                    <p class="text-xs font-medium text-gray-500">Pilih area dan gejala yang dirasakan. Anda bisa memilih lebih dari satu gejala, bahkan dari beberapa area sekaligus.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach ($complaintCategories as $cat)
                        @php
                            $count = $this->selectedCountByCategory[$cat] ?? 0;
                            $isActive = $active_category === $cat;
                        @endphp
                        <button type="button"
                            wire:key="category-{{ $cat }}"
                            wire:click="toggleCategory('{{ $cat }}')"
                            class="p-4 border-2 rounded-none text-center transition
                                {{ $isActive ? 'border-brand-red bg-red-50' : 'border-gray-200 hover:border-brand-dark hover:bg-gray-50' }}">
                            <span class="text-sm font-black text-brand-dark uppercase block">{{ $cat }}</span>
                            <span class="text-xs font-semibold {{ $count > 0 ? 'text-brand-red' : 'text-gray-500' }}">
                                {{ $count }} gejala dipilih
                            </span>
                        </button>
                    @endforeach
                </div>

                @if ($active_category)
                    <div class="pt-2">
                        <h3 class="text-sm font-black uppercase text-brand-dark mb-3">{{ $active_category }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($symptomsList->where('category', $active_category) as $symp)
                                <label wire:key="symptom-{{ $symp->id }}"
                                       class="flex items-center space-x-3 p-4 border-2 rounded-none cursor-pointer transition
                                    {{ in_array($symp->id, $selected_symptoms) ? 'border-brand-red bg-red-50' : 'border-gray-200 hover:border-brand-dark hover:bg-gray-50' }}">
                                    <input type="checkbox"
                                           wire:model.live="selected_symptoms"
                                           value="{{ $symp->id }}"
                                           class="h-5 w-5 text-brand-red rounded-none border-gray-400 focus:ring-brand-red">
                                    <span class="text-sm font-bold text-brand-dark uppercase">{{ $symp->symptom_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (count($selected_symptoms) > 0)
                    <div class="bg-gray-50 border-t-4 border-brand-dark p-4 mt-2">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                            Total {{ count($selected_symptoms) }} Gejala Dipilih
                        </span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($this->selectedSymptomsDetail as $symp)
                                <span class="text-[11px] font-bold uppercase bg-white border border-gray-300 px-2 py-1">
                                    {{ $symp->symptom_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @error('selected_symptoms') <span class="text-brand-red text-xs block font-bold uppercase">{{ $message }}</span> @enderror
                @error('selected_symptoms.*') <span class="text-brand-red text-xs block font-bold uppercase">Salah satu gejala yang dipilih tidak valid.</span> @enderror
            </div>
        @endif

        <!-- Tahap 04 - Assessment -->
        @if ($currentStep == 4)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Assessment (Pemeriksaan Awal)</h2>
                    <p class="text-xs font-medium text-gray-500">Jawab pertanyaan pemeriksaan awal untuk setiap gejala yang Anda pilih.</p>
                </div>

                @forelse ($this->selectedSymptomsDetail as $symp)
                    <div class="border-2 border-gray-200 p-4">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ $symp->category }}</p>
                        <p class="text-sm font-black text-brand-dark uppercase mb-2">{{ $symp->symptom_name }}</p>

                        @if ($symp->assessment_question)
                            <p class="text-xs font-medium text-gray-500 mb-2">{{ $symp->assessment_question }}</p>
                        @endif

                        <textarea
                            wire:model="assessment_answers.{{ $symp->id }}"
                            rows="3"
                            class="w-full bg-gray-50 border border-gray-300 rounded-none p-3.5 text-sm font-medium text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400"
                            placeholder="Tuliskan detail kondisi terkait gejala ini..."></textarea>
                        @error('assessment_answers.' . $symp->id) <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                @empty
                    <p class="text-xs font-medium text-gray-500 italic border-2 border-dashed border-gray-300 p-4 text-center">
                        Tidak ada gejala yang dipilih pada tahap sebelumnya (servis berkala saja).
                    </p>
                @endforelse
            </div>
        @endif

        <!-- Tahap 05 - Metode Servis -->
        @if ($currentStep == 5)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Metode Pengerjaan Servis</h2>
                    <p class="text-xs font-medium text-gray-500">Pilih bagaimana cara servis ingin dilakukan.</p>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <label class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-none cursor-pointer hover:border-brand-dark hover:bg-gray-50 transition group">
                        <input type="radio" wire:model="service_method" value="workshop" class="text-brand-red focus:ring-brand-red h-5 w-5 mt-0.5 border-gray-400">
                        <div>
                            <span class="text-sm font-black text-brand-dark uppercase tracking-wider block group-hover:text-brand-red">Datang ke Bengkel</span>
                            <span class="text-xs font-medium text-gray-500">Bawa kendaraan langsung ke lokasi bengkel kami.</span>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-none cursor-pointer hover:border-brand-dark hover:bg-gray-50 transition group">
                        <input type="radio" wire:model="service_method" value="home_service" class="text-brand-red focus:ring-brand-red h-5 w-5 mt-0.5 border-gray-400">
                        <div>
                            <span class="text-sm font-black text-brand-dark uppercase tracking-wider block group-hover:text-brand-red">Home Service</span>
                            <span class="text-xs font-medium text-gray-500">Mekanik kami datang langsung ke lokasi Anda.</span>
                        </div>
                    </label>

                    <label class="flex items-start space-x-3 p-4 border-2 border-gray-200 rounded-none cursor-pointer hover:border-brand-dark hover:bg-gray-50 transition group">
                        <input type="radio" wire:model="service_method" value="pickup" class="text-brand-red focus:ring-brand-red h-5 w-5 mt-0.5 border-gray-400">
                        <div>
                            <span class="text-sm font-black text-brand-dark uppercase tracking-wider block group-hover:text-brand-red">Jemput Kendaraan</span>
                            <span class="text-xs font-medium text-gray-500">Tim kami menjemput dan mengantar kembali kendaraan Anda.</span>
                        </div>
                    </label>
                </div>
                @error('service_method') <span class="text-brand-red text-xs block font-bold uppercase">{{ $message }}</span> @enderror
            </div>
        @endif

        <!-- Tahap 06 - Jadwal (DIPERBAIKI: tambah indikator ketersediaan teknisi live) -->
        @if ($currentStep == 6)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Jadwal Kedatangan / Penjemputan</h2>
                    <p class="text-xs font-medium text-gray-500">Tentukan tanggal dan waktu yang pas untuk Anda.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" wire:model="booking_date" min="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-3 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition">
                        @error('booking_date') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Jam</label>
                        <input type="time" wire:model="booking_time" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-3 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition">
                        @error('booking_time') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Indikator ketersediaan teknisi, muncul otomatis saat tanggal & jam terisi --}}
                @if ($booking_date && $booking_time)
                    <div wire:loading.remove wire:target="booking_date, booking_time">
                        @if ($availableTechnicianCount === null)
                            {{-- belum sempat dicek --}}
                        @elseif ($availableTechnicianCount > 0)
                            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-600 flex items-center space-x-3">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-800">
                                    {{ $availableTechnicianCount }} Teknisi Tersedia Pada Jadwal Ini
                                </span>
                            </div>
                        @else
                            <div class="p-4 bg-red-50 border-l-4 border-brand-red flex items-center space-x-3">
                                <svg class="w-5 h-5 text-brand-red flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-xs font-black uppercase tracking-wider text-brand-red">
                                    Semua Teknisi Penuh, Silakan Pilih Jadwal Lain
                                </span>
                            </div>
                        @endif
                    </div>

                    <div wire:loading wire:target="booking_date, booking_time" class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Mengecek ketersediaan teknisi...
                    </div>
                @endif
            </div>
        @endif

        <!-- Tahap 07 - Data Anda -->
        @if ($currentStep == 7)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Informasi Kontak & Kendaraan</h2>
                    <p class="text-xs font-medium text-gray-500">Lengkapi data diri agar kami bisa menghubungi Anda.</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" wire:model="customer_name" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition">
                    @error('customer_name') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                    <input type="text" wire:model="customer_phone" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400" placeholder="08xxxxxxxxxx">
                    @error('customer_phone') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Nomor Polisi Kendaraan</label>
                    <input type="text" wire:model="license_plate" class="w-full bg-gray-50 border border-gray-300 rounded-none px-3.5 py-2.5 text-sm font-semibold text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400 uppercase" placeholder="B 1234 XYZ">
                    @error('license_plate') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-brand-dark uppercase tracking-wider mb-2">
                        Alamat Layanan
                        @if ($service_method !== 'workshop')
                            <span class="text-brand-red font-black">*WAJIB</span>
                        @else
                            <span class="text-gray-400 font-normal">(Opsional)</span>
                        @endif
                    </label>
                    <textarea wire:model="service_address" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-none p-3.5 text-sm font-medium text-brand-dark focus:border-brand-red focus:ring-1 focus:ring-brand-red transition placeholder-gray-400" placeholder="Masukkan alamat lengkap lokasi kendaraan..."></textarea>
                    @error('service_address') <span class="text-brand-red text-xs mt-1 block font-bold uppercase">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        <!-- Tahap 08 - Review & Submit -->
        @if ($currentStep == 8)
            <div class="space-y-5 animate-fade-in">
                <div class="border-l-4 border-brand-red pl-3">
                    <h2 class="text-sm font-black text-brand-dark uppercase tracking-wider">Tinjau Rangkuman Booking</h2>
                    <p class="text-xs font-medium text-gray-500">Pastikan seluruh data yang Anda masukkan sudah benar.</p>
                </div>

                <div class="bg-gray-50 border-t-4 border-brand-dark p-6 rounded-none space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">Kendaraan</span>
                            <span class="font-black text-brand-dark uppercase text-sm">{{ $vehicle_type }} - {{ $transmission }}</span>
                            <span class="text-gray-500 block text-[11px] font-semibold mt-0.5 uppercase">({{ trim(($brand ?? '') . ' ' . ($model_name ?? '') . ' ' . ($year ?? '')) ?: 'Tidak ada spesifikasi' }})</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">No. Polisi</span>
                            <span class="font-black text-brand-dark uppercase text-sm tracking-widest">{{ $license_plate ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">Layanan Dipilih</span>
                        <span class="font-black text-brand-dark uppercase text-sm">{{ $this->selectedServiceNames->isNotEmpty() ? $this->selectedServiceNames->implode(', ') : '-' }}</span>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Keluhan & Gejala</span>

                        @forelse ($this->selectedSymptomsDetail->groupBy('category') as $category => $symptoms)
                            <div class="mb-3 last:mb-0">
                                <span class="font-black text-brand-dark uppercase text-sm block">{{ $category }}</span>
                                <ul class="list-disc list-inside text-gray-700 font-medium mt-1 space-y-1">
                                    @foreach ($symptoms as $symp)
                                        <li>
                                            {{ $symp->symptom_name }}
                                            @if (!empty($assessment_answers[$symp->id]))
                                                <span class="block ml-4 text-[11px] italic text-gray-600 border-l-2 border-brand-red pl-2 mt-0.5">
                                                    "{{ $assessment_answers[$symp->id] }}"
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <span class="font-black text-brand-dark uppercase text-sm">Tidak ada keluhan (servis berkala saja)</span>
                        @endforelse
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">Metode Servis</span>
                            <span class="font-black text-brand-dark uppercase text-sm">{{ ucfirst(str_replace('_', ' ', $service_method)) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">Jadwal</span>
                            <span class="font-black text-brand-dark uppercase text-sm">{{ $booking_date }} &bull; {{ $booking_time }} WIB</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-1">Informasi Pemesan</span>
                        <span class="font-black text-brand-dark uppercase text-sm block">{{ $customer_name }} (<span class="text-brand-red">{{ $customer_phone }}</span>)</span>
                        @if(!empty($service_address))
                            <span class="text-gray-600 font-medium block mt-1 leading-relaxed">Alamat: {{ $service_address }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Tahap 09 - Cetak Kode Booking (Selesai/Sukses) -->
        @if ($currentStep == 9)
            <div class="space-y-6 text-center animate-fade-in py-4">
                <div class="w-16 h-16 bg-brand-red text-white flex items-center justify-center mx-auto shadow-md rounded-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-white bg-brand-dark px-3 py-1 inline-block">
                        Booking Berhasil
                    </span>
                    <h2 class="text-2xl font-black text-brand-dark uppercase tracking-tight mt-3">Kode Booking Anda Siap</h2>
                    <p class="text-xs font-medium text-gray-500 mt-1 max-w-sm mx-auto">Tunjukkan kode resmi di bawah ini kepada tim mekanik kami.</p>
                </div>

                {{-- TIKET KODE REGISTRASI MEKANIKAL --}}
                <div class="max-w-xs sm:max-w-sm mx-auto bg-brand-dark text-white p-6 rounded-none shadow-xl border-l-4 border-brand-red relative overflow-hidden text-left">
                    <span class="text-[10px] tracking-widest text-gray-400 uppercase font-black block mb-1">KODE REGISTRASI</span>
                    <div class="text-2xl font-black tracking-widest text-brand-red my-3 font-mono bg-black/40 py-3 text-center border border-gray-800 shadow-inner rounded-none">
                        {{ $booking_code ?: 'BKG-000000' }}
                    </div>

                    <div class="text-xs space-y-2 border-t border-gray-800 pt-4 mt-4 text-gray-300 font-medium">
                        <div class="flex justify-between">
                            <span class="text-gray-500 uppercase text-[10px] font-bold">Nama:</span>
                            <span class="font-black text-white uppercase">{{ $customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 uppercase text-[10px] font-bold">No. Polisi:</span>
                            <span class="font-black text-white uppercase tracking-wider">{{ $license_plate }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 uppercase text-[10px] font-bold">Jadwal:</span>
                            <span class="font-black text-white uppercase">{{ $booking_date }} ({{ $booking_time }})</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-3 pt-3">
                    <button type="button" onclick="window.print()" class="px-6 py-3 bg-brand-dark hover:bg-brand-red text-white text-xs font-black uppercase tracking-wider rounded-none transition shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Cetak / Unduh Bukti</span>
                    </button>
                    <a href="{{ url('/') }}" class="px-6 py-3 bg-gray-100 hover:bg-brand-dark hover:text-white text-brand-dark border-2 border-brand-dark text-xs font-black uppercase tracking-wider rounded-none transition flex items-center justify-center space-x-2">
                        <span>Selesai</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Navigasi Tombol -->
        @if ($currentStep < 9)
            <div class="mt-8 pt-5 border-t-2 border-gray-200 flex justify-between items-center">
                @if ($currentStep > 1)
                    <button type="button" wire:click="previousStep" class="px-5 py-3 bg-white border-2 border-brand-dark hover:bg-brand-dark hover:text-white text-brand-dark text-xs font-black uppercase tracking-wider rounded-none transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        <span>Kembali</span>
                    </button>
                @else
                    <div></div>
                @endif

                @if ($currentStep < 8)
                    <button type="button" wire:click="nextStep" class="px-6 py-3 bg-brand-dark hover:bg-brand-red text-white text-xs font-black uppercase tracking-wider rounded-none transition shadow-md flex items-center space-x-2">
                        <span>Selanjutnya</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @else
                    <button type="submit" class="px-6 py-3 bg-brand-dark hover:bg-brand-red text-white text-xs font-black uppercase tracking-wider rounded-none transition shadow-md flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span>Konfirmasi Booking</span>
                    </button>
                @endif
            </div>
        @endif

    </form>
</div>
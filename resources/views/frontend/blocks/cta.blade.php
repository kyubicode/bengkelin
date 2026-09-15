<div class="cta-block flex justify-center items-center my-6">
    <a href="{{ e($data['url'] ?? '#') }}" 
       class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
        {{ e($data['label'] ?? 'Selengkapnya') }}
        <!-- Ikon panah opsional untuk mempermanis interaksi -->
        <svg class="w-5 h-5 ml-2 -mr-1 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
    </a>
</div>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#1B1D29] border border-gray-700 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-[#2E324A] focus:outline-none focus:border-indigo-500 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>

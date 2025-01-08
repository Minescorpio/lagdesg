@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-[#FFD700] text-black rounded-lg text-sm font-medium leading-5 shadow-sm hover:bg-[#FFE44D] focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition-colors duration-200'
            : 'inline-flex items-center px-4 py-2 bg-[#FFD700] text-black rounded-lg text-sm font-medium leading-5 shadow-sm hover:bg-[#FFE44D] focus:outline-none focus:ring-2 focus:ring-[#FFD700] transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

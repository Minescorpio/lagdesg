@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-100 bg-blue-50/5 focus:outline-none focus:bg-blue-100/10 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-300 hover:text-gray-100 hover:bg-gray-50/5 focus:outline-none focus:bg-gray-50/10 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

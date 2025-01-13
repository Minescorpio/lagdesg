@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-2 border-l-4 border-blue-400 text-base font-medium text-gray-100 bg-blue-50/5 focus:outline-none focus:text-blue-300 focus:bg-blue-100/10 focus:border-blue-700 transition duration-150 ease-in-out'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-300 hover:text-gray-100 hover:bg-gray-50/5 hover:border-gray-300 focus:outline-none focus:text-gray-100 focus:bg-gray-50/10 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

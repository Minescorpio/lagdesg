@props(['disabled' => false])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-gray-600 bg-gray-700 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800']) !!}>

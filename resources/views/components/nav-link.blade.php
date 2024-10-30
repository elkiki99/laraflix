@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-100 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 hover:text-gray-300 hover:border-gray-700 focus:outline-none focus:text-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

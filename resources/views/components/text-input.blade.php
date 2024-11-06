@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-gray-900 text-gray-300 focus:border-gray-200 focus:ring-gray-200 rounded-md shadow-sm']) }}>

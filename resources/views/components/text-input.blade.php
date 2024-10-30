@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm']) }}>

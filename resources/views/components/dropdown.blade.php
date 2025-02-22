@props(['class' => '', 'align' => 'right', 'width' => '48', 'contentClasses' => ''])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $width = match ($width) {
        '56' => 'w-48',
        '36' => 'w-36',
        default => "w-64",
    };
@endphp

<div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
    <div>
        {{ $trigger }}
    </div>
    
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50  {{ $width }} {{ $class }} rounded-md shadow-lg backdrop-blur-sm {{ $alignmentClasses }}"
        style="display: none">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 overflow-y-auto max-h-64 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

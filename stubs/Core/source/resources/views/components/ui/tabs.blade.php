@props([
    'default' => null,
    'orientation' => 'horizontal',
])

<div
    x-data="{ tab: '{{ $default }}', orientation: '{{ $orientation }}' }"
    {{ $attributes->merge(['class' => 'ui-card']) }}
>
    @if($orientation === 'vertical')
        <div class="flex flex-col sm:flex-row">
            <div class="shrink-0 border-b border-gray-200 p-3 sm:w-56 sm:border-r sm:border-b-0 dark:border-gray-800">
                <nav role="tablist" class="flex gap-1 overflow-x-auto overflow-y-hidden sm:flex-col sm:overflow-visible">
                    {{ $tabs }}
                </nav>
            </div>

            <div class="min-w-0 flex-1 p-6">
                {{ $slot }}
            </div>
        </div>
    @else
        <div class="border-b border-gray-200 px-4 dark:border-gray-800">
            <nav role="tablist" class="flex gap-6 overflow-x-auto overflow-y-hidden">
                {{ $tabs }}
            </nav>
        </div>

        <div class="p-6">
            {{ $slot }}
        </div>
    @endif
</div>

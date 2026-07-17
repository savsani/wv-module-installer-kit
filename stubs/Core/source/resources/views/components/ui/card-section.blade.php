@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'ui-card']) }}>
    <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-800">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h2>
            @if ($description)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="shrink-0">
                {{ $actions }}
            </div>
        @endisset
    </div>

    <div class="px-6 py-6">
        {{ $slot }}
    </div>
</div>

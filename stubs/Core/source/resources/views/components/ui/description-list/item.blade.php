@props(['label'])

<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-4']) }}>
    <dt class="text-gray-500 dark:text-gray-400">{{ $label }}</dt>
    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $slot }}</dd>
</div>

@props([
    'label' => null,
    'badge' => null,
    'for' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'success' => null,
])

<div {{ $attributes }}>
    @if($label)
        <div class="mb-1.5 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <label @if($for) for="{{ $for }}" @endif class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $label }}@if($required) <span class="text-red-500 dark:text-red-400">*</span>@endif
                </label>
                @if($badge)
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">{{ $badge }}</span>
                @endif
            </div>
            @isset($action)
                <div class="text-sm">{{ $action }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}

    @if($error)
        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif($success)
        <p class="mt-1.5 text-xs text-green-600 dark:text-green-400">{{ $success }}</p>
    @elseif($hint)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>

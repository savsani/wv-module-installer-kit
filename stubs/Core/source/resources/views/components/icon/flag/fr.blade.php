@props(['class' => 'h-5 w-5'])

<span {{ $attributes->merge(['class' => "$class inline-flex shrink-0 overflow-hidden rounded-full"]) }}>
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-full" aria-hidden="true">
        <rect width="24" height="24" fill="#fff" />
        <rect width="8" height="24" fill="#002395" />
        <rect x="16" width="8" height="24" fill="#ED2939" />
    </svg>
</span>

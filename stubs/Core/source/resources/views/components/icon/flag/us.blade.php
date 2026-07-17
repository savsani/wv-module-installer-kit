@props(['class' => 'h-5 w-5'])

<span {{ $attributes->merge(['class' => "$class inline-flex shrink-0 overflow-hidden rounded-full"]) }}>
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-full w-full" aria-hidden="true">
        <rect width="24" height="24" fill="#fff" />
        <rect y="0" width="24" height="2.4" fill="#B22234" />
        <rect y="4.8" width="24" height="2.4" fill="#B22234" />
        <rect y="9.6" width="24" height="2.4" fill="#B22234" />
        <rect y="14.4" width="24" height="2.4" fill="#B22234" />
        <rect y="19.2" width="24" height="2.4" fill="#B22234" />
        <rect width="11" height="13.2" fill="#3C3B6E" />
    </svg>
</span>

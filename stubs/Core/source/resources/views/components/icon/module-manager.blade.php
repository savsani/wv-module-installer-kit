@props(['class' => 'h-5 w-5'])

<svg class="{{ $class }}" {{ $attributes }} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" aria-hidden="true">
    {{-- Top box --}}
    <polygon points="12,1.9 15.98,4.2 15.98,8.8 12,11.1 8.02,8.8 8.02,4.2" stroke-width="1.5" stroke-linejoin="round" />
    <line x1="12" y1="6.5" x2="12" y2="1.9" stroke-width="1.5" stroke-linecap="round" />
    <line x1="12" y1="6.5" x2="15.98" y2="8.8" stroke-width="1.5" stroke-linecap="round" />
    <line x1="12" y1="6.5" x2="8.02" y2="8.8" stroke-width="1.5" stroke-linecap="round" />

    {{-- Bottom-left box --}}
    <polygon points="7.2,10.2 11.18,12.5 11.18,17.1 7.2,19.4 3.22,17.1 3.22,12.5" stroke-width="1.5" stroke-linejoin="round" />
    <line x1="7.2" y1="14.8" x2="7.2" y2="10.2" stroke-width="1.5" stroke-linecap="round" />
    <line x1="7.2" y1="14.8" x2="11.18" y2="17.1" stroke-width="1.5" stroke-linecap="round" />
    <line x1="7.2" y1="14.8" x2="3.22" y2="17.1" stroke-width="1.5" stroke-linecap="round" />

    {{-- Bottom-right box --}}
    <polygon points="16.8,10.2 20.78,12.5 20.78,17.1 16.8,19.4 12.82,17.1 12.82,12.5" stroke-width="1.5" stroke-linejoin="round" />
    <line x1="16.8" y1="14.8" x2="16.8" y2="10.2" stroke-width="1.5" stroke-linecap="round" />
    <line x1="16.8" y1="14.8" x2="20.78" y2="17.1" stroke-width="1.5" stroke-linecap="round" />
    <line x1="16.8" y1="14.8" x2="12.82" y2="17.1" stroke-width="1.5" stroke-linecap="round" />
</svg>

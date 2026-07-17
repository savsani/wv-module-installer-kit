<img
    src="{{ asset('images/brand/logo-light.png') }}"
    alt="{{ config('app.name') }}"
    {{ $attributes->merge(['class' => 'shrink-0 dark:hidden']) }}
>
<img
    src="{{ asset('images/brand/logo-dark.png') }}"
    alt="{{ config('app.name') }}"
    {{ $attributes->merge(['class' => 'hidden shrink-0 dark:block']) }}
>

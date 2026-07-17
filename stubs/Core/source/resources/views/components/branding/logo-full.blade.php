<img
    src="{{ asset('images/brand/logo-full-light.png') }}"
    alt="{{ config('app.name') }}"
    {{ $attributes->merge(['class' => 'w-auto dark:hidden']) }}
>
<img
    src="{{ asset('images/brand/logo-full-dark.png') }}"
    alt="{{ config('app.name') }}"
    {{ $attributes->merge(['class' => 'hidden w-auto dark:block']) }}
>

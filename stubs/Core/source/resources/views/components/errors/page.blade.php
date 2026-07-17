@props([
    'code',
    'title',
    'message',
    'showAction' => true,
])

<div class="flex flex-1 flex-col items-center justify-center px-4 py-12 text-center">
    <p class="text-4xl font-extrabold tracking-tight text-brand-600 dark:text-brand-500 sm:text-5xl">{{ $code }}</p>
    <h1 class="mt-3 text-lg font-bold text-gray-900 dark:text-gray-100 sm:text-xl">{{ $title }}</h1>
    <p class="mt-2 max-w-md text-sm text-gray-500 dark:text-gray-400">{{ $message }}</p>

    @if($showAction)
        <div class="mt-6">
            <x-ui.button :href="url('/')" variant="primary" size="sm">
                <x-slot:iconLeft>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </x-slot:iconLeft>
                Go to Dashboard
            </x-ui.button>
        </div>
    @endif
</div>

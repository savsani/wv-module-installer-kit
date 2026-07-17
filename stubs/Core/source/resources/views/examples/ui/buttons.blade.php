@extends('layouts.app')

@section('title', 'Buttons')

@section('header')
    <x-ui.page-header title="Buttons">
        Reference gallery for <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.button&gt;</code>.
        Every combination of <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">variant</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">style</code>, and
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">size</code> is mapped once inside the component.
    </x-ui.page-header>
@endsection

@section('content')
    <div class="grid gap-6 sm:grid-cols-2">
        <x-ui.card-section title="Solid">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="primary">Primary</x-ui.button>
                <x-ui.button variant="secondary">Secondary</x-ui.button>
                <x-ui.button variant="success">Success</x-ui.button>
                <x-ui.button variant="danger">Danger</x-ui.button>
                <x-ui.button variant="warning">Warning</x-ui.button>
                <x-ui.button variant="info">Info</x-ui.button>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Outline">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="primary" style="outline">Primary</x-ui.button>
                <x-ui.button variant="secondary" style="outline">Secondary</x-ui.button>
                <x-ui.button variant="success" style="outline">Success</x-ui.button>
                <x-ui.button variant="danger" style="outline">Danger</x-ui.button>
                <x-ui.button variant="warning" style="outline">Warning</x-ui.button>
                <x-ui.button variant="info" style="outline">Info</x-ui.button>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Sizes">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button size="xs">Extra small</x-ui.button>
                <x-ui.button size="sm">Small</x-ui.button>
                <x-ui.button size="md">Medium</x-ui.button>
                <x-ui.button size="lg">Large</x-ui.button>
                <x-ui.button size="xl">Extra large</x-ui.button>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Icons & States">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="primary">
                    <x-slot:iconLeft><x-icon.plus class="h-4 w-4" /></x-slot:iconLeft>
                    Add item
                </x-ui.button>

                <x-ui.button variant="secondary" style="outline">
                    Continue
                    <x-slot:iconRight><x-icon.arrow-right class="h-4 w-4" /></x-slot:iconRight>
                </x-ui.button>

                <x-ui.button variant="danger" style="outline">
                    <x-slot:iconLeft><x-icon.trash class="h-4 w-4" /></x-slot:iconLeft>
                    Delete
                </x-ui.button>

                <x-ui.button variant="success">
                    <x-slot:iconLeft><x-icon.check class="h-4 w-4" /></x-slot:iconLeft>
                    Confirm
                </x-ui.button>

                <x-ui.button variant="primary" href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}">
                    Link button
                    <x-slot:iconRight><x-icon.arrow-right class="h-4 w-4" /></x-slot:iconRight>
                </x-ui.button>

                <x-ui.button variant="primary" disabled>Disabled</x-ui.button>
            </div>
        </x-ui.card-section>
    </div>
@endsection

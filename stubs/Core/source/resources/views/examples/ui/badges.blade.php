@extends('layouts.app')

@section('title', 'Badges')

@section('header')
    <x-ui.page-header title="Badges">
        Reference gallery for <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.badge&gt;</code>.
        Every combination of <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">variant</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">style</code>, and
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">size</code> is mapped once inside the component.
    </x-ui.page-header>
@endsection

@section('content')
    <div class="grid gap-6 sm:grid-cols-2">
        <x-ui.card-section title="Light">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.badge variant="primary">Primary</x-ui.badge>
                <x-ui.badge variant="secondary">Secondary</x-ui.badge>
                <x-ui.badge variant="success">Success</x-ui.badge>
                <x-ui.badge variant="danger">Danger</x-ui.badge>
                <x-ui.badge variant="warning">Warning</x-ui.badge>
                <x-ui.badge variant="info">Info</x-ui.badge>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Solid">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.badge variant="primary" style="solid">Primary</x-ui.badge>
                <x-ui.badge variant="secondary" style="solid">Secondary</x-ui.badge>
                <x-ui.badge variant="success" style="solid">Success</x-ui.badge>
                <x-ui.badge variant="danger" style="solid">Danger</x-ui.badge>
                <x-ui.badge variant="warning" style="solid">Warning</x-ui.badge>
                <x-ui.badge variant="info" style="solid">Info</x-ui.badge>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Icons">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.badge variant="success">
                    <x-slot:iconLeft><x-icon.check class="h-3 w-3" /></x-slot:iconLeft>
                    Completed
                </x-ui.badge>

                <x-ui.badge variant="danger">
                    Failed
                    <x-slot:iconRight><x-icon.x-mark class="h-3 w-3" /></x-slot:iconRight>
                </x-ui.badge>

                <x-ui.badge variant="warning" style="solid">
                    <x-slot:iconLeft><x-icon.star class="h-3 w-3" /></x-slot:iconLeft>
                    Featured
                </x-ui.badge>

                <x-ui.badge variant="info">
                    <x-slot:iconLeft><x-icon.information-circle class="h-3.5 w-3.5" /></x-slot:iconLeft>
                    Beta
                </x-ui.badge>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Sizes & Shape">
            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.badge variant="primary" size="sm">Small</x-ui.badge>
                    <x-ui.badge variant="primary" size="md">Medium</x-ui.badge>
                    <x-ui.badge variant="primary" size="lg">Large</x-ui.badge>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.badge variant="secondary" :pill="false">Draft</x-ui.badge>
                    <x-ui.badge variant="success" :pill="false" style="solid">Active</x-ui.badge>
                </div>
            </div>
        </x-ui.card-section>
    </div>
@endsection

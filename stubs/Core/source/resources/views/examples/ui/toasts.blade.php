@extends('layouts.app')

@section('title', 'Toasts')

@section('header')
    <x-ui.page-header title="Toasts">
        Reference gallery for the global toast stack, triggered via
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">$store.toast.show({ title, message, type })</code>.
        Meant for surfacing server responses — auto-dismisses after 3 seconds, click ✕ to dismiss early.
    </x-ui.page-header>
@endsection

@section('content')
    <x-ui.card-section title="Variants" description="Each button pushes a toast with a title and message onto the stack in the top-right corner.">
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="success" @click="$store.toast.show({ title: 'Success', message: 'Changes saved successfully.', type: 'success' })">
                <x-slot:iconLeft>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </x-slot:iconLeft>
                Success
            </x-ui.button>

            <x-ui.button variant="warning" @click="$store.toast.show({ title: 'Warning', message: 'Please review your input before continuing.', type: 'warning' })">
                <x-slot:iconLeft>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </x-slot:iconLeft>
                Warning
            </x-ui.button>

            <x-ui.button variant="danger" @click="$store.toast.show({ title: 'Error', message: 'Something went wrong. Please try again.', type: 'danger' })">
                <x-slot:iconLeft>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </x-slot:iconLeft>
                Danger
            </x-ui.button>

            <x-ui.button variant="info" @click="$store.toast.show({ title: 'Info', message: 'Your session will expire in 5 minutes.', type: 'info' })">
                <x-slot:iconLeft>
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.6501 11.9996C3.6501 7.38803 7.38852 3.64961 12.0001 3.64961C16.6117 3.64961 20.3501 7.38803 20.3501 11.9996C20.3501 16.6112 16.6117 20.3496 12.0001 20.3496C7.38852 20.3496 3.6501 16.6112 3.6501 11.9996ZM12.0001 1.84961C6.39441 1.84961 1.8501 6.39392 1.8501 11.9996C1.8501 17.6053 6.39441 22.1496 12.0001 22.1496C17.6058 22.1496 22.1501 17.6053 22.1501 11.9996C22.1501 6.39392 17.6058 1.84961 12.0001 1.84961ZM10.9992 7.52468C10.9992 8.07697 11.4469 8.52468 11.9992 8.52468H12.0002C12.5525 8.52468 13.0002 8.07697 13.0002 7.52468C13.0002 6.9724 12.5525 6.52468 12.0002 6.52468H11.9992C11.4469 6.52468 10.9992 6.9724 10.9992 7.52468ZM12.0002 17.371C11.586 17.371 11.2502 17.0352 11.2502 16.621V10.9445C11.2502 10.5303 11.586 10.1945 12.0002 10.1945C12.4144 10.1945 12.7502 10.5303 12.7502 10.9445V16.621C12.7502 17.0352 12.4144 17.371 12.0002 17.371Z" fill="" />
                    </svg>
                </x-slot:iconLeft>
                Info
            </x-ui.button>

            <x-ui.button variant="primary" @click="$store.toast.show({ title: 'Primary', message: 'New feature unlocked!', type: 'primary' })">
                <x-slot:iconLeft>
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.6501 11.9996C3.6501 7.38803 7.38852 3.64961 12.0001 3.64961C16.6117 3.64961 20.3501 7.38803 20.3501 11.9996C20.3501 16.6112 16.6117 20.3496 12.0001 20.3496C7.38852 20.3496 3.6501 16.6112 3.6501 11.9996ZM12.0001 1.84961C6.39441 1.84961 1.8501 6.39392 1.8501 11.9996C1.8501 17.6053 6.39441 22.1496 12.0001 22.1496C17.6058 22.1496 22.1501 17.6053 22.1501 11.9996C22.1501 6.39392 17.6058 1.84961 12.0001 1.84961ZM10.9992 7.52468C10.9992 8.07697 11.4469 8.52468 11.9992 8.52468H12.0002C12.5525 8.52468 13.0002 8.07697 13.0002 7.52468C13.0002 6.9724 12.5525 6.52468 12.0002 6.52468H11.9992C11.4469 6.52468 10.9992 6.9724 10.9992 7.52468ZM12.0002 17.371C11.586 17.371 11.2502 17.0352 11.2502 16.621V10.9445C11.2502 10.5303 11.586 10.1945 12.0002 10.1945C12.4144 10.1945 12.7502 10.5303 12.7502 10.9445V16.621C12.7502 17.0352 12.4144 17.371 12.0002 17.371Z" fill="" />
                    </svg>
                </x-slot:iconLeft>
                Primary
            </x-ui.button>

            <x-ui.button variant="secondary" style="outline" @click="$store.toast.show({ message: 'Changes published.', type: 'success' })">
                Single line (no title)
            </x-ui.button>

            <x-ui.button
                variant="secondary"
                style="outline"
                @click="
                    $store.toast.show({ title: 'Success', message: 'First notification.', type: 'success' });
                    setTimeout(() => $store.toast.show({ title: 'Info', message: 'Second notification.', type: 'info' }), 400);
                    setTimeout(() => $store.toast.show({ title: 'Warning', message: 'Third notification.', type: 'warning' }), 800);
                "
            >
                Stack 3 Toasts
            </x-ui.button>
        </div>
    </x-ui.card-section>
@endsection

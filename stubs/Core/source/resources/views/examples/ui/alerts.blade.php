@extends('layouts.app')

@section('title', 'Alerts')

@section('header')
    <x-ui.page-header title="Alerts">
        Reference gallery for <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.alert&gt;</code>.
        Colors and default icons are mapped per variant inside the component, so restyling a variant everywhere is a one-place edit.
    </x-ui.page-header>
@endsection

@section('content')
    <x-ui.card-section title="Variants">
        <div class="space-y-4">
            <x-ui.alert variant="success" title="Success Message">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="danger" title="Error Message">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="warning" title="Warning Message">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="info" title="Info Message">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="primary" title="Primary Message">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="danger" title="Dismissible alert" dismissible>
                This alert can be closed — click the <span class="font-medium">×</span> button on the right.
            </x-ui.alert>

            <x-ui.alert variant="info" title="Info Message" :icon="false">
                You can insert a description for the message here. The text relates to the action that has been performed.
            </x-ui.alert>

            <x-ui.alert variant="info" :icon="false">
                An alert without an icon — just a colored surface and text, for lighter-weight messaging.
            </x-ui.alert>
        </div>
    </x-ui.card-section>
@endsection

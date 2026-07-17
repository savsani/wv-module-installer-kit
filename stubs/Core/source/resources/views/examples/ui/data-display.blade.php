@extends('layouts.app')

@section('title', 'Data Display')

@section('header')
    <x-ui.page-header title="Data Display">
        Reference gallery for the newer data-display components —
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.avatar&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.icon-button&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-form.rich-text-toolbar&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.stat-card&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.empty-state&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.description-list&gt;</code>,
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.copy-button&gt;</code>, and
        <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.confirm-delete-button&gt;</code>.
        The table-toolbar pieces (checkbox-th/td, bulk-actions-bar, filter) are demonstrated live on the
        <a href="{{ route('examples.data-table') }}" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400">Data Table</a> page instead.
    </x-ui.page-header>
@endsection

@section('content')
    <div class="grid gap-6 sm:grid-cols-2">
        <x-ui.card-section title="Avatars" description="Initials avatar, three sizes.">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.avatar name="Ravi Savsani" size="sm" />
                <x-ui.avatar name="Ravi Savsani" size="md" />
                <x-ui.avatar name="Ravi Savsani" size="lg" />
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Icon Buttons" description="Icon-only button used for toolbars, headers, and row actions.">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.icon-button size="sm" aria-label="Edit">
                    <x-icon.pencil class="h-4 w-4" />
                </x-ui.icon-button>
                <x-ui.icon-button size="md" aria-label="Notifications">
                    <x-icon.bell class="h-5 w-5" />
                </x-ui.icon-button>
                <x-ui.icon-button size="lg" aria-label="Settings">
                    <x-icon.dots-vertical class="h-5 w-5" />
                </x-ui.icon-button>
                <x-ui.confirm-delete-button onConfirm="$store.toast.show({ message: 'Deleted.', type: 'danger' })" />
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Icon Toggle Toolbar" description="Themeable rich-text toolbar — the reusable x-form.rich-text-toolbar component, composed from icon-toggle, icon-toggle-group, and icon-toggle-dropdown. plain defaults to true, which keeps it background-free like this." class="sm:col-span-2">
            <x-form.rich-text-toolbar />
        </x-ui.card-section>

        <x-ui.card-section title="Icon Toggle Toolbar — Shaded Background" description="Same x-form.rich-text-toolbar with plain set to false — a tinted bar background, same family as the number-stepper buttons, file-input's 'Choose file' chip, and the rich-text editor's own toolbar. Compare side-by-side with the plain version above." class="sm:col-span-2">
            <x-form.rich-text-toolbar :plain="false" />
        </x-ui.card-section>

        <x-ui.card-section title="Stat Cards" description="Dashboard KPI tiles with an optional trend badge." class="sm:col-span-2">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-ui.stat-card label="Open Tickets" value="24" change="-8.1%" trend="down" variant="danger">
                    <x-slot:icon><x-icon.exclamation-triangle class="h-5 w-5" /></x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card label="Team Members" value="12" variant="secondary">
                    <x-slot:icon><x-icon.users class="h-5 w-5" /></x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card label="Avg. Rating" value="4.6" change="+0.2" trend="up" variant="warning">
                    <x-slot:icon><x-icon.star class="h-5 w-5" /></x-slot:icon>
                </x-ui.stat-card>
            </div>
        </x-ui.card-section>

        <x-ui.card-section title="Empty State" description="With icon + actions slot.">
            <x-ui.empty-state title="No projects yet" description="Create your first project to get started.">
                <x-slot:icon><x-icon.squares-2x2 class="h-6 w-6" /></x-slot:icon>
                <x-slot:actions>
                    <x-ui.button size="sm">
                        <x-slot:iconLeft><x-icon.plus class="h-4 w-4" /></x-slot:iconLeft>
                        New Project
                    </x-ui.button>
                </x-slot:actions>
            </x-ui.empty-state>
        </x-ui.card-section>

        <x-ui.card-section title="Description List" description="Label/value rows for a detail panel.">
            <x-ui.description-list>
                <x-ui.description-list.item label="Plan">Pro</x-ui.description-list.item>
                <x-ui.description-list.item label="Seats">12 / 20</x-ui.description-list.item>
                <x-ui.description-list.item label="Renews">Jan 12, 2027</x-ui.description-list.item>
                <x-ui.description-list.item label="Status">
                    <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                </x-ui.description-list.item>
            </x-ui.description-list>
        </x-ui.card-section>

        <x-ui.card-section title="Copy Button" description="Copy-to-clipboard for IDs, SKUs, API keys." class="sm:col-span-2">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-1 rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-white/5">
                    <span class="font-mono text-gray-600 dark:text-gray-300">sk_live_51H8x...9fQ2</span>
                    <x-ui.copy-button value="sk_live_51H8x9fQ2">Copy key</x-ui.copy-button>
                </div>

                <div class="flex items-center gap-1 rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-white/5">
                    <span class="font-mono text-gray-600 dark:text-gray-300">ELEC-001</span>
                    <x-ui.copy-button value="ELEC-001" />
                </div>
            </div>
        </x-ui.card-section>
    </div>
@endsection

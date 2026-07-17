@extends('layouts.app')

@section('title', 'Tabs')

@section('header')
    <x-ui.page-header title="Tabs">
        Reference gallery for <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">&lt;x-ui.tabs&gt;</code>.
        Set <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">orientation</code> to switch between an
        underlined horizontal bar and a pill-style vertical list. Tab labels take an optional <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-800">icon</code> slot.
    </x-ui.page-header>
@endsection

@section('content')
    <div class="space-y-6">
        <x-ui.card-section title="Tab With Icon">
            <x-ui.tabs default="overview">
                <x-slot:tabs>
                    <x-ui.tabs.tab value="overview">
                        <x-slot:icon><x-icon.squares-2x2 /></x-slot:icon>
                        Overview
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="notification">
                        <x-slot:icon><x-icon.bell /></x-slot:icon>
                        Notification
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="analytics">
                        <x-slot:icon><x-icon.chart-bar /></x-slot:icon>
                        Analytics
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="customers">
                        <x-slot:icon><x-icon.users /></x-slot:icon>
                        Customers
                    </x-ui.tabs.tab>
                </x-slot:tabs>

                <x-ui.tabs.panel value="overview">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Overview</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Overview ipsum dolor sit amet consectetur. Non vitae facilisis urna tortor placerat egestas donec.
                        Faucibus diam gravida enim elit lacus a. Tincidunt fermentum condimentum quis et a et tempus.
                        Tristique urna nisi nulla elit sit libero scelerisque ante.
                    </p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="notification">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Notification</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Manage how and when you're notified about account activity, mentions, and updates.
                    </p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="analytics">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Analytics</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        A summary of traffic, conversions, and engagement across your workspace.
                    </p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="customers">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Customers</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Browse and manage the customers associated with your account.
                    </p>
                </x-ui.tabs.panel>
            </x-ui.tabs>
        </x-ui.card-section>

        <x-ui.card-section title="Horizontal Tabs (No Icon)">
            <x-ui.tabs default="profile">
                <x-slot:tabs>
                    <x-ui.tabs.tab value="profile">Profile</x-ui.tabs.tab>
                    <x-ui.tabs.tab value="security">Security</x-ui.tabs.tab>
                    <x-ui.tabs.tab value="billing">Billing</x-ui.tabs.tab>
                </x-slot:tabs>

                <x-ui.tabs.panel value="profile">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update your name, email, and public profile details.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="security">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your password, two-factor authentication, and active sessions.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="billing">
                    <p class="text-sm text-gray-600 dark:text-gray-400">View invoices and manage your subscription plan.</p>
                </x-ui.tabs.panel>
            </x-ui.tabs>
        </x-ui.card-section>

        <x-ui.card-section title="Vertical Tabs">
            <x-ui.tabs default="overview" orientation="vertical">
                <x-slot:tabs>
                    <x-ui.tabs.tab value="overview">Overview</x-ui.tabs.tab>
                    <x-ui.tabs.tab value="notification">Notification</x-ui.tabs.tab>
                    <x-ui.tabs.tab value="analytics">Analytics</x-ui.tabs.tab>
                    <x-ui.tabs.tab value="customers">Customers</x-ui.tabs.tab>
                </x-slot:tabs>

                <x-ui.tabs.panel value="overview">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Overview</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Overview ipsum dolor sit amet consectetur. Non vitae facilisis urna tortor placerat egestas donec.
                        Faucibus diam gravida enim elit lacus a. Tincidunt fermentum condimentum quis et a et tempus.
                        Tristique urna nisi nulla elit sit libero scelerisque ante.
                    </p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="notification">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Notification preferences go here.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="analytics">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Analytics summary goes here.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="customers">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Customer list goes here.</p>
                </x-ui.tabs.panel>
            </x-ui.tabs>
        </x-ui.card-section>

        <x-ui.card-section title="Vertical Tabs With Icon">
            <x-ui.tabs default="overview" orientation="vertical">
                <x-slot:tabs>
                    <x-ui.tabs.tab value="overview">
                        <x-slot:icon><x-icon.squares-2x2 /></x-slot:icon>
                        Overview
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="notification">
                        <x-slot:icon><x-icon.bell /></x-slot:icon>
                        Notification
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="analytics">
                        <x-slot:icon><x-icon.chart-bar /></x-slot:icon>
                        Analytics
                    </x-ui.tabs.tab>

                    <x-ui.tabs.tab value="customers">
                        <x-slot:icon><x-icon.users /></x-slot:icon>
                        Customers
                    </x-ui.tabs.tab>
                </x-slot:tabs>

                <x-ui.tabs.panel value="overview">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Overview content goes here.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="notification">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Notification preferences go here.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="analytics">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Analytics summary goes here.</p>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel value="customers">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Customer list goes here.</p>
                </x-ui.tabs.panel>
            </x-ui.tabs>
        </x-ui.card-section>
    </div>
@endsection

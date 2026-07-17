{{--
    Account dropdown for the authenticated header: avatar trigger + profile/logout
    links. Renders identically for the desktop toolbar and the mobile menu bar.
--}}
<x-navigation.dropdown align="right" width="56">
    <x-slot name="trigger">
        <button type="button" class="flex cursor-pointer items-center gap-2 rounded-lg py-1.5 pl-1.5 pr-2 text-sm transition hover:bg-gray-100 focus:outline-none dark:hover:bg-gray-800" aria-haspopup="true" :aria-expanded="open">
            <x-ui.avatar :name="auth()->user()->name" />
            <x-icon.chevron-down class="h-4 w-4 text-gray-400 transition-transform duration-150" x-bind:class="open && 'rotate-180'" />
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
            <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
            <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
        </div>

        <x-navigation.dropdown-link href="{{ route('profile.show') }}">
            My Profile
        </x-navigation.dropdown-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-navigation.dropdown-link href="{{ route('logout') }}" class="w-full text-left" onclick="event.preventDefault(); this.closest('form').submit();">
                Log Out
            </x-navigation.dropdown-link>
        </form>
    </x-slot>
</x-navigation.dropdown>

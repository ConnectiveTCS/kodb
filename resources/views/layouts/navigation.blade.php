<aside x-data="{ open: false }"
       class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <!-- Logo -->
    <div class="px-4 py-6">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <!-- Primary Navigation Links -->
    <nav class="px-4 space-y-2 flex flex-col sticky top-10 bg-white border-b border-gray-200">
        <x-nav-link :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                    class="block">
            {{ __('Dashboard') }}
        </x-nav-link>
                <x-nav-link :href="route('speakers.index')"
                    :active="request()->routeIs('speakers.index')"
                    class="block">
            {{ __('Speakers') }}
        </x-nav-link>
                <x-nav-link :href="route('dashboard')"
                    :active="request()->routeIs('')"
                    class="block">
            {{ __('Partners') }}
        </x-nav-link>
                <x-nav-link :href="route('dashboard')"
                    :active="request()->routeIs('')"
                    class="block">
            {{ __('Volunteers') }}
        </x-nav-link>
    </nav>

    <!-- User Settings (pinned at bottom) -->
    <div class="mt-auto px-4 py-6 sticky bottom-1 bg-white border-t border-gray-200">
        <x-dropdown align="right" width="48" direction="up">
            <x-slot name="trigger">
                <button class="w-full inline-flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-transparent rounded-md hover:text-gray-700">
                    <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</aside>

<header class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
    <button
        @click="sidebarOpen = !sidebarOpen"
        type="button"
        class="text-gray-500 hover:text-gray-700 lg:hidden"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
        </svg>
    </button>

    <div class="hidden lg:block"></div>

    <div class="flex items-center gap-4">
        <button type="button" class="relative text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
        </button>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button type="button" class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900">
                    <span class="hidden font-medium sm:inline">{{ auth()->user()->name }}</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 font-semibold text-primary-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                          onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Sair') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>

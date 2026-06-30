<nav x-data="{ open: false }" class="border-b border-white/60 bg-white/70 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 justify-between gap-6">
            <div class="flex items-center gap-8">
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/20">
                            <x-application-logo class="h-6 w-6 fill-current" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold tracking-[0.3em] text-sky-700">PDRIVE</p>
                            <p class="text-xs text-slate-500">Personal cloud storage</p>
                        </div>
                    </a>
                </div>

                <div class="hidden items-center gap-2 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Drive') }}
                    </x-nav-link>
                    <x-nav-link :href="route('favorites')" :active="request()->routeIs('favorites')">
                        {{ __('Favorites') }}
                    </x-nav-link>
                    <x-nav-link :href="route('trash')" :active="request()->routeIs('trash')">
                        {{ __('Trash') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                    Profile
                </a>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium leading-4 text-slate-600 transition hover:text-slate-900">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Drive') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('favorites')" :active="request()->routeIs('favorites')">
                {{ __('Favorites') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('trash')" :active="request()->routeIs('trash')">
                {{ __('Trash') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 transition-colors duration-300 dark:bg-slate-900 dark:border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-10 sm:flex sm:items-center sm:gap-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <div class="relative" x-data="themeManager()" x-init="init()" x-cloak>
                        <button type="button" @click="toggleMenu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 transition hover:border-cyan-400 hover:text-gray-900 dark:border-white/20 dark:text-slate-200 dark:hover:text-white" aria-label="Toggle theme">
                            <template x-if="buttonIcon() === 'light'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414" />
                                </svg>
                            </template>
                            <template x-if="buttonIcon() === 'dark'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                                </svg>
                            </template>
                            <template x-if="buttonIcon() === 'system'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-8 7h10M6 5h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                            </template>
                        </button>
                        <div x-cloak x-show="menuOpen" x-transition class="absolute right-0 mt-2 w-44 rounded-2xl border border-gray-100 bg-white p-3 text-sm shadow-xl dark:border-white/15 dark:bg-slate-900/90">
                            <button type="button" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white dark:bg-cyan-500/80': isActive('light') }" @click="setTheme('light')">
                                <span>Light</span>
                                <span aria-hidden="true">☀️</span>
                            </button>
                            <button type="button" class="mt-2 flex w-full items-center justify-between rounded-xl px-3 py-2 text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white dark:bg-cyan-500/80': isActive('dark') }" @click="setTheme('dark')">
                                <span>Dark</span>
                                <span aria-hidden="true">🌙</span>
                            </button>
                            <button type="button" class="mt-2 flex w-full items-center justify-between rounded-xl px-3 py-2 text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white dark:bg-cyan-500/80': isActive('system') }" @click="setTheme('system')">
                                <span>System</span>
                                <span aria-hidden="true">🖥️</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('settings.edit')">
                            {{ __('Pengaturan Situs') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
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

            <!-- Mobile Theme + Hamburger -->
            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <div class="relative" x-data="themeManager()" x-init="init()" x-cloak>
                    <button type="button" @click="toggleMenu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 transition hover:border-cyan-400 hover:text-gray-900 dark:border-white/20 dark:text-slate-200 dark:hover:text-white" aria-label="Tema">
                        <template x-if="buttonIcon() === 'light'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414" />
                            </svg>
                        </template>
                        <template x-if="buttonIcon() === 'dark'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </template>
                        <template x-if="buttonIcon() === 'system'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-8 7h10M6 5h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                        </template>
                    </button>
                    <div x-cloak x-show="menuOpen" x-transition class="absolute right-0 mt-2 w-32 rounded-2xl border border-gray-100 bg-white p-2 text-sm shadow-xl dark:border-white/15 dark:bg-slate-900/90">
                        <button type="button" class="w-full rounded-xl px-2 py-1 text-left text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white': isActive('light') }" @click="setTheme('light')">Light</button>
                        <button type="button" class="mt-1 w-full rounded-xl px-2 py-1 text-left text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white': isActive('dark') }" @click="setTheme('dark')">Dark</button>
                        <button type="button" class="mt-1 w-full rounded-xl px-2 py-1 text-left text-gray-600 transition hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/40" :class="{ 'bg-cyan-600 text-white': isActive('system') }" @click="setTheme('system')">System</button>
                    </div>
                </div>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('settings.edit')">
                    {{ __('Pengaturan Situs') }}
                </x-responsive-nav-link>

                <div class="px-4" x-data="themeManager()" x-init="init()">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-slate-400">Theme</p>
                    <div class="mt-2 space-y-2">
                        <button type="button" class="flex w-full items-center justify-between rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-cyan-400 hover:text-gray-900 dark:border-white/15 dark:text-slate-200" :class="{ 'bg-cyan-600 text-white': isActive('light') }" @click="setTheme('light')">Light</button>
                        <button type="button" class="flex w-full items-center justify-between rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-cyan-400 hover:text-gray-900 dark:border-white/15 dark:text-slate-200" :class="{ 'bg-cyan-600 text-white': isActive('dark') }" @click="setTheme('dark')">Dark</button>
                        <button type="button" class="flex w-full items-center justify-between rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:border-cyan-400 hover:text-gray-900 dark:border-white/15 dark:text-slate-200" :class="{ 'bg-cyan-600 text-white': isActive('system') }" @click="setTheme('system')">System</button>
                    </div>
                </div>
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

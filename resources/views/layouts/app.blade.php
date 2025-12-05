<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $siteName = $siteSettings['name'] ?? config('app.name', 'Laravel');
        @endphp

        <title>{{ $siteName }} • Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            (function () {
                var storageKey = 'zarly-theme-preference';
                var root = document.documentElement;
                var mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

                function resolve(preference) {
                    if (preference === 'system') {
                        return mediaQuery && mediaQuery.matches ? 'dark' : 'light';
                    }
                    return preference;
                }

                function apply(preference) {
                    var resolved = resolve(preference);
                    root.classList.toggle('dark', resolved === 'dark');
                    root.dataset.theme = resolved;
                    root.dataset.themePreference = preference;
                }

                try {
                    var saved = localStorage.getItem(storageKey) || 'system';
                    apply(saved);
                } catch (error) {
                    apply('system');
                }

                window.themeManager = function () {
                    return {
                        storageKey: 'zarly-theme-preference',
                        current: 'system',
                        menuOpen: false,
                        mediaQuery: null,
                        init() {
                            this.mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
                            this.current = this.getStoredPreference();
                            this.applyTheme(this.current);
                            this.bindMediaListener();
                        },
                        getStoredPreference() {
                            try {
                                return localStorage.getItem(this.storageKey) || 'system';
                            } catch (error) {
                                return 'system';
                            }
                        },
                        persistPreference(value) {
                            try {
                                localStorage.setItem(this.storageKey, value);
                            } catch (error) {
                                /* ignore */
                            }
                        },
                        resolveTheme(value = this.current) {
                            if (value === 'system') {
                                return this.mediaQuery && this.mediaQuery.matches ? 'dark' : 'light';
                            }
                            return value;
                        },
                        applyTheme(value) {
                            var resolved = this.resolveTheme(value);
                            var rootEl = document.documentElement;
                            rootEl.classList.toggle('dark', resolved === 'dark');
                            rootEl.dataset.theme = resolved;
                            rootEl.dataset.themePreference = value;
                        },
                        setTheme(value) {
                            if (!value) return;
                            this.current = value;
                            this.persistPreference(value);
                            this.applyTheme(value);
                            this.menuOpen = false;
                        },
                        buttonIcon() {
                            switch (this.resolveTheme()) {
                                case 'dark':
                                    return 'dark';
                                case 'light':
                                    return 'light';
                                default:
                                    return 'system';
                            }
                        },
                        isActive(value) {
                            return this.current === value;
                        },
                        toggleMenu() {
                            this.menuOpen = !this.menuOpen;
                        },
                        bindMediaListener() {
                            if (!this.mediaQuery) return;
                            var handler = () => {
                                if (this.current === 'system') {
                                    this.applyTheme('system');
                                }
                            };
                            if (this.mediaQuery.addEventListener) {
                                this.mediaQuery.addEventListener('change', handler);
                            } else if (this.mediaQuery.addListener) {
                                this.mediaQuery.addListener(handler);
                            }
                        },
                    };
                };
            })();
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-100 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <div class="min-h-screen bg-gray-100 transition-colors duration-300 dark:bg-slate-950">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow transition-colors duration-300 dark:bg-slate-900 dark:shadow-black/20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

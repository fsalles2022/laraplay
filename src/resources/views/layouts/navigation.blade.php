<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b bg-slate-950/80 backdrop-blur-md border-slate-800">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo Modernizada -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="bg-red-600 p-1.5 rounded-lg group-hover:rotate-12 transition-transform shadow-lg shadow-red-900/40">
                            <x-application-logo class="block w-auto h-6 text-white fill-current" />
                        </div>
                        <span class="hidden text-lg font-bold tracking-tighter text-slate-100 md:block">LAB<span class="text-red-500">PLAY</span></span>
                    </a>
                </div>

                <!-- Links de Navegação Dark -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="transition-colors border-red-500 text-slate-400 hover:text-red-400">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative ms-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition duration-150 ease-in-out border border-slate-700 rounded-xl text-slate-300 bg-slate-900/50 hover:bg-slate-800 hover:text-white focus:outline-none">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-red-600 to-orange-400 flex items-center justify-center text-[10px] text-white">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-1">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="overflow-hidden border rounded-lg shadow-2xl bg-slate-900 border-slate-800">
                                <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:bg-slate-800 hover:text-red-400">
                                    <div class="flex items-center gap-2 italic"><i data-lucide="user" class="w-4 h-4"></i> {{ __('Profile') }}</div>
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" class="text-slate-300 hover:bg-red-500/10 hover:text-red-500"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        <div class="flex items-center gap-2"><i data-lucide="log-out" class="w-4 h-4"></i> {{ __('Log Out') }}</div>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="p-2 transition rounded-lg text-slate-400 hover:bg-slate-800 focus:outline-none">
                    <i :class="{'hidden': open, 'block': ! open }" data-lucide="menu" class="w-6 h-6"></i>
                    <i :class="{'hidden': ! open, 'block': open }" data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t sm:hidden bg-slate-900 border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="border-red-500 text-slate-300">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="flex items-center gap-3 px-4">
                <div class="flex items-center justify-center w-10 h-10 font-bold text-white bg-red-600 rounded-full">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-base font-medium text-slate-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-400">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-red-400" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

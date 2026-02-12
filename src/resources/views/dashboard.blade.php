<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight text-slate-100">
                {{ __('Dev Workspace') }} <span class="ml-2 text-sm font-normal text-slate-500">/ Playground</span>
            </h2>
            <div class="flex items-center gap-3">
                <span class="flex w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-medium tracking-widest uppercase text-slate-400">Database Online</span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen py-10 bg-slate-950">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

            <!-- Welcome Alert / Status -->
            <div
                class="relative p-6 overflow-hidden border shadow-2xl rounded-2xl bg-gradient-to-r from-red-600/20 to-orange-600/10 border-red-500/20">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-red-400">
                            {{ __("Bem-vindo de volta, :name!", ['name' => Auth::user()->name]) }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-400">
                            Seu ambiente de testes Laravel está pronto para novas queries.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <i data-lucide="terminal" class="w-12 h-12 text-red-500/20"></i>
                    </div>
                </div>
                <!-- Detalhe decorativo no fundo -->
                <div class="absolute w-32 h-32 rounded-full -right-10 -top-10 bg-red-500/5 blur-3xl"></div>
            </div>

            <!-- Playground Container -->
            <div class="overflow-hidden">
                {{-- Aqui incluímos o conteúdo do playground --}}
                @include('playground.index')
            </div>

        </div>
        
        @include('components.footer')

    </div>

    <!-- Script para garantir que os ícones do Lucide funcionem no layout pai também -->
    <script src="https://unpkg.com"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>

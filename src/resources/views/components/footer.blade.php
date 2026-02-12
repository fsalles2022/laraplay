<footer class="mt-auto border-t mpt-8 p border-slate-800 bg-slate-950/50 backdrop-blur-md ">
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-6 md:flex-row">

            <!-- Lado Esquerdo: Branding & Copyright -->
            <div class="flex items-center gap-4">
                <div class="p-2 border rounded-lg bg-slate-800 border-slate-700">
                    <x-application-logo class="w-auto h-5 text-red-500 fill-current" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-200">Laravel Playground <span class="text-xs text-red-500">v1.0</span></p>
                    <p class="text-xs text-slate-500">© {{ date('Y') }} — Crafted for Developers.</p>
                </div>
            </div>

            <!-- Centro: Status em Tempo Real -->
            <div class="flex items-center gap-6 px-6 py-2 border rounded-full bg-slate-900/50 border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="relative flex w-2 h-2">
                        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-emerald-400"></span>
                        <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] uppercase tracking-widest font-bold text-slate-400">PHP {{ PHP_VERSION }}</span>
                </div>
                <div class="h-4 w-[1px] bg-slate-700"></div>
                <div class="flex items-center gap-2">
                    <i data-lucide="database" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span class="text-[10px] uppercase tracking-widest font-bold text-slate-400">Laravel {{ app()->version() }}</span>
                </div>
            </div>

            <!-- Lado Direito: Links Sociais/Doc -->
            <div class="flex items-center gap-4">
                <a href="https://laravel.com" target="_blank" class="transition-colors text-slate-500 hover:text-red-400" title="Laravel Docs">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </a>
                <a href="https://github.com" target="_blank" class="transition-colors text-slate-500 hover:text-white" title="GitHub Repo">
                    <i data-lucide="github" class="w-5 h-5"></i>
                </a>
                <div class="h-6 w-[1px] bg-slate-800 mx-2"></div>
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="p-2 transition-all border rounded-lg bg-slate-900 hover:bg-red-600/20 hover:text-red-500 border-slate-800 text-slate-400">
                    <i data-lucide="arrow-up" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Bottom bar com gradiente sutil -->
        <div class="mt-8 h-[1px] w-full bg-gradient-to-r from-transparent via-slate-800 to-transparent"></div>
    </div>
</footer>

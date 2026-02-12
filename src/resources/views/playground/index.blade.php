<!-- Adicione isso no topo do seu arquivo para garantir o UTF-8 -->
<meta charset="UTF-8">

<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <div class="space-y-6 lg:col-span-7">

        <!-- Exemplos -->
        <div class="p-4 border glass rounded-2xl border-slate-800">
            <h5 class="flex items-center gap-2 mb-3 text-[10px] font-bold tracking-widest uppercase text-slate-500">
                <i data-lucide="zap" class="w-3 h-3 text-amber-500"></i> Quick Starters
            </h5>
            <div class="flex flex-wrap gap-2">
                @foreach($examples as $label => $code)
                    <button type="button"
                        onclick="insertCode(`{{ addslashes($code) }}`)"
                        class="px-3 py-1 text-[11px] font-medium transition-all border rounded-full border-slate-700 text-slate-400 hover:border-red-500 hover:text-red-400">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Formulário -->
        <form method="POST" action="{{ route('play.run') }}" class="p-6 border shadow-2xl glass rounded-2xl border-slate-800">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input name="title" value="{{ old('title') }}"
                    class="text-sm bg-slate-900 border-slate-700 rounded-xl focus:ring-red-500 text-slate-200 placeholder-slate-600"
                    placeholder="Título (opcional)">
                <input name="tag" value="{{ old('tag') }}"
                    class="text-sm bg-slate-900 border-slate-700 rounded-xl focus:ring-red-500 text-slate-200 placeholder-slate-600"
                    placeholder="Tag (ex: query)">
            </div>

            <div class="relative group">
                <!-- Ajuste de Fonte: JetBrains Mono ou Fira Code como fallback -->
                <textarea id="code-editor" name="code" rows="10"
                    class="w-full p-4 font-mono text-sm leading-relaxed outline-none bg-slate-950 border-slate-800 rounded-xl text-emerald-400 focus:ring-2 focus:ring-red-500/50 custom-scrollbar"
                    style="font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;"
                    placeholder="return User::all();">{{ old('code') }}</textarea>
                <div class="absolute bottom-4 right-4 text-[10px] text-slate-600 uppercase font-black tracking-widest pointer-events-none">PHP REPL</div>
            </div>

            <button class="flex items-center justify-center w-full gap-2 py-3 mt-4 font-bold text-white transition-all bg-red-600 shadow-lg hover:bg-red-500 rounded-xl shadow-red-900/20 active:scale-[0.98]">
                <i data-lucide="play" class="w-4 h-4"></i> EXECUTAR CÓDIGO
            </button>
        </form>

        @if(session('output'))
            <div class="p-5 overflow-hidden border shadow-inner rounded-2xl bg-slate-900 border-slate-800">
                <div class="flex items-center gap-2 mb-2 text-xs font-bold tracking-tighter uppercase text-emerald-500">
                    <i data-lucide="terminal" class="w-4 h-4"></i> Output:
                </div>
                <!-- O whitespace-pre-wrap garante que quebras de linha apareçam -->
                <pre class="p-3 font-mono text-sm break-words whitespace-pre-wrap border rounded-lg text-slate-300 bg-black/20 border-white/5">{{ session('output') }}</pre>
            </div>
        @endif
    </div>

    <!-- Histórico Lateral -->
    <div class="space-y-4 lg:col-span-5">
        <div class="flex items-center justify-between px-2">
            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-100">
                <i data-lucide="layers" class="w-5 h-5 text-red-500"></i> Snippets
            </h3>
            <span class="px-2 py-1 text-[10px] font-black rounded-md bg-slate-800 text-slate-500 uppercase tracking-tighter">{{ $snippets->total() }} registros</span>
        </div>

        <div class="space-y-3 max-h-[750px] overflow-y-auto pr-2 custom-scrollbar">
            @forelse($snippets as $s)
                <div class="p-4 transition-all border glass rounded-xl border-slate-800 group hover:border-slate-600 {{ $s->favorite ? 'ring-1 ring-amber-500/40 bg-amber-500/[0.02]' : '' }}">
                    <div class="flex items-start justify-between mb-2">
                        <span class="text-[10px] font-black {{ $s->favorite ? 'text-amber-500' : 'text-slate-500' }} uppercase tracking-widest">
                             {{ $s->favorite ? '★ ' : '' }}{{ $s->tag }}
                        </span>

                        <div class="flex items-center gap-3">
                            <form action="{{ route('play.favorite', $s->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="transition-all hover:scale-110 {{ $s->favorite ? 'text-amber-500' : 'text-slate-500 hover:text-amber-400' }}">
                                    <i data-lucide="star" class="w-4 h-4 {{ $s->favorite ? 'fill-amber-500' : '' }}"></i>
                                </button>
                            </form>

                            <button onclick="insertCode(`{{ addslashes($s->code) }}`)" class="transition-colors text-slate-500 hover:text-emerald-400">
                                <i data-lucide="copy" class="w-4 h-4"></i>
                            </button>

                            <form action="{{ route('play.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Excluir permanentemente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="transition-colors text-slate-500 hover:text-red-500">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h4 class="mb-2 text-sm font-bold truncate text-slate-200">{{ $s->title }}</h4>

                    <div class="bg-black/40 p-2 rounded text-[11px] font-mono text-slate-400 border border-white/5 overflow-hidden">
                        {{ Str::limit($s->code, 100) }}
                    </div>
                </div>
            @empty
                <div class="py-20 text-center border-2 border-dashed rounded-2xl border-slate-800">
                    <p class="italic text-slate-600">Nenhum snippet encontrado.</p>
                </div>
            @endforelse
        </div>
        <div class="px-2 mt-4">
            {{ $snippets->links() }}
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com"></script>
<script>
    function insertCode(code) {
        const editor = document.getElementById('code-editor');
        // Usamos um textarea temporário para decodificar entidades HTML se necessário
        const doc = new DOMParser().parseFromString(code, 'text/html');
        editor.value = doc.documentElement.textContent;

        window.scrollTo({ top: 0, behavior: 'smooth' });
        editor.focus();
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();

        const editor = document.getElementById('code-editor');
        if (editor && editor.value) {
            editor.focus();
            editor.setSelectionRange(editor.value.length, editor.value.length);
        }
    });
</script>

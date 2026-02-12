<!-- Painel de Execução -->
<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <div class="space-y-6 lg:col-span-7">

        <!-- Exemplos -->
        <div class="p-4 border glass rounded-2xl border-slate-800">
            <h5 class="flex items-center gap-2 mb-3 text-xs font-bold tracking-widest uppercase text-slate-500">
                <i data-lucide="zap" class="w-3 h-3 text-amber-500"></i> Quick Starters
            </h5>
            <div class="flex flex-wrap gap-2">
                @foreach($examples as $label => $code)
                    <button type="button"
                        onclick="insertCode(`{{ addslashes($code) }}`)"
                        class="px-3 py-1 text-xs transition-all border rounded-full border-slate-700 text-slate-400 hover:border-red-500 hover:text-red-400">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Formulário -->
        <form method="POST" action="{{ route('play.run') }}" class="p-6 border shadow-2xl glass rounded-2xl border-slate-800">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input name="title" value="{{ old('title') }}" class="text-sm bg-slate-900 border-slate-700 rounded-xl focus:ring-red-500 text-slate-200" placeholder="Título (opcional)">
                <input name="tag" value="{{ old('tag') }}" class="text-sm bg-slate-900 border-slate-700 rounded-xl focus:ring-red-500 text-slate-200" placeholder="Tag (ex: query)">
            </div>

            <div class="relative group">
                <textarea id="code-editor" name="code" rows="8"
                    class="w-full p-4 font-mono text-sm outline-none bg-slate-950 border-slate-800 rounded-xl text-emerald-400 focus:ring-2 focus:ring-red-500/50 custom-scrollbar"
                    placeholder="return User::all();">{{ old('code') }}</textarea>
                <div class="absolute bottom-4 right-4 text-[10px] text-slate-600 uppercase font-bold tracking-widest">PHP REPL</div>
            </div>

            <button class="flex items-center justify-center w-full gap-2 py-3 mt-4 font-bold text-white transition-all bg-red-600 shadow-lg hover:bg-red-500 rounded-xl shadow-red-900/20">
                <i data-lucide="play" class="w-4 h-4"></i> EXECUTAR CÓDIGO
            </button>
        </form>

        @if(session('output'))
            <div class="p-5 border shadow-inner rounded-2xl bg-slate-900 border-slate-800">
                <div class="flex items-center gap-2 mb-2 text-xs font-bold tracking-tighter uppercase text-emerald-500">
                    <i data-lucide="terminal" class="w-4 h-4"></i> Output:
                </div>
                <pre class="font-mono text-sm break-all whitespace-pre-wrap text-slate-300">{{ session('output') }}</pre>
            </div>
        @endif
    </div>

    <!-- Histórico Lateral -->
    <div class="space-y-4 lg:col-span-5">
        <h3 class="flex items-center gap-2 px-2 text-lg font-bold text-slate-100">
            <i data-lucide="layers" class="w-5 h-5 text-red-500"></i> Snippets Recentes
        </h3>

        <div class="space-y-3 max-h-[700px] overflow-y-auto pr-2 custom-scrollbar">
            @forelse($snippets as $s)
                <div class="p-4 transition border glass rounded-xl border-slate-800 group hover:border-slate-600">
                    <div class="flex items-start justify-between">
                        <span class="text-[10px] font-black text-slate-500 uppercase">{{ $s->tag }}</span>
                        <div class="flex gap-2 transition opacity-0 group-hover:opacity-100">
                            <button onclick="insertCode(`{{ addslashes($s->code) }}`)" class="text-slate-400 hover:text-emerald-400"><i data-lucide="copy" class="w-4 h-4"></i></button>
                            <form action="{{ route('play.destroy', $s->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-slate-400 hover:text-red-500"><i data-lucide="trash" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </div>
                    <h4 class="mb-2 font-bold truncate text-slate-200">{{ $s->title }}</h4>
                    <div class="bg-black/40 p-2 rounded text-[11px] font-mono text-slate-400 border border-white/5 italic">
                        {{ Str::limit($s->code, 60) }}
                    </div>
                </div>
            @empty
                <p class="py-10 italic text-center text-slate-600">Nenhum snippet salvo ainda.</p>
            @endforelse
        </div>
        <div class="px-2 mt-4">
            {{ $snippets->links() }}
        </div>
    </div>
</div>

<script>
    function insertCode(code) {
        const editor = document.getElementById('code-editor');
        editor.value = code;
        window.scrollTo({ top: 0, behavior: 'smooth' });
        editor.focus();
    }

    document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('code-editor');
    if (editor.value) {
        editor.focus();
        editor.setSelectionRange(editor.value.length, editor.value.length);
    }
});

</script>

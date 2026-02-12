<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Throwable;

class PlaygroundController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $snippetsCount = Snippet::where('user_id', $userId)->count();
        $favoritesCount = Snippet::where('user_id', $userId)->where('favorite', true)->count();
        $snippets = Snippet::where('user_id', $userId)->latest()->paginate(10);
        $examples = $this->getExamples();

        return view('dashboard', compact('snippetsCount', 'favoritesCount', 'snippets', 'examples'));
    }

    public function run(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'title' => 'nullable|string|max:255',
            'tag'   => 'nullable|string|max:50',
        ]);

        $code = $request->code;

        // 1. Bloqueio Robusto com Word Boundaries (\b)
        $blocked = [
            'exec',
            'shell_exec',
            'system',
            'passthru',
            'proc_open',
            'popen',
            'unlink',
            'artisan',
            'mail',
            'curl_exec',
            'putenv',
            'posix_kill'
        ];

        foreach ($blocked as $fn) {
            if (preg_match("/\b{$fn}\s*\(/i", $code)) {
                Log::warning("Tentativa de uso de função proibida por User ID: " . Auth::id());
                return back()->with('output', "❌ Segurança: Função '{$fn}' é estritamente proibida.")->withInput();
            }
        }

        // 2. Proteção contra acesso a dados sensíveis (.env / config)
        if (preg_match("/\b(env|config|app)\s*\(/i", $code)) {
            return back()->with('output', "❌ Segurança: Acesso a configurações do sistema negado.")->withInput();
        }

        try {
            ob_start();

            // O eval é encapsulado em uma função anônima para isolar o escopo
            $wrapped = "return (function() {\n{$code}\n})();";
            $result = eval($wrapped);

            $output = ob_get_clean();
            $final = $this->formatResult($result, $output);

            // 3. Salva no Histórico
            Snippet::create([
                'title'   => $request->title ?? 'Untitled Snippet',
                'tag'     => $request->tag ?? 'general',
                'code'    => $code,
                'result'  => Str::limit($final, 5000),
                'user_id' => Auth::id(),
            ]);

            return back()->with('output', $final)->withInput();
        } catch (Throwable $e) {
            if (ob_get_level() > 0) ob_end_clean();
            return back()->with('output', '⚠️ Erro de Sintaxe: ' . $e->getMessage())->withInput();
        }
    }

    private function formatResult($result, $output)
    {
        if (!empty(trim($output))) return $output;

        if (is_iterable($result) || $result instanceof \Illuminate\Database\Eloquent\Model) {
            return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return var_export($result, true);
    }

    private function getExamples(): array
    {
        return [
            'User Count'   => "return \\App\\Models\\User::count();",
            'Latest User'  => "return \\App\\Models\\User::latest()->first();",
            'Str Plural'   => "return \Illuminate\Support\Str::plural('car', 5);",
            'Collection'   => "return collect([10, 20, 30])->avg();",
        ];
    }

    // Deletar Snippet
    public function destroy(Snippet $snippet)
    {
        // Garante que o usuário só delete o que é dele
        if ($snippet->user_id !== Auth::id()) {
            abort(403);
        }

        $snippet->delete();
        return back()->with('output', '✅ Snippet removido com sucesso.');
    }

    // Favoritar (Extra)
    public function toggleFavorite(Snippet $snippet)
    {
        if ($snippet->user_id !== Auth::id()) abort(403);

        $snippet->update(['favorite' => !$snippet->favorite]);
        return back();
    }
}

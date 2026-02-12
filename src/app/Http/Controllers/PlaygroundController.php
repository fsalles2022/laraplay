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

        $blocked = [
            'exec', 'shell_exec', 'system', 'passthru', 'proc_open', 'popen',
            'unlink', 'artisan', 'mail', 'curl_exec', 'putenv', 'posix_kill'
        ];

        foreach ($blocked as $fn) {
            if (preg_match("/\b{$fn}\s*\(/i", $code)) {
                Log::warning("Tentativa de uso de função proibida por User ID: " . Auth::id());
                return back()->with('output', "❌ Segurança: Função '{$fn}' é estritamente proibida.")->withInput();
            }
        }

        if (preg_match("/\b(env|config|app)\s*\(/i", $code)) {
            return back()->with('output', "❌ Segurança: Acesso a configurações do sistema negado.")->withInput();
        }

        try {
            ob_start();

            $wrapped = "return (function() {\n{$code}\n})();";
            Log::info("Snippet [{$request->title}]: " . $code);

            $result = eval($wrapped);

            $output = ob_get_clean();
            $final = $this->formatResult($result, $output);

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
            // Eloquent & DB
            'User Count'     => "return \App\Models\User::count();",
            'Last 5 Users'   => "return \App\Models\User::latest()->take(5)->get();",
            'Find User #1'   => "return \App\Models\User::find(1);",
            'DB Tables'      => "return \Illuminate\Support\Facades\DB::select('SHOW TABLES');",

            // Strings (Str Helpers)
            'Str Slug'       => "return \Illuminate\Support\Str::slug('Laravel Playground Pro');",
            'Str Random'     => "return \Illuminate\Support\Str::random(16);",
            'Str Mask'       => "return \Illuminate\Support\Str::mask('admin@email.com', '*', 3);",
            'Str Plural'     => "return \Illuminate\Support\Str::plural('child', 10);",
            'Str Markdown'   => "return \Illuminate\Support\Str::markdown('# Hello World');",

            // Collections
            'Coll Average'   => "return collect([10, 25, 45, 60])->avg();",
            'Coll Filter'    => "return collect([1, 2, 3, 4, 5, 6])->filter(fn(\$n) => \$n > 3)->values();",
            'Coll Map'       => "return collect(['laraplay', 'laravel'])->map(fn(\$s) => strtoupper(\$s));",

            // Dates (Carbon)
            'Now'            => "return now();",
            'Next Sunday'    => "return now()->next('Sunday')->format('d/m/Y');",
            'Diff For Humans'=> "return now()->subDays(5)->diffForHumans();",

            // Logic & Math
            'Simple Math'    => "return (150 * 0.15) + 20;",
            'Array Sort'     => "\$arr = [10, 5, 2, 8];\nsort(\$arr);\nreturn \$arr;",
            'JSON Encode'    => "return json_encode(['status' => 'success', 'data' => true]);",
            'Check Email'    => "\$email = 'teste@teste.com';\nreturn filter_var(\$email, FILTER_VALIDATE_EMAIL);",
            'Password Hash'  => "return \Illuminate\Support\Facades\Hash::make('secret123');",
        ];
    }

    public function destroy(Snippet $snippet)
    {
        if ($snippet->user_id !== Auth::id()) abort(403);
        $snippet->delete();
        return back()->with('output', '✅ Snippet removido com sucesso.');
    }

    public function toggleFavorite(Snippet $snippet)
    {
        if ($snippet->user_id !== Auth::id()) abort(403);
        $snippet->update(['favorite' => !$snippet->favorite]);
        return back();
    }
}

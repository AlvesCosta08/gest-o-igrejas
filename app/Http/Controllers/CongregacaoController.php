<?php

namespace App\Http\Controllers;

use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CongregacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Apenas administradores podem gerenciar congregações.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Congregacao::withCount(['filiados', 'users']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'LIKE', "%{$request->search}%")
                  ->orWhere('cidade', 'LIKE', "%{$request->search}%");
            });
        }

        $congregacoes = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('congregacoes.index', compact('congregacoes'));
    }

    public function create()
    {
        return view('congregacoes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:255|unique:congregacoes,nome',
            'cidade'    => 'required|string|max:255',
            'endereco'  => 'nullable|string|max:255',
            'telefone'  => 'nullable|string|max:20',
        ]);

        Congregacao::create($validated);

        return redirect()->route('congregacoes.index')
            ->with('success', 'Congregação cadastrada com sucesso!');
    }

    public function show(Congregacao $congregacao)
    {
        $congregacao->loadCount(['filiados', 'users']);
        $congregacao->load(['filiados' => fn($q) => $q->orderBy('nome')->limit(10), 'users']);

        return view('congregacoes.show', compact('congregacao'));
    }

    public function edit(Congregacao $congregacao)
    {
        return view('congregacoes.edit', compact('congregacao'));
    }

    public function update(Request $request, Congregacao $congregacao)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:255|unique:congregacoes,nome,' . $congregacao->id,
            'cidade'    => 'required|string|max:255',
            'endereco'  => 'nullable|string|max:255',
            'telefone'  => 'nullable|string|max:20',
        ]);

        $congregacao->update($validated);

        return redirect()->route('congregacoes.index')
            ->with('success', 'Congregação atualizada com sucesso!');
    }

    public function destroy(Congregacao $congregacao)
    {
        if ($congregacao->filiados()->count() > 0) {
            return redirect()->route('congregacoes.index')
                ->with('error', 'Não é possível excluir uma congregação que possui filiados.');
        }

        if ($congregacao->users()->count() > 0) {
            return redirect()->route('congregacoes.index')
                ->with('error', 'Não é possível excluir uma congregação que possui usuários vinculados.');
        }

        $congregacao->delete();

        return redirect()->route('congregacoes.index')
            ->with('success', 'Congregação excluída com sucesso!');
    }
}
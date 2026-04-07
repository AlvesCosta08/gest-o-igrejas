<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Apenas administradores podem gerenciar usuários.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::with('congregacao');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->filled('congregacao_id')) {
            $query->where('congregacao_id', $request->congregacao_id);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        $users        = $query->orderBy('name')->paginate(15)->withQueryString();
        $congregacoes = Congregacao::orderBy('nome')->get();

        return view('users.index', compact('users', 'congregacoes'));
    }

    public function create()
    {
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('users.create', compact('congregacoes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'password'       => ['required', 'confirmed', Password::min(8)],
            'nivel'          => 'required|in:admin,user',
            'congregacao_id' => 'required|exists:congregacoes,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        $user->load('congregacao');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $congregacoes = Congregacao::orderBy('nome')->get();
        return view('users.edit', compact('user', 'congregacoes'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nivel'          => 'required|in:admin,user',
            'congregacao_id' => 'required|exists:congregacoes,id',
            'password'       => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
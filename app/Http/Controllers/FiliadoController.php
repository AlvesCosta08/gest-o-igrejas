<?php
// app/Http/Controllers/FiliadoController.php

namespace App\Http\Controllers;

use App\Models\Filiado;
use App\Models\Congregacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FiliadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Filiado::with('congregacao');

        // ✅ FILTRO POR CONGREGAÇÃO BASEADO NO PAPEL DO USUÁRIO
        if ($user->isAdmin()) {
            // Admin vê todos, mas pode filtrar por congregação se desejar
            if ($request->filled('congregacao_id')) {
                $query->where('congregacao_id', $request->congregacao_id);
            }
            // Admin pode filtrar por função (ex: ver apenas Secretários)
            if ($request->filled('funcao')) {
                $query->where('funcao', $request->funcao);
            }
        } elseif ($user->isSecretario()) {
            // ✅ Secretário: vê APENAS filiados da congregação que gerencia
            $congregacaoId = $user->filiado()?->congregacao_id;
            $query->where('congregacao_id', $congregacaoId);
            // Secretário NÃO pode alterar filtros de congregação ou função Secretário
            $request->merge(['congregacao_id' => $congregacaoId]);
        } else {
            // Usuário comum: vê apenas sua congregação
            $query->where('congregacao_id', $user->congregacao_id);
        }

        // 🔍 Filtros de busca (comuns a todos)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('matricula', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('documento', 'LIKE', "%{$search}%");
            });
        }

        // 📊 Filtro por status (comum a todos)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $filiados = $query->orderBy('nome')->paginate(15)->withQueryString();
        
        // 📋 Lista de congregações para o filtro (APENAS para admin)
        $congregacoes = $user->isAdmin() ? Congregacao::all() : null;

        return view('filiados.index', compact('filiados', 'congregacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin pode escolher qualquer congregação
            $congregacoes = Congregacao::all();
        } elseif ($user->isSecretario()) {
            // ✅ Secretário: só pode cadastrar na congregação que gerencia
            $congregacao = $user->filiado()?->congregacao;
            $congregacoes = $congregacao ? collect([$congregacao]) : collect();
        } else {
            // Usuário comum: usa sua própria congregação
            $congregacoes = collect([$user->congregacao]);
        }
        
        return view('filiados.create', compact('congregacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            // ✅ VALIDAÇÃO DA FUNÇÃO: Apenas Admin pode definir 'Secretário'
            $funcaoRules = ['required', 'string', 'max:255', Rule::in(['Membro', 'Diácono', 'Presbítero', 'Missionário'])];
            if ($user->isAdmin()) {
                // Admin pode usar todas as funções, incluindo Secretário
                $funcaoRules = ['required', 'string', 'max:255', Rule::in(['Membro', 'Diácono', 'Presbítero', 'Missionário', 'Secretário'])];
            }

            $validated = $request->validate([
                'matricula' => 'required|integer|unique:filiados,matricula',
                'nome' => 'required|string|max:255',
                'nome_carteira' => 'nullable|string|max:255',
                'congregacao_id' => $user->isAdmin() ? 'required|exists:congregacoes,id' : 'nullable',
                'funcao' => $funcaoRules,
                // ✅ NOVO: Validação do campo user_id
                'user_id' => 'nullable|exists:users,id',
                'status' => 'required|string|in:ativo,inativo,transferido',
                'logradouro' => 'required|string|max:255',
                'endereco' => 'required|string|max:255',
                'numero' => 'required|string|max:255',
                'bairro' => 'required|string|max:255',
                'cep' => 'required|string|max:9',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string|size:2',
                'documento' => 'required|string|max:255|unique:filiados,documento',
                'telefone' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'estadoCivil' => 'required|string|max:255',
                'dataNascimento' => 'required|date|before:today',
                'mae' => 'required|string|max:255',
                'pai' => 'nullable|string|max:255',
                'datCadastro' => 'nullable|date',
                'dataBatismo' => 'nullable|date|after_or_equal:dataNascimento',
                'data_Consagracao' => 'nullable|date',
                
                // ✅ VALIDAÇÃO DE ARQUIVOS
                'arquivo' => [
                    'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120',
                    function ($attribute, $value, $fail) {
                        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
                        if (!in_array($value->getMimeType(), $allowedMimes)) {
                            $fail('O arquivo enviado não é um formato válido (JPG, PNG ou PDF).');
                        }
                    },
                ],
                'cartas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            ], [
                'arquivo.mimes' => 'A foto deve ser JPG, PNG ou PDF.',
                'arquivo.max' => 'A foto não pode exceder 5MB.',
                'arquivo.file' => 'O campo foto deve ser um arquivo válido.',
                'funcao.in' => 'A função selecionada não é válida.',
                'user_id.exists' => 'O usuário selecionado não existe.',
            ]);

            // Definir congregação baseado no nível do usuário
            if ($user->isAdmin()) {
                $validated['congregacao_id'] = $request->congregacao_id;
            } elseif ($user->isSecretario()) {
                $validated['congregacao_id'] = $user->filiado()?->congregacao_id;
            } else {
                $validated['congregacao_id'] = $user->congregacao_id;
            }
            
            // ✅ Definir user_id: se Admin enviou no formulário, usa esse; caso contrário, usa o próprio Admin
            if ($user->isAdmin() && $request->filled('user_id')) {
                $validated['user_id'] = $request->user_id;
            } else {
                $validated['user_id'] = $user->id;
            }
            
            $validated['datCadastro'] = $validated['datCadastro'] ?? now()->format('Y-m-d');

            // ✅ UPLOAD SEGURO DO ARQUIVO 'arquivo'
            if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
                $file = $request->file('arquivo');
                $validated['arquivo'] = $this->storeFileSafely($file, 'filiados/documentos', $user->id);
            }

            if ($request->hasFile('cartas') && $request->file('cartas')->isValid()) {
                $file = $request->file('cartas');
                $validated['cartas'] = $this->storeFileSafely($file, 'filiados/cartas', $user->id);
            }

            unset($validated['congregacao']);

            Filiado::create($validated);

            Log::info('Filiado criado com sucesso', ['filiado_id' => null, 'user_id' => $user->id]);

            return redirect()->route('filiados.index')
                ->with('success', '✅ Filiado cadastrado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Erro de validação ao criar filiado', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', '⚠️ Verifique os campos destacados abaixo.');
                
        } catch (\Exception $e) {
            Log::error('Erro crítico ao criar filiado', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Ocorreu um erro ao salvar. Tente novamente ou contate o suporte.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Filiado $filiado)
    {
        $this->authorizeAccess($filiado);
        return view('filiados.show', compact('filiado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiado $filiado)
    {
        $this->authorizeAccess($filiado);
        
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $congregacoes = Congregacao::all();
        } elseif ($user->isSecretario()) {
            $congregacao = $user->filiado()?->congregacao;
            $congregacoes = $congregacao ? collect([$congregacao]) : collect();
        } else {
            $congregacoes = collect([$user->congregacao]);
        }
        
        return view('filiados.edit', compact('filiado', 'congregacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiado $filiado)
    {
        try {
            $this->authorizeAccess($filiado);
            $user = Auth::user();
            
            // ✅ VALIDAÇÃO DA FUNÇÃO: Apenas Admin pode definir/alterar para 'Secretário'
            $funcaoRules = ['required', 'string', 'max:255', Rule::in(['Membro', 'Diácono', 'Presbítero', 'Missionário'])];
            if ($user->isAdmin()) {
                $funcaoRules = ['required', 'string', 'max:255', Rule::in(['Membro', 'Diácono', 'Presbítero', 'Missionário', 'Secretário'])];
            }

            $validated = $request->validate([
                'matricula' => ['required', 'integer', Rule::unique('filiados')->ignore($filiado->id)],
                'nome' => 'required|string|max:255',
                'nome_carteira' => 'nullable|string|max:255',
                'congregacao_id' => $user->isAdmin() ? 'required|exists:congregacoes,id' : 'nullable',
                'funcao' => $funcaoRules,
                // ✅ NOVO: Validação do campo user_id
                'user_id' => 'nullable|exists:users,id',
                'status' => 'required|string|in:ativo,inativo,transferido',
                'logradouro' => 'required|string|max:255',
                'endereco' => 'required|string|max:255',
                'numero' => 'required|string|max:255',
                'bairro' => 'required|string|max:255',
                'cep' => 'required|string|max:9',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string|size:2',
                'documento' => ['required', 'string', 'max:255', Rule::unique('filiados')->ignore($filiado->id)],
                'telefone' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'estadoCivil' => 'required|string|max:255',
                'dataNascimento' => 'required|date|before:today',
                'mae' => 'required|string|max:255',
                'pai' => 'nullable|string|max:255',
                'datCadastro' => 'nullable|date',
                'dataBatismo' => 'nullable|date',
                'data_Consagracao' => 'nullable|date',
                
                // ✅ VALIDAÇÃO DE ARQUIVOS
                'arquivo' => [
                    'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120',
                    function ($attribute, $value, $fail) {
                        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
                        if (!in_array($value->getMimeType(), $allowedMimes)) {
                            $fail('O arquivo enviado não é um formato válido (JPG, PNG ou PDF).');
                        }
                    },
                ],
                'cartas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            ], [
                'arquivo.mimes' => 'A foto deve ser JPG, PNG ou PDF.',
                'arquivo.max' => 'A foto não pode exceder 5MB.',
                'arquivo.file' => 'O campo foto deve ser um arquivo válido.',
                'funcao.in' => 'A função selecionada não é válida.',
                'user_id.exists' => 'O usuário selecionado não existe.',
            ]);

            // Admin pode alterar congregação; Secretário e comum não
            if ($user->isAdmin() && $request->has('congregacao_id')) {
                $validated['congregacao_id'] = $request->congregacao_id;
            } elseif ($user->isSecretario()) {
                $validated['congregacao_id'] = $user->filiado()?->congregacao_id;
            }
            // Usuário comum mantém a congregacao_id original do filiado (já validado em authorizeAccess)

            // ✅ Respeitar user_id enviado pelo Admin no formulário
            if ($user->isAdmin() && $request->filled('user_id')) {
                $validated['user_id'] = $request->user_id;
            }
            // Se não foi enviado, mantém o user_id original do filiado

            // ✅ UPLOAD SEGURO: arquivo
            if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
                $file = $request->file('arquivo');
                
                if ($filiado->arquivo && Storage::disk('public')->exists($filiado->arquivo)) {
                    try {
                        Storage::disk('public')->delete($filiado->arquivo);
                        Log::info("Arquivo antigo excluído: {$filiado->arquivo}", ['filiado_id' => $filiado->id]);
                    } catch (\Exception $e) {
                        Log::warning("Falha ao excluir arquivo antigo: {$e->getMessage()}", ['filiado_id' => $filiado->id]);
                    }
                }
                
                $validated['arquivo'] = $this->storeFileSafely($file, 'filiados/documentos', $filiado->id);
            }

            if ($request->hasFile('cartas') && $request->file('cartas')->isValid()) {
                $file = $request->file('cartas');
                
                if ($filiado->cartas && Storage::disk('public')->exists($filiado->cartas)) {
                    Storage::disk('public')->delete($filiado->cartas);
                }
                
                $validated['cartas'] = $this->storeFileSafely($file, 'filiados/cartas', $filiado->id);
            }

            unset($validated['congregacao']);

            $filiado->update($validated);

            Log::info('Filiado atualizado com sucesso', ['filiado_id' => $filiado->id, 'user_id' => $user->id]);

            return redirect()->route('filiados.show', $filiado->id)
                ->with('success', '✅ Filiado atualizado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Erro de validação ao atualizar filiado', [
                'filiado_id' => $filiado->id ?? null,
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', '⚠️ Verifique os campos destacados abaixo.');
                
        } catch (\Exception $e) {
            Log::error('Erro crítico ao atualizar filiado', [
                'filiado_id' => $filiado->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Ocorreu um erro ao salvar. Tente novamente ou contate o suporte.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiado $filiado)
    {
        try {
            $this->authorizeAccess($filiado);
            
            // Soft delete: altera status para inativo
            $filiado->update(['status' => 'inativo']);
            
            Log::info('Filiado marcado como inativo', ['filiado_id' => $filiado->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('filiados.index')
                ->with('success', '✅ Filiado removido com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao remover filiado', [
                'filiado_id' => $filiado->id ?? null,
                'message' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', '❌ Ocorreu um erro ao remover o filiado.');
        }
    }

    // 🔒 MÉTODO DE AUTORIZAÇÃO UNIFICADO
    private function authorizeAccess(Filiado $filiado): void
    {
        $user = Auth::user();
        
        // ✅ Admin global: acesso total
        if ($user->isAdmin()) {
            return;
        }
        
        // ✅ Secretário: só acessa filiados da congregação que gerencia
        if ($user->isSecretario()) {
            $congregacaoSecretariado = $user->filiado()?->congregacao_id;
            
            if ($filiado->congregacao_id !== $congregacaoSecretariado) {
                Log::warning('Tentativa de acesso não autorizado por secretário', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'filiado_id' => $filiado->id,
                    'filiado_congregacao' => $filiado->congregacao_id,
                    'secretario_congregacao' => $congregacaoSecretariado
                ]);
                abort(403, 'Você só pode acessar filiados da congregação que gerencia.');
            }
            return;
        }
        
        // ✅ Usuário comum: só acessa filiados da sua congregação
        if ($filiado->congregacao_id !== $user->congregacao_id) {
            Log::warning('Tentativa de acesso não autorizado por usuário comum', [
                'user_id' => $user->id,
                'filiado_id' => $filiado->id,
                'user_congregacao' => $user->congregacao_id,
                'filiado_congregacao' => $filiado->congregacao_id
            ]);
            abort(403, 'Você não tem permissão para acessar este filiado.');
        }
    }

    // ✅ MÉTODO AUXILIAR: Upload seguro com nome único
    private function storeFileSafely($file, string $folder, $referenceId): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Sanitizar nome: remover caracteres especiais
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '_', Str::slug($originalName));
        $safeName = Str::limit($safeName, 50, '');
        
        // Adicionar timestamp e token único para evitar colisões
        $uniqueName = $safeName . '_' . time() . '_' . Str::random(8) . '.' . $extension;
        
        // Caminho final: filiados/documentos/usuario_123_abc12345.jpg
        $path = "{$folder}/{$referenceId}_{$uniqueName}";
        
        // Salvar no disco 'public'
        $file->storeAs($folder, basename($path), 'public');
        
        Log::info("Arquivo salvo com sucesso", ['path' => $path, 'size' => $file->getSize()]);
        
        return $path;
    }
}
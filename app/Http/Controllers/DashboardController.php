<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Filiado;
use App\Models\Congregacao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // ✅ Base query com escopo de permissão (Admin, Secretário ou Comum)
        $query = Filiado::query();
        
        if (!$user->isAdmin()) {
            if ($user->isSecretario()) {
                // Secretário vê apenas filiados da congregação que gerencia
                $query->where('congregacao_id', $user->filiado?->congregacao_id);
            } else {
                // Usuário comum vê apenas filiados da sua congregação
                $query->where('congregacao_id', $user->congregacao_id);
            }
        }
        
        // ✅ Estatísticas (respeitando permissão)
        $totalFiliados = (clone $query)->count();
        $totalAtivos = (clone $query)->where('status', 'ativo')->count();
        $totalInativos = (clone $query)->where('status', 'inativo')->count();
        $totalTransferidos = (clone $query)->where('status', 'transferido')->count();
        
        // ✅ Total de congregações visíveis
        $totalCongregacoes = $user->isAdmin() 
            ? Congregacao::count() 
            : 1;
        
        // ✅ Apenas admin vê estatísticas de secretários
        $totalSecretarios = $user->isAdmin() 
            ? Filiado::where('funcao', 'Secretário')->count() 
            : null;
        
        // ✅ Gráfico: distribuição por congregação (apenas admin)
        $filiadosPorCongregacao = null;
        if ($user->isAdmin()) {
            $filiadosPorCongregacao = Filiado::selectRaw('congregacao_id, count(*) as total')
                ->groupBy('congregacao_id')
                ->with('congregacao:id,nome,cidade,uf')
                ->get();
        }
        
        // ✅ Últimos 5 filiados (respeitando permissão)
        $ultimosFiliados = (clone $query)
            ->with('congregacao:id,nome,cidade,uf')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalFiliados',
            'totalAtivos',
            'totalInativos',
            'totalTransferidos',
            'totalCongregacoes',
            'totalSecretarios',
            'filiadosPorCongregacao',
            'ultimosFiliados'
        ));
    }
}
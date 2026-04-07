<?php

namespace App\Http\ViewModels;

use App\Models\Filiado;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * ViewModel para o Dashboard de Filiados
 * 
 * Responsável por preparar e formatar todos os dados necessários
 * para a view do dashboard, mantendo a lógica de negócio fora do controller.
 * 
 * @package App\Http\ViewModels
 */
class DashboardViewModel
{
    /**
     * Usuário autenticado que está visualizando o dashboard
     */
    public readonly User $user;

    /**
     * Total geral de filiados (respeitando escopo de permissão)
     */
    public readonly int $totalFiliados;

    /**
     * Total de filiados com status 'ativo'
     */
    public readonly int $totalAtivos;

    /**
     * Total de filiados com status 'inativo'
     */
    public readonly int $totalInativos;

    /**
     * Total de filiados com status 'transferido'
     */
    public readonly int $totalTransferidos;

    /**
     * Total de filiados com função 'Secretário' (apenas para Admin)
     */
    public readonly ?int $totalSecretarios;

    /**
     * Coleção com distribuição de filiados por congregação (para gráfico)
     * 
     * @var Collection<int, array{congregacao: ?object, total: int}>
     */
    public readonly Collection $filiadosPorCongregacao;

    /**
     * Últimos filiados cadastrados (lista para tabela)
     * 
     * @var LengthAwarePaginator<Filiado>|Collection<int, Filiado>
     */
    public readonly LengthAwarePaginator|Collection $ultimosFiliados;

    /**
     * Texto contextual exibido no subtítulo do dashboard
     */
    public readonly string $currentUserContext;

    /**
     * Configuração de badge para o papel do usuário
     * 
     * @var array{label: string, color: string, icon: ?string}
     */
    public readonly array $roleBadge;

    /**
     * Cria uma nova instância do ViewModel
     * 
     * @param User $user Usuário autenticado
     * @param int $totalFiliados Total de filiados visíveis
     * @param int $totalAtivos Total de ativos
     * @param int $totalInativos Total de inativos
     * @param int $totalTransferidos Total de transferidos
     * @param int|null $totalSecretarios Total de secretários (null se não for admin)
     * @param Collection $filiadosPorCongregacao Dados para o gráfico
     * @param LengthAwarePaginator|Collection $ultimosFiliados Lista recente
     * @param string $currentUserContext Texto contextual do usuário
     * @param array $roleBadge Configuração do badge de papel
     */
    public function __construct(
        User $user,
        int $totalFiliados,
        int $totalAtivos,
        int $totalInativos,
        int $totalTransferidos,
        ?int $totalSecretarios,
        Collection $filiadosPorCongregacao,
        LengthAwarePaginator|Collection $ultimosFiliados,
        string $currentUserContext,
        array $roleBadge,
    ) {
        $this->user = $user;
        $this->totalFiliados = $totalFiliados;
        $this->totalAtivos = $totalAtivos;
        $this->totalInativos = $totalInativos;
        $this->totalTransferidos = $totalTransferidos;
        $this->totalSecretarios = $totalSecretarios;
        $this->filiadosPorCongregacao = $filiadosPorCongregacao;
        $this->ultimosFiliados = $ultimosFiliados;
        $this->currentUserContext = $currentUserContext;
        $this->roleBadge = $roleBadge;
    }

    /**
     * Factory method para criar o ViewModel a partir do usuário autenticado
     * 
     * Este método encapsula toda a lógica de consulta ao banco de dados,
     * respeitando as regras de escopo por papel de usuário.
     * 
     * @param User|null $user Usuário para construir o ViewModel (default: Auth::user())
     * @param int $limit Quantidade de registros para "Últimos Cadastros"
     * @return self
     */
    public static function make(?User $user = null, int $limit = 10): self
    {
        $user = $user ?? Auth::user();
        
        if (!$user) {
            throw new \RuntimeException('Usuário não autenticado para construir DashboardViewModel');
        }

        // Determina o escopo de consulta baseado no papel do usuário
        $queryScope = self::resolveQueryScope($user);

        // Consultas estatísticas (todas respeitam o escopo)
        $totalFiliados = Filiado::query()->applyScope($queryScope)->count();
        
        $totalAtivos = Filiado::query()
            ->applyScope($queryScope)
            ->where('status', 'ativo')
            ->count();
            
        $totalInativos = Filiado::query()
            ->applyScope($queryScope)
            ->where('status', 'inativo')
            ->count();
            
        $totalTransferidos = Filiado::query()
            ->applyScope($queryScope)
            ->where('status', 'transferido')
            ->count();

        // Total de secretários: apenas administradores têm acesso
        $totalSecretarios = $user->isAdmin()
            ? Filiado::query()->where('funcao', 'Secretário')->count()
            : null;

        // Dados para gráfico de distribuição por congregação (apenas admin)
        $filiadosPorCongregacao = $user->isAdmin()
            ? self::fetchDistribuicaoPorCongregacao()
            : collect();

        // Últimos filiados cadastrados (com relações eager-loaded para performance)
        $ultimosFiliados = Filiado::query()
            ->applyScope($queryScope)
            ->with(['congregacao', 'user'])
            ->latest('created_at')
            ->limit($limit)
            ->get();

        // Contexto textual exibido no header
        $currentUserContext = self::resolveUserContext($user);

        // Configuração do badge de papel
        $roleBadge = self::resolveRoleBadge($user);

        return new self(
            user: $user,
            totalFiliados: $totalFiliados,
            totalAtivos: $totalAtivos,
            totalInativos: $totalInativos,
            totalTransferidos: $totalTransferidos,
            totalSecretarios: $totalSecretarios,
            filiadosPorCongregacao: $filiadosPorCongregacao,
            ultimosFiliados: $ultimosFiliados,
            currentUserContext: $currentUserContext,
            roleBadge: $roleBadge,
        );
    }

    /**
     * Resolve o escopo de consulta baseado no papel do usuário
     * 
     * @param User $user
     * @return string|null Nome do escopo ou null para acesso total
     */
    protected static function resolveQueryScope(User $user): ?string
    {
        return match (true) {
            $user->isAdmin() => null, // Admin vê tudo
            $user->isSecretario() => 'porCongregacao', // Secretário vê apenas sua congregação
            default => 'proprioOuCongregacao', // Usuário comum vê próprios ou da congregação
        };
    }

    /**
     * Busca distribuição de filiados por congregação para o gráfico
     * 
     * @return Collection<int, array{congregacao: ?object, total: int}>
     */
    protected static function fetchDistribuicaoPorCongregacao(): Collection
    {
        return Filiado::query()
            ->selectRaw('congregacao_id, COUNT(*) as total')
            ->with(['congregacao:id,nome'])
            ->groupBy('congregacao_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByDesc('total')
            ->limit(10) // Limita a 10 congregações para legibilidade do gráfico
            ->get()
            ->map(fn ($item) => [
                'congregacao' => $item->congregacao?->only(['id', 'nome']),
                'total' => (int) $item->total,
            ]);
    }

    /**
     * Resolve o texto contextual exibido no subtítulo do dashboard
     * 
     * @param User $user
     * @return string
     */
    protected static function resolveUserContext(User $user): string
    {
        return match (true) {
            $user->isAdmin() => 'Acesso completo a todas as congregações',
            $user->isSecretario() => sprintf(
                'Gerenciando: %s',
                $user->filiado?->congregacao?->nome ?? 'N/A'
            ),
            default => sprintf(
                'Visualizando: %s',
                $user->congregacao?->nome ?? 'N/A'
            ),
        };
    }

    /**
     * Resolve a configuração do badge de papel do usuário
     * 
     * @param User $user
     * @return array{label: string, color: string, icon: ?string}
     */
    protected static function resolveRoleBadge(User $user): array
    {
        return match (true) {
            $user->isAdmin() => [
                'label' => 'Administrador',
                'color' => 'amber',
                'icon' => 'shield-check',
            ],
            $user->isSecretario() => [
                'label' => 'Secretário',
                'color' => 'blue',
                'icon' => 'user-tie',
            ],
            default => [
                'label' => 'Usuário',
                'color' => 'slate',
                'icon' => 'user',
            ],
        };
    }

    // =========================================================================
    // MÉTODOS AUXILIARES PARA A VIEW
    // =========================================================================

    /**
     * Verifica se o gráfico deve ser exibido
     * 
     * @return bool
     */
    public function shouldShowChart(): bool
    {
        return $this->user->isAdmin() 
            && $this->filiadosPorCongregacao->isNotEmpty();
    }

    /**
     * Formata os dados do gráfico para JavaScript (Chart.js)
     * 
     * @return array{labels: string[], datasets: array[]}
     */
    public function getChartData(): array
    {
        return [
            'labels' => $this->filiadosPorCongregacao->map(fn ($item) => 
                $item['congregacao']['nome'] ?? 'Sem congregação'
            )->toArray(),
            'datasets' => [
                [
                    'label' => 'Filiados',
                    'data' => $this->filiadosPorCongregacao->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.85)',
                    'borderColor' => 'rgba(251, 191, 36, 1)',
                ]
            ]
        ];
    }

    /**
     * Verifica se há filiados para exibir na tabela
     * 
     * @return bool
     */
    public function hasFiliados(): bool
    {
        return $this->ultimosFiliados->isNotEmpty();
    }

    /**
     * Obtém a cor CSS para um valor estatístico (para animações/destaques)
     * 
     * @param string $key 'total', 'ativos', 'inativos', 'transferidos'
     * @return string Classe de cor Tailwind ou CSS variable
     */
    public function getStatColor(string $key): string
    {
        return match ($key) {
            'total' => 'text-blue-400',
            'ativos' => 'text-emerald-400',
            'inativos' => 'text-rose-400',
            'transferidos' => 'text-amber-400',
            'secretarios' => 'text-violet-400',
            default => 'text-slate-400',
        };
    }

    /**
     * Obtém o ícone SVG para um card de estatística
     * 
     * @param string $key
     * @return string Nome do componente Blade de ícone
     */
    public function getStatIcon(string $key): string
    {
        return match ($key) {
            'total' => 'users',
            'ativos' => 'check-circle',
            'inativos' => 'x-circle',
            'transferidos' => 'arrows-right-left',
            'secretarios' => 'user-tie',
            default => 'users',
        };
    }

    /**
     * Converte o ViewModel para array (útil para caching ou APIs)
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'total_filiados' => $this->totalFiliados,
            'total_ativos' => $this->totalAtivos,
            'total_inativos' => $this->totalInativos,
            'total_transferidos' => $this->totalTransferidos,
            'total_secretarios' => $this->totalSecretarios,
            'filiados_por_congregacao' => $this->filiadosPorCongregacao->toArray(),
            'ultimos_filiados' => $this->ultimosFiliados->map->toArray(),
            'user_context' => $this->currentUserContext,
            'role_badge' => $this->roleBadge,
            'is_admin' => $this->user->isAdmin(),
            'is_secretario' => $this->user->isSecretario(),
        ];
    }

    /**
     * Serializa o ViewModel para JSON (útil para componentes Livewire/Inertia)
     * 
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
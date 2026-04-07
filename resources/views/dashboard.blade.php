@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">
    
    {{-- Cabeçalho de Boas-vindas --}}
    <header class="dashboard-header">
        <div>
            <h1 class="dashboard-title">
                Olá, {{ auth()->user()->name }} 👋
            </h1>
            <p class="dashboard-subtitle">
                {{-- Badge de papel inline (substitui <x-user-role-badge>) --}}
                @php
                    $roleConfig = match(true) {
                        auth()->user()->isAdmin() => ['label' => 'Administrador', 'class' => 'badge badge-admin', 'icon' => 'fa-shield-alt'],
                        auth()->user()->isSecretario() => ['label' => 'Secretário', 'class' => 'badge badge-secretario', 'icon' => 'fa-user-tie'],
                        default => ['label' => 'Usuário', 'class' => 'badge badge-user', 'icon' => 'fa-user'],
                    };
                @endphp
                <span class="{{ $roleConfig['class'] }}">
                    <i class="fas {{ $roleConfig['icon'] }} me-1"></i>{{ $roleConfig['label'] }}
                </span>
                <span class="text-muted">• {{ $currentUserContext ?? (auth()->user()->isAdmin() ? 'Acesso completo a todas as congregações' : (auth()->user()->isSecretario() ? 'Gerenciando: ' . (auth()->user()->filiado?->congregacao?->nome ?? 'N/A') : 'Visualizando: ' . (auth()->user()->congregacao?->nome ?? 'N/A'))) }}</span>
            </p>
        </div>
        <div class="header-actions">
            {{-- Botão inline (substitui <x-button.primary>) --}}
            <a href="{{ route('filiados.create') }}" class="btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>
                Novo Filiado
            </a>
        </div>
    </header>

    {{-- Alerta contextual para Secretários (inline, substitui <x-alert.info>) --}}
    @if(auth()->user()->isSecretario())
    <div class="alert alert-info alert-dismissible fade show mb-4 reveal" role="alert">
        <i class="fas fa-info-circle me-1"></i>
        <strong>Modo Secretário:</strong> Você está visualizando dados da congregação 
        <strong>{{ auth()->user()->filiado?->congregacao?->nome }}</strong>.
        <br>
        <small class="text-muted">
            <i class="fas fa-lock me-1"></i>Para designar um novo secretário, contate um Administrador.
        </small>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    @endif

    {{-- Cards de Estatísticas (inline, substitui <x-dashboard.stat-card>) --}}
    <section class="stats-grid">
        {{-- Total de Filiados --}}
        <a href="{{ route('filiados.index') }}" class="stat-card stat-card--clickable reveal">
            <div class="stat-card-icon" style="background:rgba(59,130,246,.15)">
                <i class="fas fa-users text-blue-400" style="font-size:24px"></i>
            </div>
            <div class="stat-card-content">
                <span class="stat-card-value text-blue-400" data-count="{{ $totalFiliados ?? 0 }}">{{ number_format($totalFiliados ?? 0) }}</span>
                <span class="stat-card-label">Total de Filiados</span>
            </div>
            <div class="stat-card-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>

        {{-- Filiados Ativos --}}
        <a href="{{ route('filiados.index', ['status' => 'ativo']) }}" class="stat-card stat-card--clickable reveal" style="transition-delay:.1s">
            <div class="stat-card-icon" style="background:rgba(34,197,94,.15)">
                <i class="fas fa-check-circle text-emerald-400" style="font-size:24px"></i>
            </div>
            <div class="stat-card-content">
                <span class="stat-card-value text-success" data-count="{{ $totalAtivos ?? 0 }}">{{ number_format($totalAtivos ?? 0) }}</span>
                <span class="stat-card-label">Filiados Ativos</span>
            </div>
            <div class="stat-card-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>

        {{-- Filiados Inativos --}}
        <a href="{{ route('filiados.index', ['status' => 'inativo']) }}" class="stat-card stat-card--clickable reveal" style="transition-delay:.2s">
            <div class="stat-card-icon" style="background:rgba(239,68,68,.15)">
                <i class="fas fa-times-circle text-rose-400" style="font-size:24px"></i>
            </div>
            <div class="stat-card-content">
                <span class="stat-card-value text-danger" data-count="{{ $totalInativos ?? 0 }}">{{ number_format($totalInativos ?? 0) }}</span>
                <span class="stat-card-label">Filiados Inativos</span>
            </div>
            <div class="stat-card-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>

        {{-- Transferidos --}}
        <a href="{{ route('filiados.index', ['status' => 'transferido']) }}" class="stat-card stat-card--clickable reveal" style="transition-delay:.3s">
            <div class="stat-card-icon" style="background:rgba(234,179,8,.15)">
                <i class="fas fa-exchange-alt text-amber-400" style="font-size:24px"></i>
            </div>
            <div class="stat-card-content">
                <span class="stat-card-value text-warning" data-count="{{ $totalTransferidos ?? 0 }}">{{ number_format($totalTransferidos ?? 0) }}</span>
                <span class="stat-card-label">Transferidos</span>
            </div>
            <div class="stat-card-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>

        {{-- Secretários (APENAS ADMIN) --}}
        @if(auth()->user()->isAdmin())
        <a href="{{ route('filiados.index', ['funcao' => 'Secretário']) }}" class="stat-card stat-card--clickable reveal" style="transition-delay:.4s; border-color: rgba(201,168,76,.3);">
            <div class="stat-card-icon" style="background:rgba(201,168,76,.15)">
                <i class="fas fa-user-tie text-amber-400" style="font-size:24px"></i>
            </div>
            <div class="stat-card-content">
                <span class="stat-card-value text-info" data-count="{{ $totalSecretarios ?? 0 }}">{{ number_format($totalSecretarios ?? 0) }}</span>
                <span class="stat-card-label">Secretários</span>
            </div>
            <div class="stat-card-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>
        @endif
    </section>

    {{-- Seção Principal: Gráfico + Tabela --}}
    <section class="dashboard-main">
        
        {{-- Gráfico (apenas para admin) --}}
        @if(auth()->user()->isAdmin() && !empty($filiadosPorCongregacao) && $filiadosPorCongregacao->count() > 0)
        <div class="card card--chart reveal">
            <div class="card-header">
                <h3 class="card-title">Distribuição por Congregação</h3>
                <span class="card-badge">{{ $filiadosPorCongregacao->count() }} congregações</span>
            </div>
            <div class="card-body">
                <canvas id="graficoCongregacoes" height="120"></canvas>
            </div>
        </div>
        @endif

        {{-- Tabela de Últimos Filiados (inline, substitui <x-card>, <x-table.responsive>, etc.) --}}
        <div class="card card--table reveal" style="{{ auth()->user()->isAdmin() ? 'transition-delay:.1s' : '' }}">
            <div class="card-header">
                <h3 class="card-title">Últimos Cadastros</h3>
                <a href="{{ route('filiados.index') }}" class="btn-link">Ver todos →</a>
            </div>
            <div class="card-body card-body--table">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nome</th>
                                <th>Função</th>
                                @if(auth()->user()->isAdmin())<th>Congregação</th>@endif
                                <th>Status</th>
                                <th>Data</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosFiliados ?? [] as $filiado)
                            <tr class="table-row" data-href="{{ route('filiados.show', $filiado) }}">
                                <td>
                                    <code class="matricula-code">{{ $filiado->matricula }}</code>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        {{-- Avatar inline (substitui <x-avatar>) --}}
                                        <div class="user-avatar">{{ strtoupper(substr($filiado->nome ?? '', 0, 1)) }}</div>
                                        <span class="user-name">{{ $filiado->nome }}</span>
                                    </div>
                                </td>
                                <td>
                                    {{-- Badge de função inline --}}
                                    @if(($filiado->funcao ?? '') === 'Secretário')
                                        <span class="status-badge status-badge--secretario">
                                            <i class="fas fa-user-tie me-1"></i>Secretário
                                        </span>
                                    @else
                                        <span class="text-muted small">{{ $filiado->funcao ?? '—' }}</span>
                                    @endif
                                </td>
                                @if(auth()->user()->isAdmin())
                                <td>
                                    <span class="text-muted">{{ $filiado->congregacao?->nome ?? '—' }}</span>
                                </td>
                                @endif
                                <td>
                                    {{-- Badge de status inline (substitui <x-dashboard.status-badge>) --}}
                                    @php
                                        $statusConfig = [
                                            'ativo' => ['class' => 'status-badge status-badge--success', 'label' => 'Ativo'],
                                            'inativo' => ['class' => 'status-badge status-badge--danger', 'label' => 'Inativo'],
                                            'transferido' => ['class' => 'status-badge status-badge--warning', 'label' => 'Transferido'],
                                        ];
                                        $config = $statusConfig[$filiado->status ?? 'inativo'] ?? $statusConfig['inativo'];
                                    @endphp
                                    <span class="{{ $config['class'] }}">{{ $config['label'] }}</span>
                                </td>
                                <td>
                                    <time datetime="{{ $filiado->created_at }}" class="text-muted">
                                        {{ $filiado->created_at?->format('d/m/Y') ?? '—' }}
                                    </time>
                                </td>
                                <td class="text-end">
                                    <div class="actions-cell">
                                        {{-- Botões de ação inline (substitui <x-button.icon>) --}}
                                        <a href="{{ route('filiados.show', $filiado) }}" class="btn-icon" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin() || 
                                           (auth()->user()->isSecretario() && auth()->user()->filiado?->congregacao_id === $filiado->congregacao_id) ||
                                           auth()->id() == $filiado->user_id)
                                        <a href="{{ route('filiados.edit', $filiado) }}" class="btn-icon" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="empty-state">
                                    {{-- Empty state inline (substitui <x-empty-state>) --}}
                                    <i class="fas fa-users" style="font-size:48px; color:var(--gray-600); margin:0 auto 16px; display:block"></i>
                                    <p class="text-muted">Nenhum filiado cadastrado ainda.</p>
                                    <a href="{{ route('filiados.create') }}" class="btn-primary btn-sm" style="margin-top:12px">Cadastrar primeiro filiado</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Bloco informativo para Admin --}}
        @if(auth()->user()->isAdmin())
        <div class="card card--info reveal" style="transition-delay:.2s">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt text-info me-2"></i>Gerenciamento de Secretários
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-lightbulb me-1"></i>
                    <strong>Como designar um Secretário:</strong>
                    <ol class="mb-0 mt-2 ps-3">
                        <li>Acesse <strong>Lista de Filiados</strong></li>
                        <li>Clique em <strong>Editar</strong></li>
                        <li>Selecione <strong>🔐 Secretário</strong> no campo Função</li>
                        <li>Salve as alterações</li>
                    </ol>
                    <hr class="my-2">
                    <small class="text-muted">
                        <i class="fas fa-lock me-1"></i>
                        Apenas Administradores podem designar ou remover a função de Secretário.
                    </small>
                </div>
            </div>
        </div>
        @endif

    </section>
</div>

{{-- Estilos --}}
@push('styles')
<style>
.dashboard-wrapper{--card-bg:var(--navy-800);--card-border:rgba(255,255,255,.07);--hover-bg:rgba(255,255,255,.03)}.dashboard-header{display:flex;justify-content:space-between;align-items:flex-start;gap:24px;margin-bottom:32px;flex-wrap:wrap}.dashboard-title{font-size:clamp(24px,4vw,32px);font-weight:700;margin:0 0 8px}.dashboard-subtitle{color:var(--gray-400);font-size:14px;margin:0;display:flex;align-items:center;gap:8px;flex-wrap:wrap}.badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em}.badge-admin{background:rgba(201,168,76,.15);color:var(--gold-400);border:1px solid rgba(201,168,76,.3)}.badge-secretario{background:rgba(96,165,250,.15);color:#60a5fa;border:1px solid rgba(96,165,250,.3)}.badge-user{background:rgba(59,130,246,.15);color:#60a5fa;border:1px solid rgba(59,130,246,.3)}.text-muted{color:var(--gray-400)!important}.text-success{color:#4ade80!important}.text-danger{color:#f87171!important}.text-warning{color:#facc15!important}.text-info{color:#60a5fa!important}.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:32px}.stat-card{display:flex;align-items:center;gap:16px;padding:20px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;transition:transform .2s,border-color .2s,background .2s;text-decoration:none;color:inherit}.stat-card--clickable:hover{transform:translateY(-2px);border-color:rgba(201,168,76,.3);background:var(--hover-bg)}.stat-card-icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}.stat-card-content{flex:1;min-width:0}.stat-card-value{display:block;font-size:24px;font-weight:700;line-height:1.2}.stat-card-label{font-size:12px;color:var(--gray-400);margin-top:2px}.stat-card-arrow{opacity:0;transform:translateX(-4px);transition:all .2s;color:var(--gray-600)}.stat-card--clickable:hover .stat-card-arrow{opacity:1;transform:translateX(0);color:var(--gold-400)}.card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;margin-bottom:24px;overflow:hidden}.card-header{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--card-border)}.card-title{font-size:16px;font-weight:600;margin:0}.card-badge{font-size:11px;padding:4px 10px;background:rgba(201,168,76,.1);color:var(--gold-400);border-radius:100px;font-weight:600}.card-body{padding:20px}.btn-link{font-size:13px;color:var(--gold-400);text-decoration:none;font-weight:500;transition:color .2s}.btn-link:hover{color:var(--gold-300)}.table-responsive{overflow-x:auto;-webkit-overflow-scrolling:touch}.table-modern{width:100%;border-collapse:separate;border-spacing:0}.table-modern th{text-align:start;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);padding:12px 16px;border-bottom:1px solid var(--card-border);background:rgba(255,255,255,.02)}.table-modern td{padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.03);font-size:14px;vertical-align:middle}.table-modern tr:last-child td{border-bottom:none}.table-row{transition:background .2s;cursor:pointer}.table-row:hover{background:var(--hover-bg)}.matricula-code{font-family:monospace;font-size:12px;background:rgba(255,255,255,.05);padding:2px 6px;border-radius:4px;color:var(--gray-200)}.user-cell{display:flex;align-items:center;gap:10px}.user-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--navy-600),var(--navy-400));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--gold-300);flex-shrink:0}.user-name{font-weight:500;color:var(--gray-200)}.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:600}.status-badge--success{background:rgba(34,197,94,.1);color:#4ade80}.status-badge--danger{background:rgba(239,68,68,.1);color:#f87171}.status-badge--warning{background:rgba(234,179,8,.1);color:#facc15}.status-badge--secretario{background:rgba(96,165,250,.15);color:#60a5fa;border:1px solid rgba(96,165,250,.3)}.text-end{text-align:end}.actions-cell{display:flex;gap:4px;justify-content:flex-end}.btn-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--gray-400);text-decoration:none;transition:all .2s;border:1px solid transparent}.btn-icon:hover{background:rgba(255,255,255,.05);color:var(--white);border-color:rgba(255,255,255,.1)}.empty-state{text-align:center;padding:40px 20px;color:var(--gray-400)}.reveal{opacity:0;transform:translateY(12px);transition:opacity .5s ease,transform .5s ease}.reveal.visible{opacity:1;transform:translateY(0)}@media(max-width:768px){.dashboard-header{flex-direction:column;align-items:flex-start}.header-actions{width:100%}.stats-grid{grid-template-columns:1fr 1fr}.card-header{flex-direction:column;align-items:flex-start;gap:8px}.table-modern th:nth-child(4),.table-modern td:nth-child(4){display:none}}@media(max-width:480px){.stats-grid{grid-template-columns:1fr}.table-modern th:nth-child(5),.table-modern td:nth-child(5){display:none}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const reveals=document.querySelectorAll('.reveal');
    const observer=new IntersectionObserver((entries)=>{entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('visible');observer.unobserve(entry.target);}});},{threshold:0.1});
    reveals.forEach(el=>observer.observe(el));
    document.querySelectorAll('.table-row[data-href]').forEach(row=>{row.addEventListener('click',(e)=>{if(!e.target.closest('a')&&!e.target.closest('.btn-icon')){window.location.href=row.dataset.href;}});});
    const animateCount=(el,target,duration=1000)=>{let start=0;const increment=target/(duration/16);let current=start;const timer=setInterval(()=>{current+=increment;if(current>=target){el.textContent=target.toLocaleString('pt-BR');clearInterval(timer);}else{el.textContent=Math.floor(current).toLocaleString('pt-BR');}},16);};
    document.querySelectorAll('.stat-card-value[data-count]').forEach(el=>{const target=parseInt(el.dataset.count);if(target>0&&!isNaN(target)){setTimeout(()=>animateCount(el,target),300);}});
});
</script>
@if(auth()->user()->isAdmin() && !empty($filiadosPorCongregacao) && $filiadosPorCongregacao->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const ctx=document.getElementById('graficoCongregacoes');if(!ctx)return;
    const dados=@json($filiadosPorCongregacao);const isDark=true;
    Chart.defaults.color=isDark?'#9aa3b2':'#6b7585';Chart.defaults.borderColor=isDark?'rgba(255,255,255,.08)':'rgba(0,0,0,.1)';Chart.defaults.font.family="'Figtree',sans-serif";Chart.defaults.font.size=12;
    new Chart(ctx.getContext('2d'),{type:'bar',data:{labels:dados.map(item=>{const nome=item.congregacao?.nome||'Sem congregação';return nome.length>18?nome.substring(0,18)+'…':nome;}),datasets:[{label:'Filiados',data:dados.map(item=>item.total),backgroundColor:'rgba(201,168,76,0.85)',borderColor:'rgba(201,168,76,1)',borderWidth:1,borderRadius:6,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:true,animation:{duration:800,easing:'easeOutQuart'},plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(13,21,38,0.95)',titleColor:'#fff',bodyColor:'#9aa3b2',borderColor:'rgba(201,168,76,.3)',borderWidth:1,padding:12,displayColors:false,callbacks:{label:(ctx)=>` ${ctx.parsed.y} filiado${ctx.parsed.y!==1?'s':''}`}}},scales:{x:{grid:{display:false},ticks:{maxRotation:45,minRotation:45,font:{size:11}}},y:{beginAtZero:true,ticks:{stepSize:1,callback:(v)=>Number.isInteger(v)?v:null},grid:{borderDash:[4,4]}}},layout:{padding:{top:8,right:8}}}});
});
</script>
@endif
@endpush
@endsection
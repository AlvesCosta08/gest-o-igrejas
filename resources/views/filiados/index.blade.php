@extends('layouts.app')

@section('title', 'Lista de Filiados')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3 pb-0">
                    <h3 class="card-title mb-0 h5 text-primary">
                        <i class="fas fa-users me-2"></i>Filiados Cadastrados
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('filiados.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Novo Filiado
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- ✅ MENSAGENS DE FEEDBACK (Bootstrap 5) --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif

                    {{-- 🔍 Formulário de Filtros --}}
                    <form method="GET" action="{{ route('filiados.index') }}" class="mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Nome, matrícula, CPF ou email" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            
                            {{-- Filtro de Congregação --}}
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted mb-1">Congregação</label>
                                        <select name="congregacao_id" class="form-select">
                                            <option value="">Todas as Congregações</option>
                                            @foreach($congregacoes ?? [] as $congregacao)
                                                <option value="{{ $congregacao->id }}" 
                                                    {{ request('congregacao_id') == $congregacao->id ? 'selected' : '' }}>
                                                    {{ $congregacao->nome }} - {{ $congregacao->cidade }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif(auth()->user()->isSecretario())
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted mb-1">Congregação</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="text" class="form-control" 
                                                   value="{{ auth()->user()->filiado?->congregacao?->nome ?? 'N/A' }} - {{ auth()->user()->filiado?->congregacao?->cidade }}" 
                                                   disabled>
                                            <input type="hidden" name="congregacao_id" 
                                                   value="{{ auth()->user()->filiado?->congregacao_id }}">
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Apenas sua congregação
                                        </small>
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted mb-1">Congregação</label>
                                        <input type="text" class="form-control" 
                                               value="{{ auth()->user()->congregacao?->nome ?? 'N/A' }}" disabled>
                                        <input type="hidden" name="congregacao_id" 
                                               value="{{ auth()->user()->congregacao_id }}">
                                    </div>
                                @endif
                            @endauth
                            
                            {{-- Filtro de Status --}}
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>✅ Ativo</option>
                                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>❌ Inativo</option>
                                    <option value="transferido" {{ request('status') == 'transferido' ? 'selected' : '' }}>🔄 Transferido</option>
                                </select>
                            </div>
                            
                            {{-- Botões --}}
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filtrar
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('filiados.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-undo me-1"></i>Limpar
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- 📋 Tabela de Filiados --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="60">Foto</th>
                                    <th width="80">Matrícula</th>
                                    <th class="text-start">Nome</th>
                                    <th width="200">Congregação</th>
                                    <th width="120">Função</th>
                                    <th width="100">Status</th>
                                    <th width="140">Telefone</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filiados as $filiado)
                                <tr>
                                    {{-- Foto --}}
                                    <td class="text-center">
                                        <div class="avatar-social" title="Visualizar {{ $filiado->nome }}">
                                            <img src="{{ $filiado->foto_url }}" 
                                                 alt="{{ $filiado->nome }}" 
                                                 class="rounded-circle border border-light shadow-sm"
                                                 loading="lazy"
                                                 width="50" 
                                                 height="50">
                                        </div>
                                    </td>
                                    
                                    {{-- Matrícula --}}
                                    <td class="text-center">
                                        <strong class="text-dark">{{ $filiado->matricula }}</strong>
                                    </td>
                                    
                                    {{-- Nome --}}
                                    <td>
                                        <div class="fw-bold text-dark">{{ $filiado->nome }}</div>
                                        @if($filiado->nome_carteira)
                                            <small class="text-muted d-block">
                                                <i class="fas fa-id-card me-1"></i>{{ $filiado->nome_carteira }}
                                            </small>
                                        @endif
                                    </td>
                                    
                                    {{-- Congregação --}}
                                    <td>
                                        @if($filiado->congregacao)
                                            <span class="badge bg-info text-dark">
                                                <i class="fas fa-church me-1"></i>{{ $filiado->congregacao->nome }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $filiado->congregacao->cidade }}/{{ $filiado->congregacao->uf }}
                                            </small>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Função --}}
                                    <td class="text-center">
                                        @switch($filiado->funcao)
                                            @case('Membro')
                                                <span class="badge bg-secondary">{{ $filiado->funcao }}</span>
                                                @break
                                            @case('Diácono')
                                                <span class="badge bg-primary">{{ $filiado->funcao }}</span>
                                                @break
                                            @case('Presbítero')
                                                <span class="badge bg-success">{{ $filiado->funcao }}</span>
                                                @break
                                            @case('Missionário')
                                                <span class="badge bg-warning text-dark">{{ $filiado->funcao }}</span>
                                                @break
                                            @case('Secretário')
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-user-tie me-1"></i>{{ $filiado->funcao }}
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $filiado->funcao }}</span>
                                        @endswitch
                                    </td>
                                    
                                    {{-- Status --}}
                                    <td class="text-center">
                                        @switch($filiado->status)
                                            @case('ativo')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Ativo
                                                </span>
                                                @break
                                            @case('inativo')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Inativo
                                                </span>
                                                @break
                                            @case('transferido')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-exchange-alt me-1"></i>Transferido
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $filiado->status }}</span>
                                        @endswitch
                                    </td>
                                    
                                    {{-- Telefone --}}
                                    <td class="text-center">
                                        @if($filiado->telefone)
                                            <a href="tel:{{ $filiado->telefone }}" class="text-decoration-none text-dark">
                                                <i class="fas fa-phone-alt text-success me-1"></i>{{ $filiado->telefone }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Ações --}}
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('filiados.show', $filiado->id) }}" 
                                               class="btn btn-info" 
                                               title="Visualizar"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('filiados.edit', $filiado->id) }}" 
                                               class="btn btn-warning" 
                                               title="Editar"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Excluir"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDelete{{ $filiado->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        {{-- Modal de Exclusão (Bootstrap 5) --}}
                                        <div class="modal fade" id="modalDelete{{ $filiado->id }}" 
                                             tabindex="-1" aria-labelledby="modalDeleteLabel{{ $filiado->id }}" 
                                             aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="modalDeleteLabel{{ $filiado->id }}">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Tem certeza que deseja remover este filiado?</strong></p>
                                                        <hr class="my-2">
                                                        <p class="mb-1"><strong>Nome:</strong> {{ $filiado->nome }}</p>
                                                        <p class="mb-0"><strong>Matrícula:</strong> {{ $filiado->matricula }}</p>
                                                        <div class="alert alert-danger mt-3 mb-0 py-2 small">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Esta ação não poderá ser desfeita!
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i>Cancelar
                                                        </button>
                                                        <form action="{{ route('filiados.destroy', $filiado->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash me-1"></i>Sim, excluir
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-3">Nenhum filiado encontrado com os filtros aplicados.</p>
                                            <a href="{{ route('filiados.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i>Cadastrar primeiro filiado
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 📄 Paginação --}}
                    <div class="row mt-3 align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Mostrando 
                                <strong>{{ $filiados->firstItem() ?? 0 }}</strong> a 
                                <strong>{{ $filiados->lastItem() ?? 0 }}</strong> 
                                de <strong>{{ $filiados->total() ?? 0 }}</strong> filiados
                            </small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end justify-content-center mt-2 mt-md-0">
                                {{ $filiados->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: #f8f9fa;
        color: #495057;
        border-bottom: 2px solid #dee2e6 !important;
    }
    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13, 138, 188, 0.05);
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
        font-weight: 500;
    }
    .avatar-social {
        width: 50px;
        height: 50px;
        margin: 0 auto;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        transition: transform 0.2s ease;
    }
    .avatar-social img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-social:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .avatar-social:hover::after {
        content: attr(title);
        position: absolute;
        bottom: -28px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.7rem;
        background: #343a40;
        color: #fff;
        padding: 3px 10px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 20;
        opacity: 0.95;
        pointer-events: none;
    }
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
    @media (max-width: 768px) {
        .table-responsive { font-size: 0.85rem; }
        .table th, .table td { padding: 0.5rem; }
        .avatar-social { width: 40px; height: 40px; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips do Bootstrap 5
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Prevenir envio duplicado de formulários de exclusão
        document.querySelectorAll('form[method="POST"]').forEach(function(form) {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processando...';
                }
            });
        });
    });
</script>
@endpush
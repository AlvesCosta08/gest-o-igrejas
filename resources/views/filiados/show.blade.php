@extends('layouts.app')

@section('title', 'Detalhes do Filiado - ' . $filiado->nome)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3 pb-0">
                    <h3 class="card-title mb-0 h5 text-primary">
                        <i class="fas fa-user-circle me-2"></i>Detalhes do Filiado: {{ $filiado->nome }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('filiados.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                        {{-- ✅ Botão Editar: Admin, Secretário da mesma congregação, ou dono do registro --}}
                        @if(auth()->user()->isAdmin() || 
                           (auth()->user()->isSecretario() && auth()->user()->filiado?->congregacao_id === $filiado->congregacao_id) ||
                           auth()->id() == $filiado->user_id)
                            <a href="{{ route('filiados.edit', $filiado->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                        @endif
                        
                        {{-- ✅ Botão Excluir: Apenas Admin ou Secretário da mesma congregação --}}
                        @if(auth()->user()->isAdmin() || 
                           (auth()->user()->isSecretario() && auth()->user()->filiado?->congregacao_id === $filiado->congregacao_id))
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete">
                                <i class="fas fa-trash me-1"></i>Excluir
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    {{-- ✅ Badge de permissão para secretários (Bootstrap 5) --}}
                    @if(auth()->user()->isSecretario() && auth()->user()->filiado?->congregacao_id === $filiado->congregacao_id)
                        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Modo Secretário:</strong> Você está visualizando um filiado da congregação que gerencia.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif

                    <!-- ✨ CABEÇALHO DE PERFIL ESTILO REDE SOCIAL -->
                    <div class="profile-header text-center mb-4 p-4 bg-gradient-primary text-white rounded">
                        <div class="profile-avatar-container mb-3">
                            <img src="{{ $filiado->foto_url }}" 
                                 alt="{{ $filiado->nome }}" 
                                 class="profile-avatar rounded-circle border border-4 border-white shadow-lg"
                                 loading="lazy"
                                 width="150" 
                                 height="150">
                            @if($filiado->status == 'ativo')
                                <span class="status-badge badge bg-success">
                                    <i class="fas fa-circle me-1"></i>Ativo
                                </span>
                            @endif
                        </div>
                        <h3 class="profile-name mb-1">{{ $filiado->nome }}</h3>
                        @if($filiado->nome_carteira)
                            <p class="profile-nickname mb-2 text-white-50">
                                <i class="fas fa-id-card me-1"></i>{{ $filiado->nome_carteira }}
                            </p>
                        @endif
                        <div class="profile-meta d-flex justify-content-center gap-3 flex-wrap">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-hashtag me-1"></i>Matrícula: {{ $filiado->matricula }}
                            </span>
                            <span class="badge bg-light text-dark">
                                @if($filiado->funcao === 'Secretário')
                                    <i class="fas fa-user-tie text-info me-1"></i>{{ $filiado->funcao }}
                                @else
                                    <i class="fas fa-church me-1"></i>{{ $filiado->funcao }}
                                @endif
                            </span>
                            @if($filiado->congregacao)
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $filiado->congregacao->cidade }}/{{ $filiado->congregacao->uf }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Botões de Ação Rápida no Perfil -->
                        <div class="profile-actions mt-3 d-flex justify-content-center flex-wrap">
                            @if($filiado->telefone)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $filiado->telefone) }}" 
                                   target="_blank" class="btn btn-success btn-sm me-2 mb-1">
                                    <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                </a>
                            @endif
                            @if($filiado->email)
                                <a href="mailto:{{ $filiado->email }}" class="btn btn-info btn-sm me-2 mb-1">
                                    <i class="fas fa-envelope me-1"></i>E-mail
                                </a>
                            @endif
                            <button type="button" class="btn btn-light btn-sm mb-1" onclick="window.print();">
                                <i class="fas fa-print me-1"></i>Imprimir
                            </button>
                        </div>
                    </div>
                    <!-- ✨ FIM DO CABEÇALHO DE PERFIL -->

                    <div class="row">
                        <!-- Coluna 1 - Dados Pessoais -->
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-user text-primary me-2"></i>Dados Pessoais
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="180">Data de Nascimento:</th>
                                            <td>
                                                <i class="fas fa-birthday-cake me-1"></i>
                                                {{ $filiado->dataNascimento ? date('d/m/Y', strtotime($filiado->dataNascimento)) : 'N/A' }}
                                                @php
                                                    if($filiado->dataNascimento) {
                                                        $idade = \Carbon\Carbon::parse($filiado->dataNascimento)->age;
                                                        echo "<br><small class='text-muted'>({$idade} anos)</small>";
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Estado Civil:</th>
                                            <td>
                                                @switch($filiado->estadoCivil)
                                                    @case('Solteiro')
                                                        <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>Solteiro(a)</span>
                                                        @break
                                                    @case('Casado')
                                                        <span class="badge bg-primary"><i class="fas fa-heart me-1"></i>Casado(a)</span>
                                                        @break
                                                    @case('Divorciado')
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-hand-peace me-1"></i>Divorciado(a)</span>
                                                        @break
                                                    @case('Viúvo')
                                                        <span class="badge bg-dark"><i class="fas fa-dove me-1"></i>Viúvo(a)</span>
                                                        @break
                                                    @default
                                                        <span class="text-muted">Não informado</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Filhos:</th>
                                            <td>
                                                @if(isset($filiado->filhos) && $filiado->filhos)
                                                    <i class="fas fa-child me-1"></i>{{ $filiado->filhos }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Dados Eclesiásticos -->
                            <div class="card card-outline card-success mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-church text-success me-2"></i>Dados Eclesiásticos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="180">Congregação:</th>
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
                                        </tr>
                                        <tr>
                                            <th>Data de Cadastro na Igreja:</th>
                                            <td>
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $filiado->datCadastro ? date('d/m/Y', strtotime($filiado->datCadastro)) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Data de Batismo:</th>
                                            <td>
                                                @if($filiado->dataBatismo)
                                                    <i class="fas fa-water me-1"></i>
                                                    {{ date('d/m/Y', strtotime($filiado->dataBatismo)) }}
                                                @else
                                                    <span class="text-muted">Não informado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Data de Consagração:</th>
                                            <td>
                                                @if($filiado->data_Consagracao)
                                                    <i class="fas fa-hands me-1"></i>
                                                    {{ date('d/m/Y', strtotime($filiado->data_Consagracao)) }}
                                                @else
                                                    <span class="text-muted">Não informado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Coluna 2 - Contato, Endereço e Filiação -->
                        <div class="col-md-6">
                            <!-- Contato -->
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-address-card text-info me-2"></i>Contato e Documentos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="180">CPF:</th>
                                            <td>
                                                <i class="fas fa-id-card me-1"></i>{{ $filiado->documento }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Telefone:</th>
                                            <td>
                                                @if($filiado->telefone)
                                                    <a href="tel:{{ $filiado->telefone }}" class="text-decoration-none">
                                                        <i class="fas fa-phone-alt text-success me-1"></i>
                                                        <strong>{{ $filiado->telefone }}</strong>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fab fa-whatsapp text-success me-1"></i>
                                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $filiado->telefone) }}" target="_blank">
                                                            Enviar WhatsApp
                                                        </a>
                                                    </small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>E-mail:</th>
                                            <td>
                                                @if($filiado->email)
                                                    <a href="mailto:{{ $filiado->email }}">
                                                        <i class="fas fa-envelope me-1"></i>{{ $filiado->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Não informado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="card card-outline card-secondary mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-map-marker-alt text-secondary me-2"></i>Endereço
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-light">
                                        <i class="fas fa-location-dot me-1"></i>
                                        {{ $filiado->logradouro }} {{ $filiado->endereco }}, 
                                        <strong>Nº {{ $filiado->numero }}</strong>
                                        <br>
                                        @if($filiado->complemento ?? null)
                                            <i class="fas fa-info-circle me-1"></i>Complemento: {{ $filiado->complemento }}<br>
                                        @endif
                                        <i class="fas fa-neighborhood me-1"></i>{{ $filiado->bairro }}<br>
                                        <i class="fas fa-city me-1"></i>{{ $filiado->cidade }} - {{ $filiado->uf }}<br>
                                        <i class="fas fa-mail-bulk me-1"></i>CEP: {{ $filiado->cep }}
                                    </div>
                                    @if(($filiado->latitude ?? null) && ($filiado->longitude ?? null))
                                    <div class="mt-2">
                                        <a href="https://www.google.com/maps?q={{ $filiado->latitude }},{{ $filiado->longitude }}" 
                                           target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>Ver no Google Maps
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Filiação -->
                            <div class="card card-outline card-warning mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-users text-warning me-2"></i>Filiação
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="180">Nome da Mãe:</th>
                                            <td>
                                                <i class="fas fa-female me-1"></i>
                                                <strong>{{ $filiado->mae }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nome do Pai:</th>
                                            <td>
                                                @if($filiado->pai)
                                                    <i class="fas fa-male me-1"></i>
                                                    <strong>{{ $filiado->pai }}</strong>
                                                @else
                                                    <span class="text-muted">Não informado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Informações do Sistema -->
                            <div class="card card-outline card-dark mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle text-dark me-2"></i>Informações do Sistema
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="180">Cadastrado por:</th>
                                            <td>
                                                <i class="fas fa-user-check me-1"></i>
                                                {{ $filiado->user->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Data de Cadastro:</th>
                                            <td>
                                                <i class="fas fa-calendar-plus me-1"></i>
                                                {{ $filiado->created_at ? $filiado->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Última Atualização:</th>
                                            <td>
                                                <i class="fas fa-edit me-1"></i>
                                                {{ $filiado->updated_at ? $filiado->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documentos Anexados -->
                    @if($filiado->arquivo || $filiado->cartas)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-paperclip text-danger me-2"></i>Documentos Anexados
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($filiado->arquivo)
                                        <div class="col-md-3 col-sm-6 mb-2">
                                            <a href="{{ Storage::url($filiado->arquivo) }}" target="_blank" class="btn btn-info w-100">
                                                <i class="fas fa-id-card me-1"></i>Ver Documento/Foto Original
                                            </a>
                                            <small class="text-muted d-block text-center mt-1">
                                                <i class="fas fa-download me-1"></i>Download
                                            </small>
                                        </div>
                                        @endif
                                        
                                        @if($filiado->cartas)
                                        <div class="col-md-3 col-sm-6 mb-2">
                                            <a href="{{ Storage::url($filiado->cartas) }}" target="_blank" class="btn btn-success w-100">
                                                <i class="fas fa-envelope-open-text me-1"></i>Ver Carta de Recomendação
                                            </a>
                                            <small class="text-muted d-block text-center mt-1">
                                                <i class="fas fa-download me-1"></i>Download
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão (Bootstrap 5) -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalDeleteLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p><strong>Tem certeza que deseja remover este filiado?</strong></p>
                <hr class="my-2">
                <p class="mb-1"><strong>Nome:</strong> {{ $filiado->nome }}</p>
                <p class="mb-1"><strong>Matrícula:</strong> {{ $filiado->matricula }}</p>
                <p class="mb-1"><strong>Função:</strong> {{ $filiado->funcao }}</p>
                <p class="mb-0"><strong>Status:</strong> 
                    @switch($filiado->status)
                        @case('ativo')
                            <span class="badge bg-success">Ativo</span>
                            @break
                        @case('inativo')
                            <span class="badge bg-danger">Inativo</span>
                            @break
                        @case('transferido')
                            <span class="badge bg-warning text-dark">Transferido</span>
                            @break
                        @default
                            <span class="badge bg-secondary">{{ $filiado->status }}</span>
                    @endswitch
                </p>
                <div class="alert alert-danger mt-3 mb-0 py-2 small">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Atenção:</strong> Esta ação não poderá ser desfeita e removerá permanentemente todos os dados do filiado!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form action="{{ route('filiados.destroy', $filiado->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Sim, excluir permanentemente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-outline {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
    .card-outline .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        width: 180px;
    }
    .alert-light {
        border-left: 4px solid #6c757d;
    }
    
    /* ✨ Estilos do Perfil Estilo Rede Social */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0D8ABC 0%, #0a6a94 100%);
    }
    
    .profile-avatar-container {
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
    }
    
    .profile-avatar:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    
    .status-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 20px;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }
    
    .profile-nickname {
        font-size: 1rem;
        margin: 0;
    }
    
    .profile-meta .badge {
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    
    .profile-actions .btn {
        margin: 2px;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 120px;
            height: 120px;
        }
        .profile-name {
            font-size: 1.25rem;
        }
        .profile-meta {
            flex-direction: column;
            align-items: center;
        }
        .profile-meta .badge {
            margin: 2px 0;
        }
    }
    
    /* Impressão */
    @media print {
        .card-tools, .btn-group, .modal, .btn, .profile-actions, .alert-dismissible .btn-close {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .profile-header {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #0D8ABC;
        }
        .profile-avatar {
            border-color: #0D8ABC !important;
        }
        body {
            padding: 0;
            margin: 0;
            font-size: 12pt;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevenção de envio duplicado no modal de exclusão
        const deleteForm = document.querySelector('#modalDelete form');
        if(deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if(submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processando...';
                }
            });
        }
    });
</script>
@endpush
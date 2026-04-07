@extends('layouts.app')

@section('title', 'Novo Filiado')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-3 pb-0">
                    <h3 class="card-title mb-0 h5 text-success">
                        <i class="fas fa-user-plus me-2"></i>Cadastrar Novo Filiado
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('filiados.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route('filiados.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      id="formCriarFiliado">
                    @csrf

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
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>Por favor, corrija os erros abaixo.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                            </div>
                        @endif

                        {{-- ✨ PREVIEW DE FOTO --}}
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="avatar-edit-container">
                                    <div class="avatar-preview-wrapper" 
                                         id="avatarWrapper"
                                         title="Clique para adicionar foto"
                                         style="cursor: pointer;"
                                         onclick="document.getElementById('arquivo').click()">
                                        <img src="https://ui-avatars.com/api/?name=NOVO&background=28A745&color=fff&size=150&bold=true" 
                                             alt="Nova foto" 
                                             id="fotoPreview"
                                             class="avatar-preview rounded-circle border border-4 border-white shadow"
                                             loading="lazy"
                                             draggable="false"
                                             style="pointer-events: none;">
                                        <label for="arquivo" 
                                               class="avatar-edit-btn" 
                                               title="Escolher foto"
                                               style="cursor: pointer;"
                                               onclick="event.stopPropagation();">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                    </div>
                                    <p class="mt-2 mb-0 text-muted small">
                                        <i class="fas fa-info-circle me-1"></i>Clique na foto ou no ícone <strong>📷</strong> para adicionar
                                    </p>
                                </div>
                                
                                <input type="file" 
                                       name="arquivo" 
                                       id="arquivo"
                                       class="d-none @error('arquivo') is-invalid @enderror"
                                       accept="image/jpeg,image/png,application/pdf"
                                       data-max-size="5120"
                                       aria-describedby="arquivoHelp"
                                       onchange="handleFileSelect(this)">
                                
                                <div id="arquivoNome" class="mt-2 text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>Nenhum arquivo selecionado
                                </div>
                                
                                <small id="arquivoHelp" class="form-text text-muted d-block mt-1">
                                    <i class="fas fa-info-circle me-1"></i>Formatos: JPG, PNG ou PDF. Máx: 5MB.
                                </small>
                                
                                @error('arquivo')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- ===== DADOS PESSOAIS ===== --}}
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-user text-primary me-2"></i>Dados Pessoais</h5>
                                <hr class="mt-0">
                            </div>

                            <div class="col-md-2">
                                <label for="matricula" class="form-label">Matrícula <span class="text-danger">*</span></label>
                                <input type="number" name="matricula" id="matricula"
                                       class="form-control @error('matricula') is-invalid @enderror"
                                       value="{{ old('matricula') }}" required>
                                @error('matricula')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-5">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" name="nome" id="nome"
                                       class="form-control @error('nome') is-invalid @enderror"
                                       value="{{ old('nome') }}" required
                                       placeholder="Nome conforme documento">
                                @error('nome')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-3">
                                <label for="nome_carteira" class="form-label">Nome na Carteirinha <small class="text-muted">(opcional)</small></label>
                                <input type="text" name="nome_carteira" id="nome_carteira"
                                       class="form-control @error('nome_carteira') is-invalid @enderror"
                                       value="{{ old('nome_carteira') }}"
                                       placeholder="Nome abreviado">
                                @error('nome_carteira')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-2">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>✅ Ativo</option>
                                    <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>❌ Inativo</option>
                                    <option value="transferido" {{ old('status') == 'transferido' ? 'selected' : '' }}>🔄 Transferido</option>
                                </select>
                                @error('status')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            {{-- Congregação - COM RESTRIÇÃO PARA SECRETÁRIO --}}
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="col-md-4">
                                        <label for="congregacao_id" class="form-label">Congregação <span class="text-danger">*</span></label>
                                        <select name="congregacao_id" id="congregacao_id"
                                                class="form-select @error('congregacao_id') is-invalid @enderror" required>
                                            <option value="">Selecione...</option>
                                            @foreach($congregacoes as $congregacao)
                                                <option value="{{ $congregacao->id }}"
                                                    {{ old('congregacao_id') == $congregacao->id ? 'selected' : '' }}>
                                                    {{ $congregacao->nome }} — {{ $congregacao->cidade }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('congregacao_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    </div>
                                @elseif(auth()->user()->isSecretario())
                                    <input type="hidden" name="congregacao_id" value="{{ auth()->user()->filiado?->congregacao_id }}">
                                    <div class="col-md-4">
                                        <label class="form-label">Congregação <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                   value="{{ auth()->user()->filiado?->congregacao?->nome ?? 'N/A' }} — {{ auth()->user()->filiado?->congregacao?->cidade }}" 
                                                   disabled>
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Secretários só podem cadastrar em sua congregação
                                        </small>
                                    </div>
                                @else
                                    <input type="hidden" name="congregacao_id" value="{{ auth()->user()->congregacao_id }}">
                                    <div class="col-md-4">
                                        <label class="form-label">Congregação</label>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->congregacao?->nome ?? 'N/A' }}" disabled>
                                    </div>
                                @endif
                            @endauth

                            <div class="col-md-4">
                                <label for="funcao" class="form-label">Função <span class="text-danger">*</span></label>
                                <select name="funcao" id="funcao" class="form-select @error('funcao') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    <option value="Membro" {{ old('funcao') == 'Membro' ? 'selected' : '' }}>Membro</option>
                                    <option value="Diácono" {{ old('funcao') == 'Diácono' ? 'selected' : '' }}>Diácono</option>
                                    <option value="Presbítero" {{ old('funcao') == 'Presbítero' ? 'selected' : '' }}>Presbítero</option>
                                    <option value="Missionário" {{ old('funcao') == 'Missionário' ? 'selected' : '' }}>Missionário</option>
                                    @if(auth()->user()->isAdmin())
                                        <option value="Secretário" {{ old('funcao') == 'Secretário' ? 'selected' : '' }}>🔐 Secretário</option>
                                    @endif
                                </select>
                                @error('funcao')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="estadoCivil" class="form-label">Estado Civil <span class="text-danger">*</span></label>
                                <select name="estadoCivil" id="estadoCivil" class="form-select @error('estadoCivil') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    <option value="Solteiro" {{ old('estadoCivil') == 'Solteiro' ? 'selected' : '' }}>Solteiro(a)</option>
                                    <option value="Casado" {{ old('estadoCivil') == 'Casado' ? 'selected' : '' }}>Casado(a)</option>
                                    <option value="Divorciado" {{ old('estadoCivil') == 'Divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                    <option value="Viúvo" {{ old('estadoCivil') == 'Viúvo' ? 'selected' : '' }}>Viúvo(a)</option>
                                </select>
                                @error('estadoCivil')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- ✅ VÍNCULO COM USUÁRIO (APENAS PARA ADMIN) --}}
                        @if(auth()->user()->isAdmin())
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-lock text-primary me-2"></i>Acesso ao Sistema</h5>
                                <hr class="mt-0">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">
                                    Usuário Responsável pelo Acesso
                                    <small class="text-muted">(opcional - para Secretários)</small>
                                </label>
                                <select name="user_id" id="user_id" 
                                        class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">— Sem vínculo —</option>
                                    @foreach(\App\Models\User::where('nivel', 'user')->orderBy('name')->get() as $usr)
                                        <option value="{{ $usr->id }}" {{ old('user_id') == $usr->id ? 'selected' : '' }}>
                                            {{ $usr->name }} ({{ $usr->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Selecione o usuário que fará login como este filiado.
                                </small>
                                @error('user_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <div class="alert alert-info mt-4 mb-0">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    <strong>Dica:</strong> Se este filiado for Secretário, 
                                    vincule ao usuário criado para ele. Assim ele poderá acessar o sistema.
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- ✅ FIM DO VÍNCULO COM USUÁRIO --}}

                        {{-- ===== FILIAÇÃO ===== --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-users text-primary me-2"></i>Filiação</h5>
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-6">
                                <label for="mae" class="form-label">Nome da Mãe <span class="text-danger">*</span></label>
                                <input type="text" name="mae" id="mae" class="form-control @error('mae') is-invalid @enderror"
                                       value="{{ old('mae') }}" required>
                                @error('mae')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pai" class="form-label">Nome do Pai <small class="text-muted">(opcional)</small></label>
                                <input type="text" name="pai" id="pai" class="form-control @error('pai') is-invalid @enderror"
                                       value="{{ old('pai') }}">
                                @error('pai')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- ===== DOCUMENTOS E CONTATO ===== --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-id-card text-primary me-2"></i>Documentos e Contato</h5>
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-3">
                                <label for="documento" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" name="documento" id="documento" class="form-control @error('documento') is-invalid @enderror"
                                       value="{{ old('documento') }}" placeholder="000.000.000-00" maxlength="14" required>
                                @error('documento')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                <input type="text" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror"
                                       value="{{ old('telefone') }}" placeholder="(00) 00000-0000" maxlength="15" required>
                                @error('telefone')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="email" class="form-label">Email <small class="text-muted">(opcional)</small></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="exemplo@email.com">
                                @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="dataNascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                                <input type="date" name="dataNascimento" id="dataNascimento" class="form-control @error('dataNascimento') is-invalid @enderror"
                                       value="{{ old('dataNascimento') }}" required>
                                @error('dataNascimento')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- ===== ENDEREÇO ===== --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>Endereço</h5>
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-2">
                                <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="cep" id="cep" class="form-control @error('cep') is-invalid @enderror"
                                           value="{{ old('cep') }}" placeholder="00000-000" maxlength="9" required>
                                    <span class="input-group-text" id="cep-loading" style="display:none">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Digite o CEP para preencher automaticamente.</small>
                                @error('cep')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-2">
                                <label for="logradouro" class="form-label">Logradouro <span class="text-danger">*</span></label>
                                <select name="logradouro" id="logradouro" class="form-select @error('logradouro') is-invalid @enderror" required>
                                    <option value="Rua" {{ old('logradouro') == 'Rua' ? 'selected' : '' }}>Rua</option>
                                    <option value="Avenida" {{ old('logradouro') == 'Avenida' ? 'selected' : '' }}>Avenida</option>
                                    <option value="Praça" {{ old('logradouro') == 'Praça' ? 'selected' : '' }}>Praça</option>
                                    <option value="Travessa" {{ old('logradouro') == 'Travessa' ? 'selected' : '' }}>Travessa</option>
                                    <option value="Estrada" {{ old('logradouro') == 'Estrada' ? 'selected' : '' }}>Estrada</option>
                                    <option value="Sítio" {{ old('logradouro') == 'Sítio' ? 'selected' : '' }}>Sítio</option>
                                </select>
                                @error('logradouro')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="endereco" class="form-label">Endereço <span class="text-danger">*</span></label>
                                <input type="text" name="endereco" id="endereco" class="form-control @error('endereco') is-invalid @enderror"
                                       value="{{ old('endereco') }}" required placeholder="Preenchido pelo CEP">
                                @error('endereco')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-2">
                                <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                                <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero') }}" required placeholder="Ex: 123 ou S/N">
                                @error('numero')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-2">
                                <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                                <input type="text" name="bairro" id="bairro" class="form-control @error('bairro') is-invalid @enderror"
                                       value="{{ old('bairro') }}" required>
                                @error('bairro')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror"
                                       value="{{ old('cidade') }}" required>
                                @error('cidade')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-1">
                                <label for="uf" class="form-label">UF <span class="text-danger">*</span></label>
                                <select name="uf" id="uf" class="form-select @error('uf') is-invalid @enderror" required>
                                    <option value="">--</option>
                                    @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $estado)
                                        <option value="{{ $estado }}" {{ old('uf') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </select>
                                @error('uf')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- ===== DATAS ECLESIÁSTICAS ===== --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i>Datas Eclesiásticas</h5>
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-3">
                                <label for="datCadastro" class="form-label">Data de Cadastro na Igreja</label>
                                <input type="date" name="datCadastro" id="datCadastro" class="form-control @error('datCadastro') is-invalid @enderror"
                                       value="{{ old('datCadastro', date('Y-m-d')) }}">
                                <small class="form-text text-muted">Data de entrada na congregação.</small>
                                @error('datCadastro')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="dataBatismo" class="form-label">Data de Batismo <small class="text-muted">(opcional)</small></label>
                                <input type="date" name="dataBatismo" id="dataBatismo" class="form-control @error('dataBatismo') is-invalid @enderror"
                                       value="{{ old('dataBatismo') }}">
                                @error('dataBatismo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="data_Consagracao" class="form-label">Data de Consagração <small class="text-muted">(opcional)</small></label>
                                <input type="date" name="data_Consagracao" id="data_Consagracao" class="form-control @error('data_Consagracao') is-invalid @enderror"
                                       value="{{ old('data_Consagracao') }}">
                                <small class="form-text text-muted">Apenas para diáconos, presbíteros e missionários.</small>
                                @error('data_Consagracao')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- ===== CARTA DE RECOMENDAÇÃO ===== --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-paperclip text-primary me-2"></i>Documentos Anexos</h5>
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-6">
                                <label for="cartas" class="form-label"><i class="fas fa-envelope-open-text me-1"></i>Carta de Recomendação <small class="text-muted">(opcional)</small></label>
                                <input type="file" name="cartas" id="cartas" class="form-control @error('cartas') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="form-text text-muted"><i class="fas fa-info-circle text-info me-1"></i>Formatos: PDF, DOC, DOCX, JPG, PNG. Máx: 10MB.</small>
                                @error('cartas')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="card-footer d-flex gap-2">
                        <button type="submit" class="btn btn-success" id="btnSubmit">
                            <i class="fas fa-save me-1"></i><span id="btnSubmitText">Cadastrar Filiado</span>
                        </button>
                        <a href="{{ route('filiados.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
    
    window.handleFileSelect = function(input) {
        const preview = document.getElementById('fotoPreview');
        const nomeArquivo = document.getElementById('arquivoNome');
        const file = input?.files?.[0];
        if (!file || !preview || !nomeArquivo) return;
        
        if (file.size > MAX_FILE_SIZE) {
            showAlert('danger', `❌ Arquivo muito grande. Máximo: 5MB. Seu arquivo: ${(file.size/1024/1024).toFixed(2)}MB`);
            input.value = '';
            nomeArquivo.innerHTML = '<i class="fas fa-times text-danger me-1"></i>Seleção cancelada';
            return;
        }
        if (!ALLOWED_TYPES.includes(file.type)) {
            showAlert('danger', `❌ Formato não permitido: ${file.type}. Use JPG, PNG ou PDF.`);
            input.value = '';
            nomeArquivo.innerHTML = '<i class="fas fa-times text-danger me-1"></i>Seleção cancelada';
            return;
        }
        
        nomeArquivo.innerHTML = `<i class="fas fa-check text-success me-1"></i><strong>Selecionado:</strong> ${file.name} <small class="text-muted">(${(file.size/1024).toFixed(0)}KB)</small>`;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('border-success');
                preview.classList.remove('border-white');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = 'https://ui-avatars.com/api/?name=PDF&background=DC3545&color=fff&size=150&bold=true';
            preview.classList.add('border-warning');
            preview.classList.remove('border-white');
        }
    };
    
    window.showAlert = function(type, message) {
        document.querySelectorAll('.alert-custom').forEach(el => el.remove());
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show alert-custom mb-3`;
        alert.role = 'alert';
        alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>`;
        const cardBody = document.querySelector('.card-body');
        if (cardBody) cardBody.insertBefore(alert, cardBody.firstChild);
        setTimeout(() => { if (alert.parentNode) alert.remove(); }, 5000);
    };
    
    // Máscaras
    const cpfInput = document.getElementById('documento');
    if(cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
            else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
            else if (v.length > 3) v = v.replace(/(\d{3})(\d{0,3})/, '$1.$2');
            e.target.value = v;
        });
    }
    
    const telefoneInput = document.getElementById('telefone');
    if(telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10) v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            else if (v.length > 6) v = v.replace(/(\d{2})(\d{4,5})(\d{0,4})/, '($1) $2-$3');
            else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            else if (v.length > 0) v = v.replace(/(\d{0,2})/, '($1');
            e.target.value = v;
        });
    }
    
    const cepInput = document.getElementById('cep');
    if(cepInput) {
        cepInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '').slice(0, 8);
            if (v.length > 5) v = v.replace(/(\d{5})(\d{0,3})/, '$1-$2');
            e.target.value = v;
            const digits = v.replace(/\D/g, '');
            if (digits.length === 8) buscarCep(digits);
        });
    }
    
    function buscarCep(cep) {
        const loading = document.getElementById('cep-loading');
        if(loading) loading.style.display = 'flex';
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if(loading) loading.style.display = 'none';
                if (data.erro) { document.getElementById('cep')?.classList.add('is-invalid'); return; }
                document.getElementById('cep')?.classList.remove('is-invalid');
                const setVal = (id, val) => { const el = document.getElementById(id); if(el) el.value = val || ''; };
                setVal('endereco', data.logradouro);
                setVal('bairro', data.bairro);
                setVal('cidade', data.localidade);
                const ufSelect = document.getElementById('uf');
                if(ufSelect && data.uf) {
                    for (let opt of ufSelect.options) { if (opt.value === data.uf) { opt.selected = true; break; } }
                }
                const logradouroSelect = document.getElementById('logradouro');
                if(logradouroSelect && data.logradouro) {
                    const tipos = ['Avenida', 'Praça', 'Travessa', 'Estrada', 'Sítio', 'Rua'];
                    let tipoEncontrado = 'Rua';
                    for (const tipo of tipos) {
                        if (data.logradouro.toLowerCase().startsWith(tipo.toLowerCase())) {
                            tipoEncontrado = tipo;
                            setVal('endereco', data.logradouro.substring(tipo.length).trim());
                            break;
                        }
                    }
                    for (let opt of logradouroSelect.options) { if (opt.value === tipoEncontrado) { opt.selected = true; break; } }
                }
                document.getElementById('numero')?.focus();
            })
            .catch(() => { if(loading) loading.style.display = 'none'; });
    }
    
    // Validação do formulário
    const form = document.getElementById('formCriarFiliado');
    if (form) {
        form.addEventListener('submit', function(e) {
            const arquivoInput = document.getElementById('arquivo');
            const file = arquivoInput?.files?.[0];
            if (file) {
                if (file.size > MAX_FILE_SIZE) { e.preventDefault(); showAlert('danger', '⚠️ O arquivo excede o limite de 5MB.'); return false; }
                if (!ALLOWED_TYPES.includes(file.type)) { e.preventDefault(); showAlert('danger', '⚠️ Formato não permitido. Use JPG, PNG ou PDF.'); return false; }
            }
            const funcao = document.getElementById('funcao')?.value;
            const dataConsagracao = document.getElementById('data_Consagracao')?.value;
            if ((funcao === 'Diácono' || funcao === 'Presbítero' || funcao === 'Missionário') && !dataConsagracao) {
                e.preventDefault(); showAlert('warning', '⚠️ Data de consagração é obrigatória para esta função.');
                document.getElementById('data_Consagracao')?.focus(); return false;
            }
            const dataBatismo = document.getElementById('dataBatismo')?.value;
            const dataNascimento = document.getElementById('dataNascimento')?.value;
            if (dataBatismo && dataNascimento && new Date(dataBatismo) <= new Date(dataNascimento)) {
                e.preventDefault(); showAlert('warning', '⚠️ A data de batismo deve ser posterior à data de nascimento.');
                document.getElementById('dataBatismo')?.focus(); return false;
            }
            const btn = document.getElementById('btnSubmit');
            if (btn) { btn.disabled = true; const txt = document.getElementById('btnSubmitText'); if (txt) txt.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Salvando...'; }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .card-header .card-title { font-size: 1.25rem; font-weight: 500; }
    .form-label { font-weight: 500; }
    .alert { font-size: 0.9rem; }
    .alert a { text-decoration: none; }
    .alert a:hover { text-decoration: underline; }
    .avatar-edit-container { display: inline-block; text-align: center; }
    .avatar-preview-wrapper { position: relative; display: inline-block; cursor: pointer; outline: none; }
    .avatar-preview { width: 120px; height: 120px; object-fit: cover; transition: all 0.3s ease; background: #f8f9fa; user-select: none; -webkit-user-drag: none; }
    .avatar-preview-wrapper:hover .avatar-preview { transform: scale(1.03); box-shadow: 0 8px 25px rgba(0,0,0,0.2); filter: brightness(0.95); }
    .avatar-edit-btn { position: absolute; bottom: 5px; right: 5px; width: 32px; height: 32px; background: #28A745; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: background 0.2s ease, transform 0.2s ease; cursor: pointer; z-index: 20; pointer-events: auto; }
    .avatar-edit-btn:hover { background: #218838; transform: scale(1.1); }
    .avatar-edit-btn:active { transform: scale(0.95); }
    input[type="file"]#arquivo { position: absolute; left: -9999px; opacity: 0; width: 1px; height: 1px; }
    @media (max-width: 768px) { .avatar-preview { width: 100px; height: 100px; } .avatar-edit-btn { width: 28px; height: 28px; font-size: 0.8rem; } }
    @media print { .card-tools, .btn, .alert-dismissible .btn-close { display: none !important; } .card { border: none !important; box-shadow: none !important; } }
</style>
@endpush
@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Usuário: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required autofocus>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="congregacao_id">Congregação <span class="text-danger">*</span></label>
                                    <select name="congregacao_id" id="congregacao_id"
                                            class="form-control @error('congregacao_id') is-invalid @enderror" required>
                                        <option value="">Selecione...</option>
                                        @foreach($congregacoes as $congregacao)
                                            <option value="{{ $congregacao->id }}"
                                                {{ old('congregacao_id', $user->congregacao_id) == $congregacao->id ? 'selected' : '' }}>
                                                {{ $congregacao->nome }} — {{ $congregacao->cidade }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('congregacao_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nivel">Nível de Acesso <span class="text-danger">*</span></label>
                                    <select name="nivel" id="nivel"
                                            class="form-control @error('nivel') is-invalid @enderror" required>
                                        <option value="user"  {{ old('nivel', $user->nivel) == 'user'  ? 'selected' : '' }}>Usuário</option>
                                        <option value="admin" {{ old('nivel', $user->nivel) == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                    @error('nivel')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <p class="text-muted">
                                    <i class="fas fa-lock"></i>
                                    Deixe os campos de senha em branco para manter a senha atual.
                                </p>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Nova Senha</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Mínimo 8 caracteres.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" autocomplete="new-password">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar Usuário
                        </button>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Editar Congregação')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Congregação: {{ $congregacao->nome }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('congregacoes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route('congregacoes.update', $congregacao->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nome">Nome da Congregação <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" id="nome"
                                           class="form-control @error('nome') is-invalid @enderror"
                                           value="{{ old('nome', $congregacao->nome) }}" required autofocus>
                                    @error('nome')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cidade">Cidade <span class="text-danger">*</span></label>
                                    <input type="text" name="cidade" id="cidade"
                                           class="form-control @error('cidade') is-invalid @enderror"
                                           value="{{ old('cidade', $congregacao->cidade) }}" required>
                                    @error('cidade')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" name="telefone" id="telefone"
                                           class="form-control @error('telefone') is-invalid @enderror"
                                           value="{{ old('telefone', $congregacao->telefone) }}"
                                           placeholder="(00) 00000-0000">
                                    @error('telefone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="endereco">Endereço</label>
                                    <input type="text" name="endereco" id="endereco"
                                           class="form-control @error('endereco') is-invalid @enderror"
                                           value="{{ old('endereco', $congregacao->endereco) }}"
                                           placeholder="Rua, número, bairro">
                                    @error('endereco')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar Congregação
                        </button>
                        <a href="{{ route('congregacoes.show', $congregacao->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>
                        <a href="{{ route('congregacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- resources/views/congregacoes/show.blade.php --}}
@extends('layouts.app')

@section('title', $congregacao->nome)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $congregacao->nome }}</h1>
        <a href="{{ route('congregacoes.index') }}" class="btn btn-secondary">
            ← Voltar
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Informações da Congregação</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Cidade</dt>
                <dd class="col-sm-9">{{ $congregacao->cidade }}</dd>

                <dt class="col-sm-3">Endereço</dt>
                <dd class="col-sm-9">{{ $congregacao->endereco }}</dd>

                <dt class="col-sm-3">Telefone</dt>
                <dd class="col-sm-9">{{ $congregacao->telefone }}</dd>

                <dt class="col-sm-3">Criada em</dt>
                <dd class="col-sm-9">{{ $congregacao->created_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filiados</h5>
                    <span class="badge bg-primary">{{ $congregacao->filiados_count }}</span>
                </div>
                <div class="card-body">
                    @forelse($congregacao->filiados as $filiado)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>{{ $filiado->nome }}</span>
                            <small class="text-muted">{{ $filiado->status }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum filiado cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuários</h5>
                    <span class="badge bg-secondary">{{ $congregacao->users_count }}</span>
                </div>
                <div class="card-body">
                    @forelse($congregacao->users as $user)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>{{ $user->name }}</span>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum usuário vinculado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('congregacoes.edit', $congregacao) }}" class="btn btn-warning">
            Editar
        </a>
        <form action="{{ route('congregacoes.destroy', $congregacao) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta congregação?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Excluir</button>
        </form>
    </div>
</div>
@endsection
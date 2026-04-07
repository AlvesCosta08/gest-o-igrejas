@extends('layouts.app')

@section('title', 'Congregações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Congregações Cadastradas</h3>
                    <div class="card-tools">
                        <a href="{{ route('congregacoes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Congregação
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Filtro --}}
                    <form method="GET" action="{{ route('congregacoes.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Buscar por nome ou cidade"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('congregacoes.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-undo"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Cidade</th>
                                    <th>Endereço</th>
                                    <th>Telefone</th>
                                    <th class="text-center">Filiados</th>
                                    <th class="text-center">Usuários</th>
                                    <th width="150" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($congregacoes as $congregacao)
                                <tr>
                                    <td>{{ $congregacao->id }}</td>
                                    <td><strong>{{ $congregacao->nome }}</strong></td>
                                    <td>{{ $congregacao->cidade }}</td>
                                    <td>{{ $congregacao->endereco ?? '—' }}</td>
                                    <td>{{ $congregacao->telefone ?? '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $congregacao->filiados_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">{{ $congregacao->users_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('congregacoes.show', $congregacao->id) }}"
                                           class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('congregacoes.edit', $congregacao->id) }}"
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('congregacoes.destroy', $congregacao->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta congregação?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir"
                                                {{ $congregacao->filiados_count > 0 || $congregacao->users_count > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhuma congregação encontrada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $congregacoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
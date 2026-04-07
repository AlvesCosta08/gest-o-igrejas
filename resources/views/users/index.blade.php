@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usuários do Sistema</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Novo Usuário
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Buscar por nome ou email"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="congregacao_id" class="form-control">
                                    <option value="">Todas as Congregações</option>
                                    @foreach($congregacoes as $congregacao)
                                        <option value="{{ $congregacao->id }}"
                                            {{ request('congregacao_id') == $congregacao->id ? 'selected' : '' }}>
                                            {{ $congregacao->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="nivel" class="form-control">
                                    <option value="">Todos os Níveis</option>
                                    <option value="admin" {{ request('nivel') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="user"  {{ request('nivel') == 'user'  ? 'selected' : '' }}>Usuário</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-undo"></i>
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
                                    <th>Email</th>
                                    <th>Congregação</th>
                                    <th>Nível</th>
                                    <th>Cadastrado em</th>
                                    <th width="130" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge badge-success ml-1">Você</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->congregacao->nome ?? '—' }}</td>
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="badge badge-danger">Administrador</span>
                                        @else
                                            <span class="badge badge-secondary">Usuário</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('users.show', $user->id) }}"
                                           class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
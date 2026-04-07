@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i> {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge badge-success ml-1">Você</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="180">Nome</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Congregação</th>
                            <td>
                                @if($user->congregacao)
                                    <a href="{{ route('congregacoes.show', $user->congregacao->id) }}">
                                        {{ $user->congregacao->nome }}
                                    </a>
                                    — {{ $user->congregacao->cidade }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Nível de Acesso</th>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge badge-danger">Administrador</span>
                                @else
                                    <span class="badge badge-secondary">Usuário</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Cadastrado em</th>
                            <td>{{ $user->created_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última atualização</th>
                            <td>{{ $user->updated_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                    </table>
                </div>

                @if($user->id !== auth()->id())
                <div class="card-footer">
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Excluir Usuário
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
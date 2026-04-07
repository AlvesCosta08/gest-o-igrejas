<?php

namespace App\Policies;

use App\Models\Filiado;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FiliadoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Qualquer usuário autenticado com nivel 'admin' ou 'apoio' pode ver a lista
        return in_array($user->nivel, ['admin', 'apoio']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Filiado $filiado): bool
    {
        // Apenas 'admin' e 'apoio' podem ver detalhes?
        return in_array($user->nivel, ['admin', 'apoio']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Apenas 'admin' pode criar?
        return $user->nivel === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Filiado $filiado): bool
    {
        // Apenas 'admin' pode atualizar?
        return $user->nivel === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Filiado $filiado): bool
    {
        // Apenas 'admin' pode deletar?
        return $user->nivel === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     * (Se usar SoftDeletes)
     */
    public function restore(User $user, Filiado $filiado): bool
    {
        // Exemplo: Apenas admin pode restaurar
        return $user->nivel === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     * (Se usar SoftDeletes)
     */
    public function forceDelete(User $user, Filiado $filiado): bool
    {
        // Exemplo: Apenas admin pode deletar permanentemente
        return $user->nivel === 'admin';
    }
}
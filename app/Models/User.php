<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nivel',
        'congregacao_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ========== MÉTODOS DE AUTORIZAÇÃO ==========

    /**
     * Verifica se o usuário é Administrador Global
     */
    public function isAdmin(): bool
    {
        return $this->nivel === 'admin';
    }

    /**
     * ✨ NOVO: Verifica se o usuário é Secretário de alguma congregação
     * 
     * Um usuário é secretário se:
     * 1. Não for admin global
     * 2. Tiver congregacao_id definido
     * 3. Existir um filiado vinculado a este usuário com funcao = 'Secretário'
     */
    public function isSecretario(): bool
    {
        // Admin global não é secretário (tem acesso total)
        if ($this->isAdmin()) {
            return false;
        }

        // Verifica se existe filiado vinculado a este usuário com função Secretário
        $filiado = $this->filiado;
        return $filiado && $filiado->funcao === 'Secretário';
    }

    /**
     * ✨ NOVO: Retorna a congregação que o usuário gerencia como secretário
     */
    public function getCongregacaoSecretariadoAttribute()
    {
        if (!$this->isSecretario()) {
            return null;
        }
        return $this->filiado?->congregacao;
    }

    // ========== RELACIONAMENTOS ==========

    /**
     * Relacionamento com Filiado (um usuário pode ter um registro como filiado)
     */
    public function filiado()
    {
        return $this->hasOne(Filiado::class, 'user_id', 'id');
    }

    /**
     * Relacionamento com Congregação (para usuários vinculados a uma congregação)
     */
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }
}
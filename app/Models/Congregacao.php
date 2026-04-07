<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Congregacao extends Model
{
    use HasFactory;

    protected $table = 'congregacoes';

    protected $fillable = [
        'nome',
        'cidade',
        'endereco',
        'telefone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELACIONAMENTOS ==========
    
    /**
     * Usuários desta congregação
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Filiados desta congregação
     */
    public function filiados()
    {
        return $this->hasMany(Filiado::class);
    }

    // ========== SCOPES ==========
    
    /**
     * Buscar congregação por nome
     */
    public function scopePorNome($query, $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }

    // ========== ACCESSORS ==========
    
    /**
     * Retorna quantidade de filiados
     */
    public function getTotalFiliadosAttribute()
    {
        return $this->filiados()->count();
    }

    /**
     * Retorna quantidade de usuários
     */
    public function getTotalUsersAttribute()
    {
        return $this->users()->count();
    }
}
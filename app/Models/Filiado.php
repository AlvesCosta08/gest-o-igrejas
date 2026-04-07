<?php
// app/Models/Filiado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Filiado extends Model
{
    protected $table = 'filiados';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // ✅ Campos preenchíveis
    protected $fillable = [
        'matricula',
        'congregacao_id',
        'user_id',
        'nome',
        'nome_carteira',
        'logradouro',
        'endereco',
        'numero',
        'bairro',
        'cep',
        'email',
        'cidade',
        'uf',
        'documento',
        'telefone',
        'estadoCivil',
        'dataNascimento',
        'mae',
        'pai',
        'datCadastro',
        'dataBatismo',
        'data_Consagracao',
        'arquivo',
        'cartas',
        'funcao',
        'status',
    ];

    // ✅ Casting de tipos
    protected $casts = [
        'matricula' => 'integer',
        'congregacao_id' => 'integer',
        'user_id' => 'integer',
        'dataNascimento' => 'date',
        'datCadastro' => 'date',
        'dataBatismo' => 'date',
        'data_Consagracao' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELACIONAMENTOS ==========
    
    public function congregacao()
    {
        return $this->belongsTo(Congregacao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========
    
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorCongregacao($query, $congregacaoId)
    {
        return $query->where('congregacao_id', $congregacaoId);
    }

    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePermissaoUsuario($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }
        return $query->where('congregacao_id', $user->congregacao_id);
    }

    /**
     * ✨ NOVO: Scope para filtrar por congregação do secretário
     */
    public function scopePorCongregacaoDoSecretario($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }
        
        if ($user->isSecretario()) {
            $congregacaoId = $user->filiado()?->congregacao_id;
            return $query->where('congregacao_id', $congregacaoId);
        }
        
        return $query->where('congregacao_id', $user->congregacao_id);
    }

    // ========== ACCESSORS ==========
    
    public function nomeCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => ucwords(strtolower($this->nome)),
        );
    }

    public function getNomeCongregacaoAttribute()
    {
        return $this->congregacao?->nome ?? 'Sem congregação';
    }

    /**
     * ✨ Accessor para URL da foto com fallback automático
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->arquivo && Storage::disk('public')->exists($this->arquivo)) {
                    return asset('storage/' . $this->arquivo);
                }
                
                $nome = urlencode($this->nome ?? 'U');
                $background = '0D8ABC';
                $color = 'fff';
                $size = 128;
                
                return "https://ui-avatars.com/api/?name={$nome}&background={$background}&color={$color}&size={$size}";
            },
        );
    }
}
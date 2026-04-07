<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Congregacao;

class CongregacaoSeeder extends Seeder
{
    public function run(): void
    {
        $congregacoes = [
            [
                'nome' => 'SEDE',
                'endereco' => 'Rua Principal, 100',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01000-000',
                'telefone' => '(11) 1234-5678',
                'email' => 'sede@igreja.com',
                'pastor' => 'Rev. João Batista',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Igreja Batista Central',
                'endereco' => 'Av. Paulista, 1000',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01310-100',
                'telefone' => '(11) 3123-4567',
                'email' => 'central@igrejabatista.org',
                'pastor' => 'Rev. Carlos Alberto Silva',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Igreja Batista Betânia',
                'endereco' => 'Rua Augusta, 1500',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'cep' => '01412-000',
                'telefone' => '(11) 3567-8901',
                'email' => 'betania@igrejabatista.org',
                'pastor' => 'Rev. Marcos Oliveira',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($congregacoes as $congregacao) {
            Congregacao::updateOrCreate(
                ['nome' => $congregacao['nome']],
                $congregacao
            );
        }
        
        $this->command->info('✅ Congregações criadas/atualizadas com sucesso!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica se já existe um usuário administrador
        $adminExists = User::where('email', 'admin@sistema.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@sistema.com',
                'password' => Hash::make('Lav8@471'),
                'nivel' => 'admin',
                'congregacao_id' => 1, // Ajuste conforme necessário
            ]);
            $this->command->info('✅ Usuário administrador criado com sucesso!');
        } else {
            $this->command->info('ℹ️  Usuário administrador já existe. Pulando criação.');
        }
        
        // Opcional: Criar um usuário padrão para testes (apenas em desenvolvimento)
        if (app()->environment('local', 'development')) {
            $userExists = User::where('email', 'usuario@teste.com')->exists();
            
            if (!$userExists) {
                User::create([
                    'name' => 'Usuário Teste',
                    'email' => 'usuario@teste.com',
                    'password' => Hash::make('teste123'),
                    'nivel' => 'user',
                    'congregacao_id' => 1,
                ]);
                $this->command->info('✅ Usuário de teste criado com sucesso!');
            }
        }
    }
}
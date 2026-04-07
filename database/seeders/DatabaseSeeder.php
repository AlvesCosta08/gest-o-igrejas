<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeders base (obrigatórios)
        $this->call([
            CongregacaoSeeder::class,
            AdminUserSeeder::class,
        ]);
        
        // Verifica se já existem filiados antes de popular
        if (\App\Models\Filiado::count() === 0) {
            $this->command->info('📝 Populando filiados...');
            $this->call([
                FiliadosTableSeeder::class,        // Dados básicos (10 filiados)
                // FiliadosMassSeeder::class,      // Descomente para criar muitos dados (50+)
                // FiliadosRegioesSeeder::class,   // Descomente para dados por região
            ]);
        } else {
            $this->command->info('✅ Filiados já existem no banco de dados. Pulando seeders de filiados.');
        }
        
        $this->command->newLine();
        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
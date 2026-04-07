<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filiados', function (Blueprint $table) {
            // Adicionar a coluna congregacao_id
            $table->foreignId('congregacao_id')
                  ->after('matricula')
                  ->constrained('congregacoes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('filiados', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
        });
    }
};
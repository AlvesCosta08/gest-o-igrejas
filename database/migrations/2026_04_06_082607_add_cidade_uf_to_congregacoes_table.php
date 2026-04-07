<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('congregacoes', function (Blueprint $table) {
            if (!Schema::hasColumn('congregacoes', 'cidade')) {
                $table->string('cidade', 100)->after('endereco')->default('São Paulo');
            }
            if (!Schema::hasColumn('congregacoes', 'uf')) {
                $table->string('uf', 2)->after('cidade')->default('SP');
            }
            if (!Schema::hasColumn('congregacoes', 'cep')) {
                $table->string('cep', 10)->after('uf')->nullable();
            }
            if (!Schema::hasColumn('congregacoes', 'telefone')) {
                $table->string('telefone', 20)->after('cep')->nullable();
            }
            if (!Schema::hasColumn('congregacoes', 'email')) {
                $table->string('email', 100)->after('telefone')->nullable();
            }
            if (!Schema::hasColumn('congregacoes', 'pastor')) {
                $table->string('pastor', 100)->after('email')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('congregacoes', function (Blueprint $table) {
            $columns = ['cidade', 'uf', 'cep', 'telefone', 'email', 'pastor'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('congregacoes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
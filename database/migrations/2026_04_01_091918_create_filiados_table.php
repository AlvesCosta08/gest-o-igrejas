<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filiados', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('matricula')->unique();
            $table->string('congregacao');
            $table->string('nome');
            $table->string('nome_carteira')->nullable();
            $table->string('logradouro')->default('Rua');
            $table->string('endereco');
            $table->string('numero'); // ← Alterado de integer() para string()
            $table->string('bairro');
            $table->string('cep');
            $table->string('email')->nullable();
            $table->string('cidade');
            $table->string('uf', 2);
            $table->string('documento');
            $table->string('telefone');
            $table->string('estadoCivil');
            $table->date('dataNascimento');
            $table->string('mae');
            $table->string('pai')->nullable();
            $table->date('datCadastro')->nullable();
            $table->date('dataBatismo')->nullable();
            $table->date('data_Consagracao')->nullable();
            $table->string('arquivo')->nullable();
            $table->string('cartas')->nullable();
            $table->string('funcao');
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filiados');
    }
};
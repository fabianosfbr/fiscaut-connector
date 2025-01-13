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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razao_emp');
            $table->string('apel_emp')->nullable();
            $table->string('cgce_emp')->nullable();
            $table->string('iest_emp')->nullable();
            $table->string('imun_emp')->nullable();
            $table->string('codi_emp')->unique();
            $table->string('esta_emp', 2)->nullable();
            $table->string('cod_mun')->nullable();
            $table->boolean('sync')->default(false);
            $table->boolean('cliente')->default(true);
            $table->boolean('fornecedor')->default(true);
            $table->boolean('plano_de_conta')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

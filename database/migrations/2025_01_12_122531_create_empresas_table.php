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
            $table->string('nome_emp');
            $table->string('apel_emp');
            $table->string('cgce_emp');
            $table->integer('iest_emp');
            $table->integer('imun_emp');
            $table->integer('codi_emp');
            $table->integer('esta_emp');
            $table->integer('cod_municipio');
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

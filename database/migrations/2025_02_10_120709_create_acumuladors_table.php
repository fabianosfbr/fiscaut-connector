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
        Schema::create('acumuadores', function (Blueprint $table) {
            $table->id();
            $table->integer('codi_emp')->index;
            $table->integer('codi_acu');
            $table->string('nome_acu')->nullable();
            $table->string('descricao_acu')->nullable();
            $table->boolean('fiscaut_sync')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acumuadores');
    }
};

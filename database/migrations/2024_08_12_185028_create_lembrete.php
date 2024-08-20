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
        Schema::create('lembretes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nome');
            $table->string('descricao');
            $table->enum('dia_semana', ['DOMINGO', 'SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO']);
            $table->time('hora');
            $table->tinyInteger('confirmado')->default(0);
            $table->unsignedBigInteger('id_cadastrante');
            $table->timestamps();

            $table->foreign('id_cadastrante')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembrete');
    }
};

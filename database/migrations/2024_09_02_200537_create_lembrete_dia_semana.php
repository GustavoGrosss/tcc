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
        Schema::create('lembrete_dia_semana', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('id_dia_semana');
            $table->unsignedBigInteger('id_lembrete');
            $table->timestamps();

            $table->foreign('id_dia_semana')->references('id')->on('dia_semana')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_lembrete')->references('id')->on('lembretes')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembrete_dia_semana');
    }
};

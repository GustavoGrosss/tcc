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
        Schema::create('notificacao', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->dateTime('data_disparo');
            $table->tinyInteger('confirmado')->default(0);
            $table->unsignedBigInteger('id_lembrete');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_lembrete')->references('id')->on('lembretes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacao');
    }
};

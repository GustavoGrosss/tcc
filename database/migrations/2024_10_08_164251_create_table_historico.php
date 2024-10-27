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
        Schema::create('historicos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('dia_semana');
            $table->tinyInteger('confirmado')->default(0);
            $table->dateTime('data_notificacao');
            $table->dateTime('data_confirmacao')->nullable();
            $table->unsignedBigInteger('id_notificacao');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_notificacao')->references('id')->on('lembrete_dia_semana')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_historico');
    }
};

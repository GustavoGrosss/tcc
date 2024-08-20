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
        Schema::create('titulares_secundarios', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('id_titular');
            $table->unsignedBigInteger('id_secundario');
            $table->timestamps();

            $table->foreign('id_titular')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_secundario')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulares_secundarios');
    }
};

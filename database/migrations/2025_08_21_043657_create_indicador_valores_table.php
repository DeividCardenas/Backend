<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('indicador_valores', function (Blueprint $table) {
            $table->bigIncrements('id_valor');
            $table->unsignedBigInteger('id_indicador');
            $table->decimal('valor', 12, 2);
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->timestamps();

            $table->foreign('id_indicador')->references('id_indicador')->on('indicadores')->onDelete('cascade');
            $table->foreign('registrado_por')->references('id_usuario')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('indicador_valores');
    }
};

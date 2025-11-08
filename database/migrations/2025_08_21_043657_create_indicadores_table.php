<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->bigIncrements('id_indicador');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('formula')->nullable();
            $table->string('meta')->nullable();
            $table->string('unidad')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('id_norma')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('responsable_id')->references('id_usuario')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('indicadores');
    }
};

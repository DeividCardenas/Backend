<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('comites', function (Blueprint $table) {
            $table->bigIncrements('id_comite');
            $table->string('nombre');
            $table->text('objetivo');
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->timestamps();

            $table->foreign('responsable_id')->references('id_usuario')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('comites');
    }
};

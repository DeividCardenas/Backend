<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reuniones', function (Blueprint $table) {
            $table->bigIncrements('id_reunion');
            $table->unsignedBigInteger('id_comite');
            $table->date('fecha');
            $table->text('tema');
            $table->text('acuerdos')->nullable();
            $table->string('archivo_acta')->nullable();
            $table->timestamps();

            $table->foreign('id_comite')->references('id_comite')->on('comites')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('reuniones');
    }
};

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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();

            // Clave foránea para el producto asociado
            $table->unsignedBigInteger('software_id');

            // Código serial para la licencia
            $table->string('serial', 100)->unique();

            // Agrega una columna llamada 'deleted_at', se usa para una eliminación suave (Almacena fecha y hora en la que se marcó el registro)
            $table->softDeletes();

            $table->timestamps();

            // Definición de las claves foráneas
            $table->foreign('software_id')->references('id')->on('software');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};

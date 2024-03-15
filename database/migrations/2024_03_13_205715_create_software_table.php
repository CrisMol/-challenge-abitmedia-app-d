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
        Schema::create('software', function (Blueprint $table) {
            $table->id();

            // SKU del producto
            $table->string('sku', 10)->unique();

            // Nombre del producto
            $table->string('name', 55);

            $table->timestamps();

            // Agrega una columna llamada 'deleted_at', se usa para una eliminación suave (Almacena fecha y hora en la que se marcó el registro)
            $table->softDeletes();

            // Tipo de software al que pertenece
            $table->unsignedBigInteger('software_type_id');

            // Definición de las claves foráneas
            $table->foreign('software_type_id')->references('id')->on('software_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software');
    }
};

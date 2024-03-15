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
        Schema::create('operating_systems', function (Blueprint $table) {
            $table->id();

            // Nombre del sistema operativo
            $table->string('name', 55);

            // Agrega una columna llamada 'deleted_at', se usa para una eliminación suave (Almacena fecha y hora en la que se marcó el registro)
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_systems');
    }
};

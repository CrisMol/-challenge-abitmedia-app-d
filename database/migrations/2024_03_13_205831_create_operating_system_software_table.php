<?php

use App\Models\Software;
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
        Schema::create('operating_system_software', function (Blueprint $table) {
            $table->id();

            // Clave foránea para el producto asociado
            $table->unsignedBigInteger('software_id');

            // Clave foránea para el sistema operativo
            $table->unsignedBigInteger('operating_system_id');

            // Precio del producto para el sistema operativo asociado
            $table->decimal('price', 10, 2)->default(0);

            // Cantidad del producto para el sistema operativo asociado
            $table->integer('quantity')->default(0);

            // Disponibilidad del producto para el sistema operativo asociado
            $table->string('availability')->default(Software::SOFTWARE_DISPONIBLE);

            // Agrega una columna llamada 'deleted_at', se usa para una eliminación suave (Almacena fecha y hora en la que se marcó el registro)
            $table->softDeletes();

            $table->timestamps();

            // Definición de las claves foráneas
            $table->foreign('software_id')->references('id')->on('software');
            $table->foreign('operating_system_id')->references('id')->on('operating_systems');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_system_software');
    }
};

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\License;
use App\Models\OperatingSystem;
use App\Models\Software;
use App\Models\Service;
use App\Models\SoftwareType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Generar la contraseña encriptada
        $passwordEncriptado = Hash::make('adminMedia');

        \App\Models\User::create([
            'name' => 'admintest',
            'email' => 'admintest@abitmedia.com',
            'password' => $passwordEncriptado,
        ]);

        // Eliminar eventos del modelo
        License::flushEventListeners();

        // Cantidades de registros
        $cantidadProductos = 10;
        $contidadLicenses = 30;

        /**
         * Crear registros
         */

         // Tipos de software
            SoftwareType::create([
                'name' => 'Antivirus',
            ]);

            SoftwareType::create([
                'name' => 'Ofimática',
            ]);

            SoftwareType::create([
                'name' => 'Editor de vídeo',
            ]);
        // Fin tipos de software

        // Software
            Software::create([
                'name' => 'AVG',
                'sku' => '0000000001',
                'software_type_id' => 1
            ]);

            Software::create([
                'name' => 'Microsoft Excel',
                'sku' => '0000000002',
                'software_type_id' => 2
            ]);

            Software::create([
                'name' => 'Camtasia',
                'sku' => '0000000003',
                'software_type_id' => 3
            ]);

            Software::create([
                'name' => 'Photoshop',
                'sku' => '0000000004',
                'software_type_id' => 3
            ]);
        // Fin software

        // Licencias
            License::factory($contidadLicenses)->create();
        // Fin licencias

        // Servicios
            Service::create([
                'sku' => '0000000001',
                'name' => 'Formateo de computadoras',
                'price' => '35.00'
            ]);

            Service::create([
                'sku' => '0000000002',
                'name' => 'Mantenimiento',
                'price' => '30.00'
            ]);

            Service::create([
                'sku' => '0000000003',
                'name' => 'Hora de soporte en software',
                'price' => '50.00'
            ]);
        // Fin servicios

        // Sistemas operativos
            OperatingSystem::create([
                'name' => 'Windows'
            ]);

            OperatingSystem::create([
                'name' => 'Mac'
            ]);
        // Fin sistemas operativos

        // Llama y ejecuta tu seeder personalizado
        $this->call(SoftwareOperatingSystemSeeder::class);
    }
}

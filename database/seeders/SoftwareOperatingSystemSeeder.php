<?php

namespace Database\Seeders;

use App\Models\OperatingSystem;
use App\Models\Software;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoftwareOperatingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Obtener todos los registros de software y sistemas operativos
         $softwares = Software::all();
         $operatingSystems = OperatingSystem::all();
 
         // Asignar sistemas operativos aleatorios a cada software
         foreach ($softwares as $software) {
             // Obtener un número aleatorio de sistemas operativos para asignar
             $numberOfOperatingSystems = rand(1, $operatingSystems->count());
 
             // Seleccionar sistemas operativos aleatorios
             $randomOperatingSystems = $operatingSystems->random($numberOfOperatingSystems);
 
             // Adjuntar los sistemas operativos al software
             foreach ($randomOperatingSystems as $os) {
                $price = mt_rand(10, 100); // Precio aleatorio entre 10 y 100
                $quantity = mt_rand(1, 20); // Cantidad aleatoria entre 1 y 20
                $software->operatingSystems()->attach($os->id, [
                    'price' => $price,
                    'quantity' => $quantity,
                    'availability' => Software::SOFTWARE_DISPONIBLE
                ]);
             }
         }
    }
}

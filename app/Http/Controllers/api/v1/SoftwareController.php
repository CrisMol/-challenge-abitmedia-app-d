<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Software;
use App\Http\Requests\StoreSoftwareRequest;
use App\Http\Requests\UpdateSoftwareRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class SoftwareController extends ApiController
{
    /**
     * Muestra todos los software
     *
     * Obtiene todas los software activos y devuelve sus detalles.
     *
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles de todos los software activos.
     */
    public function index()
    {
        $software = Software::with(['operatingSystems', 'softwareType'])->get();

        // Transformar los datos para mostrar en la respuesta
        $transformedSoftware = $software->map(function ($value) {
            return $this->createSoftwareStructure($value);
        });

        return $this->showAll($transformedSoftware);
    }

    /**
     * Almacena un nuevo software.
     *
     * Crea un nuevo software utilizando los datos proporcionados en la solicitud. Devuelve los detalles del software recién creado.
     *
     * @param StoreSoftwareRequest $request La solicitud de almacenamiento que contiene los datos enviados por el cliente.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles del software recién creado.
     */
    public function store(StoreSoftwareRequest $request)
    {
        // Armar los datos para el registro
        $dataSoftware = [
            'sku' => $request->sku,
            'name' => $request->nombre,
            'software_type_id' => $request->identificador_tipo_software,
        ];

        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();
    
            // Crea un nuevo objeto 
            $software = Software::create($dataSoftware);

            // Relación con un sistema operativo con columnas pivot adicionales
            $software->operatingSystems()->attach($request->identificador_sistema_operativo, [
                'quantity' => $request->cantidad,
                'price' => $request->precio,
            ]);

            // Confirma la transacción si todo fue exitoso
            DB::commit();

            // Transformar los datos del software para mostrar en la respuesta
            $transformedSoftware = $this->createSoftwareStructure($software);

            // Devuelve la respuesta con el hotel creado y un código de estado 201 (Created)
            return $this->showOne($transformedSoftware, 201);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();
            return $this->errorResponse('Error al almacenar los datos. Detalles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Muestra los detalles de un software.
     *
     * @param Software $software El software cuyos detalles se van a mostrar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles del software.
     */
    public function show(Software $software)
    {
        // Transformar los datos del software para mostrar en la respuesta
        $transformedSoftware = $this->createSoftwareStructure($software);

        return $this->showOne($transformedSoftware);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoftwareRequest $request, Software $software)
    {
         // Armar los datos para el registro
         $dataSoftware = [
            'sku' => $request->sku,
            'name' => $request->nombre,
            'software_type_id' => $request->identificador_tipo_software,
        ];

        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();
    
            // Actualizar
            $software->update($dataSoftware);

            // Verificar si se desea actualizar alguna relación con un sistema operativo
            if ($request->has('identificador_sistema_operativo')) {
                 // Verificar si existe una relación entre el software y el sistema operativo específico
                if (!$software->operatingSystems()->where('operating_systems.id', $request->identificador_sistema_operativo)->exists()) {
                    // Crear la relación
                    $software->operatingSystems()->attach($request->identificador_sistema_operativo, [
                        'quantity' => $request->cantidad,
                        'price' => $request->precio,
                    ]);
                }
                // Actualizar los campos de la relación
                $software->operatingSystems()->updateExistingPivot($request->identificador_sistema_operativo, [
                    'quantity' => $request->cantidad,
                    'price' => $request->precio,
                ]);
            }

            // Confirma la transacción si todo fue exitoso
            DB::commit();

            // Transformar los datos del software para mostrar en la respuesta
            $transformedSoftware = $this->createSoftwareStructure($software);

            // Devuelve la respuesta con el hotel creado y un código de estado 201 (Created)
            return $this->showOne($transformedSoftware, 201);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();
            return $this->errorResponse('Error al almacenar los datos. Detalles: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Crea una estructura de software a partir de un objeto de software.
     *
     * @param Software $software El objeto de software del cual se creará la estructura.
     * @return array La estructura del software.
     */
    public function createSoftwareStructure($software)
    {
        return [
            'identificador' => $software->id,
            'sku' => $software->sku,
            'nombre' => $software->name,
            'tipo' => [
                'identificador' => $software->softwareType->id,
                'nombre' => $software->softwareType->name,
            ],
            'sistema_operativo' => $software->operatingSystems->map(function ($sistemaOperativo) {
                return [
                    'identificador' => $sistemaOperativo->id,
                    'nombre' => $sistemaOperativo->name,
                    'precio' => $sistemaOperativo->pivot->price,
                    'cantidad' => $sistemaOperativo->pivot->quantity,
                    'estado' => $sistemaOperativo->pivot->availability,
                ];
            })->toArray(),
        ];
    }

    /**
     * Elimina un software a nivel lógico
     *
     * @param Software $software El software a eliminar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON indicando el resultado de la eliminación.
     */
    public function destroy(Software $software)
    {
        $software->delete();
        // Mostrar el servicio en la respuesta
        $transformedSoftware = [
            'identificador' => $software->id,
            'sku' => $software->sku,
            'nombre' => $software->name,
            'estado' => 'eliminado'
        ];

        return $this->showOne($transformedSoftware);
    }
}

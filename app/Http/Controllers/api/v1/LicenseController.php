<?php

namespace App\Http\Controllers\api\v1;

use App\Models\License;
use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Http\Controllers\ApiController;

class LicenseController extends ApiController
{
    /**
     * Muestra todas las licencias activas.
     *
     * Obtiene todas las licencias activos y devuelve sus detalles.
     *
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles de todos las licencias activas.
     */
    public function index()
    {
        $licenses = License::with('software')->get();

        // Transformar los datos de las licencias para mostrar en la respuesta
        $transformedLicenses = $licenses->map(function ($license) {
            return $this->createLicenseStructure($license);
        });

        return $this->showAll($transformedLicenses);
    }

    /**
     * Almacena una nueva licencia.
     *
     * Crea una nueva licencia utilizando los datos proporcionados en la solicitud. Devuelve los detalles de la licencia recién creada.
     *
     * @param StoreLicenseRequest $request La solicitud de almacenamiento que contiene los datos enviados por el cliente.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles de la licencia recién creada.
     */
    public function store(StoreLicenseRequest $request)
    {
        // Armar los datos para el registro
        $data = [
            'software_id' => $request->identificador_software,
        ];

        // Crear un serial unico de 100 caracteres
        $serial = $this->generateSingleSerial();
        // Verificar si el serial aún no existe en la base
        while (License::where('serial', $serial)->exists()) {
            $serial = $this->generateSingleSerial();
        }
        $data['serial'] = $serial;

        $license = License::create($data);
        // Transformar los datos del servicio para mostrar en la respuesta
        $transformedLicense = $this->createLicenseStructure($license);

        return $this->showOne($transformedLicense, 201);
    }

    /**
     * Genera un serial de 100 carácteres aleatorios
     * 
     * @return String Un serial de 100 caracteres aleatorios
     */
    public function generateSingleSerial()
    {
        // Generar un serial único de 100 caracteres
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $longitud = 100;
        $serial = '';

        for ($i = 0; $i < $longitud; $i++) {
            $serial .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $serial;
    }

    /**
     * Muestra los detalles de una licencia.
     *
     *
     * @param License $license El licencia cuyos detalles se van a mostrar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles de la licencia.
     */
    public function show(License $license)
    {
        // Transformar los datos de la licencia para mostrar en la respuesta
        $transformedLicense = $this->createLicenseStructure($license);

        return $this->showOne($transformedLicense);
    }

   /**
     * Actualiza una licencia existente.
     *
     * @param UpdateLicenseRequest $request La solicitud de actualización que contiene los datos enviados por el cliente.
     * @param License $license La licencia a actualizar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles actualizados de la licencia.
     */
    public function update(UpdateLicenseRequest $request, License $license)
    {
        $data = [];

        // Verificar si existe el identificador de software
        if ($request->has('identificador_software')) {
            $data['software_id'] = $request->identificador_software;
        }

        // Verificar si existe el serial de software
        if ($request->has('serial')) {
            $data['serial'] = $request->serial;
        }

        $license->fill($data); // Obtener los valores que vamos actualizar

        if ($license->isClean()) { // Si la instancia no ha cambiado
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $license->save();
        // Transformar los datos de la licencia para mostrar en la respuesta
        $transformedLicense = $this->createLicenseStructure($license);

        return $this->showOne($transformedLicense);
    }

    /**
     * Crea una estructura de licencia a partir de un objeto de licencia.
     *
     * @param License $license El objeto de licencia del cual se creará la estructura.
     * @return array La estructura de la licencia.
     */
    public function createLicenseStructure($license)
    {
        return [
            'identificador' => $license->id,
            'serial' => $license->serial,
            'software' => [
                'identificador' => $license->software->id,
                'sku' => $license->software->sku,
                'nombre' => $license->software->name,
                'tipo' => [
                    'identificador' => $license->software->softwareType->id,
                    'nombre' => $license->software->softwareType->name
                ]
            ]
        ];
    }

     /**
     * Elimina una licencia a nivel lógico
     *
     * @param License $license La licencia a eliminar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON indicando el resultado de la eliminación.
     */
    public function destroy(License $license)
    {
        $license->delete();
        // Mostrar la licencia en la respuesta
        $transformedLicense = [
            'identificador' => $license->id,
            'serial' => $license->serial,
            'estado' => 'eliminado'
        ];

        return $this->showOne($transformedLicense);
    }
}

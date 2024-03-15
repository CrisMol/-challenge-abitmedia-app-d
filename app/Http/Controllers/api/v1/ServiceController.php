<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Service;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends ApiController
{
    /**
     * Muestra todos los servicios activos.
     *
     * Obtiene todos los servicios activos (cuyo estado no está marcado como eliminado) y devuelve sus detalles.
     *
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles de todos los servicios activos.
     */
    public function index()
    {
        $services = Service::all();
        // Transformar los datos de los servicios para mostrar en la respuesta
        $transformedServices = $services->map(function ($service) {
            return [
                'identificador' => $service->id,
                'sku' => $service->sku,
                'nombre' => $service->name,
                'precio' => $service->price,
            ];
        });

        return $this->showAll($transformedServices);
    }

    /**
     * Almacena un nuevo servicio.
     *
     * Crea un nuevo servicio utilizando los datos proporcionados en la solicitud. Devuelve los detalles del servicio recién creado.
     *
     * @param StoreServiceRequest $request La solicitud de almacenamiento que contiene los datos enviados por el cliente.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles del servicio recién creado.
     */
    public function store(StoreServiceRequest $request)
    {
        // Armar los datos para el registro
        $data = [
            'sku' => $request->sku,
            'name' => $request->nombre,
            'price' => $request->precio
        ];

        $service = Service::create($data);
        // Transformar los datos del servicio para mostrar en la respuesta
        $transformedService = [
            'identificador' => $service->id,
            'sku' => $service->sku,
            'nombre' => $service->name,
            'precio' => $service->price,
        ];

        return $this->showOne($transformedService, 201);
    }

    /**
     * Muestra los detalles de un servicio.
     *
     * Muestra los detalles de un servicio específico, verificando primero que el servicio no esté marcado como eliminado.
     * Si el servicio no existe o está eliminado, devuelve un error con el código de estado 404.
     *
     * @param Service $service El servicio cuyos detalles se van a mostrar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles del servicio.
     */
    public function show(Service $service)
    {
        // Transformar los datos del servicio para mostrar en la respuesta
        $transformedService = [
            'identificador' => $service->id,
            'sku' => $service->sku,
            'nombre' => $service->name,
            'precio' => $service->price,
        ];

        return $this->showOne($transformedService);
    }

   /**
     * Actualiza un servicio existente.
     *
     * Actualiza los detalles de un servicio existente, verificando primero que el servicio no esté marcado como eliminado.
     * Si el servicio no existe o está eliminado, devuelve un error con el código de estado 404. Si no se especifica ningún valor diferente
     * para actualizar, devuelve un error con el código de estado 422.
     *
     * @param UpdateServiceRequest $request La solicitud de actualización que contiene los datos enviados por el cliente.
     * @param Service $service El servicio a actualizar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON que contiene los detalles actualizados del servicio.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        // Armar los datos para el registro
        $data = [
            'sku' => $request->sku,
            'name' => $request->nombre,
            'price' => $request->precio
        ];

        $service->fill($data); // Obtener los valores que vamos actualizar

        if ($service->isClean()) { // Si la instancia no ha cambiado
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $service->save();
        // Transformar los datos del servicio para mostrar en la respuesta
        $transformedService = [
            'identificador' => $service->id,
            'sku' => $service->sku,
            'nombre' => $service->name,
            'precio' => $service->price,
        ];

        return $this->showOne($transformedService);
    }

    /**
     * Elimina un servicio a nivel lógico, cambia de estado 0 (activo) a 1 (eliminado)
     *
     * Elimina un servicio marcando su estado como eliminado ('status' = 1). Si el servicio ya está eliminado,
     * devuelve un error con el código de estado 404.
     *
     * @param Service $service El servicio a eliminar.
     * @return Illuminate\Http\JsonResponse Una respuesta JSON indicando el resultado de la eliminación.
     */
    public function destroy(Service $service)
    {

        // Estado 1
        $service->delete();
        // Mostrar el servicio en la respuesta
        $transformedService = [
            'identificador' => $service->id,
            'sku' => $service->sku,
            'nombre' => $service->name,
            'precio' => $service->price,
            'estado' => 'eliminado'
        ];

        return $this->showOne($transformedService);
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

trait ApiResponser 
{
    /**
     * Genera una respuesta JSON exitosa.
     *
     * @param mixed $status Estado de la respuesta.
     * @param string|null $message Mensaje opcional.
     * @param mixed $data Datos que se incluirán en la respuesta.
     * @param int $code Código de estado HTTP.
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($status, $message = NULL, $data, $code)
    {
        $data = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($data, $code);
    }

    /**
     * Genera una respuesta JSON de error.
     *
     * @param string $message Mensaje de error.
     * @param int $code Código de estado HTTP.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        $data = [
            'status' => $code,
            'message' => $message,
            'data' => []
        ];

        return response()->json($data, $code);
    }

    /**
     * Muestra una colección de datos en formato JSON.
     *
     * @param \Illuminate\Support\Collection $collection Colección de datos.
     * @param int $code Código de estado HTTP.
     * @param string|null $message Mensaje opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll(Collection $collection, $code = 200, $message = NULL)
    {
        return $this->successResponse($code, $message, $collection, $code);
    }

    /**
     * Muestra un solo elemento en formato JSON.
     *
     * @param mixed $instanceOrArray Instancia del modelo o array.
     * @param int $code Código de estado HTTP.
     * @param string|null $message Mensaje opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOne($instanceOrArray, $code = 200, $message = NULL)
    {
        $data = $instanceOrArray instanceof Model ? $instanceOrArray : $instanceOrArray;

        return $this->successResponse($code, $message, $data, $code);
    }

    /**
     * Muestra una colección transformada utilizando Fractal.
     *
     * @param \Illuminate\Support\Collection $collection Colección de datos.
     * @param int $code Código de estado HTTP.
     * @param string|null $message Mensaje opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAllTransformer(Collection $collection, $code = 200, $message = null)
    {
        // Tiene elementos o no?
        if ($collection->isEmpty()) {
            return $this->successResponse($code, $message, $collection, $code);
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse($code, $message, $collection['data'], $code);
    }

    /**
     * Muestra un solo elemento transformado utilizando Fractal.
     *
     * @param \Illuminate\Database\Eloquent\Model $instance Instancia del modelo.
     * @param int $code Código de estado HTTP.
     * @param string|null $message Mensaje opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOneTransformer(Model $instance, $code = 200, $message = null)
    {
        $transformer = $instance->transformer;
        $data = $this->transformData($instance, $transformer);

        return $this->successResponse($code, $message, $data['data'], $code);
    }

    /**
     * Registra las llamadas a la base de datos en tiempo real
     */
    protected function registerCallToDatabase($queries)
    {
        // Almacenar la información en el archivo de registro
        foreach ($queries as $query) {
            $sql = $query['query'];
            $bindings = $query['bindings'];
            $time = $query['time'];

            // Formatear la información
            $logMessage = "Query: $sql | Bindings: " . json_encode($bindings) . " | Tiempo de ejecución: $time ms";

            // Almacenar en el archivo de registro
            \Illuminate\Support\Facades\Log::info($logMessage);
        }
    }
}
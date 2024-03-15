<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse("No posee permisos para ejecutar esta acción", 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("No se encontro la url especificada", 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El método especificado en la petición no es válido", 405);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];

            if ($codigo == 1451) {
                return $this->errorResponse("No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro", 409);
            }

            if ($codigo == 1062) {
                $mensaje = $exception->getMessage();
                preg_match("/'(.+?)'/", $mensaje, $matches);
                $campo = $matches[1] ?? 'campo_desconocido';

                return $this->errorResponse("El campo {$campo} recibido debe ser único, ya existe en la base de datos", 422);
            }

            if ($codigo == 1364) {
                $mensaje = $exception->getMessage();
                preg_match("/'(.+?)'/", $mensaje, $matches);
                $campo = $matches[1] ?? 'campo_desconocido';

                return $this->errorResponse("El campo {$campo} no tiene valor por defecto", 422);
            }
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            $sqlState = $exception->getSQLState();

            // Intenta extraer el nombre del campo desde el mensaje de SQLState
            preg_match('/key \'(.+?)\'/', $sqlState, $matches);
            $fieldName = $matches[1] ?? 'campo_desconocido';

            return $this->errorResponse("El campo {$fieldName} debe ser único y ya existe en la base de datos", 422);
        }

        // Si está en modo de depuración
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Error inesperado, intentálo más tarde", 500);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);
    }

    // Retorna siempre errores de tipo JSON cuando un usuario no esté autenticado
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado', 401);
    }
}

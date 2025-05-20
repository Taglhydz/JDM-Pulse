<?php

namespace App\Http\Librairies;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponse implements Responsable
{
    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers;

    /**
     * Constructeur
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(
        bool $success = true,
        string $message = '',
        $data = null,
        int $statusCode = 200,
        array $headers = []
    ) {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Créer une réponse HTTP à partir de l'instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return new JsonResponse([
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->statusCode, $this->headers);
    }

    /**
     * Réponse de succès
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function success(string $message = 'Opération réussie', $data = null, int $statusCode = 200, array $headers = [])
    {
        return new self(true, $message, $data, $statusCode, $headers);
    }

    /**
     * Réponse d'erreur
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function error(string $message = 'Une erreur est survenue', $data = null, int $statusCode = 400, array $headers = [])
    {
        return new self(false, $message, $data, $statusCode, $headers);
    }

    /**
     * Réponse d'erreur de validation
     *
     * @param mixed $errors
     * @param string $message
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function validationError($errors, string $message = 'Erreur de validation', int $statusCode = 422, array $headers = [])
    {
        return new self(false, $message, $errors, $statusCode, $headers);
    }
}
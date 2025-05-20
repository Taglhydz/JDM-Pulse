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
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function validationError(string $message = 'Erreur de validation', $errors, int $statusCode = 422, array $headers = [])
    {
        return new self(false, $message, $errors, $statusCode, $headers);
    }

    /**
     * Réponse not found (404)
     * 
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function notFound($message = 'Ressource non trouvée', $data = null, $statusCode = 404, $headers = [])
    {
        return new self(false, $message, $data, $statusCode, $headers);
    }

    /**
     * Réponse unauthorized (401)
     * 
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function unauthorized($message = 'Non autorisé', $data = null, $statusCode = 401, $headers = [])
    {
        return new self(false, $message, $data, $statusCode, $headers);
    }

    /**
     * Réponse forbidden (403)
     * 
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return ApiResponse
     */
    public static function forbidden($message = 'Accès interdit', $data = null, $statusCode = 403, $headers = [])
    {
        return new self(false, $message, $data, $statusCode, $headers);
    }

    /**
     * Réponse created (201)
     * 
     * @param string $message
     * @param mixed $data
     * @param array $headers
     * @return ApiResponse
     */
    public static function created($message = 'Création réussie', $data = null, $headers = [])
    {
        return new self(true, $message, $data, 201, $headers);
    }

    /**
     * Réponse no content (204)
     * 
     * @param string $message
     * @param array $headers
     * @return ApiResponse
     */
    public static function noContent($message = 'Aucun contenu', $headers = [])
    {
        return new self(true, $message, null, 204, $headers);
    }
}
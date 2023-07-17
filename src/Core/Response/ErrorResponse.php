<?php

declare(strict_types=1);

namespace App\Core\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends JsonResponse
{
    /**
     * @param array<string, string> $errors
     */
    public function __construct(
        int $code,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?string $message = null,
        array $errors = []
    ) {
        $data = [
            'error' => [
                'message' => $message,
                'code' => $code,
            ],
        ];

        if (!empty($errors)) {
            $data['error']['errors'] = $errors;
        }

        parent::__construct($data, $status);
    }
}

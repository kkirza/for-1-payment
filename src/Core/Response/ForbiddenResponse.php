<?php

declare(strict_types=1);

namespace App\Core\Response;

use Symfony\Component\HttpFoundation\Response;

class ForbiddenResponse extends ErrorResponse
{
    public function __construct(string $message = 'Доступ запрещён.')
    {
        parent::__construct(1003, Response::HTTP_FORBIDDEN, $message);
    }
}

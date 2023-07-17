<?php

declare(strict_types=1);

namespace App\Core\Response;

use Symfony\Component\HttpFoundation\Response;

class NotFoundResponse extends ErrorResponse
{
    public function __construct(string $message = 'Страница не найдена.')
    {
        parent::__construct(1004, Response::HTTP_FORBIDDEN, $message);
    }
}

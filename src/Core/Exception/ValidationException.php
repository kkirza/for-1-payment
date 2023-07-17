<?php

declare(strict_types=1);

namespace App\Core\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends Exception
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations
    )
    {
        parent::__construct('Ошибка валидации параметров', 1000);
    }

    public function getViolation() : array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}

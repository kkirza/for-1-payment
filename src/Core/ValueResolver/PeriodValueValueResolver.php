<?php

declare(strict_types=1);

namespace App\Core\ValueResolver;

use App\Core\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PeriodValueValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @return array<int, int>
     * @throws ValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (!$request->attributes->get('periodValue')) {
            return [];
        }

        $this->validate($request);

        /** @var string $id */
        $id = $request->attributes->get('periodValue');

        return [(int) $this->fillZeros($id)];
    }

    /**
     * @throws ValidationException
     */
    private function validate(Request $request): void
    {
        $id = $request->attributes->get('periodValue');

        $violations = $this->validator->validate($id, [
            new Assert\NotNull(),
            new Assert\Type('string'),
            new Assert\Regex(
                pattern: '/^[1-9]\d{0,18}$/',
                message: 'Значение должно быть числом не более 19 позиций',
            )
        ]);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }

    private function fillZeros(string $number): string
    {
        $zerosToAdd = 19 - strlen($number);
        if ($zerosToAdd > 0) {
            return $number . str_repeat('0', $zerosToAdd);
        } else {
            return $number;
        }
    }
}

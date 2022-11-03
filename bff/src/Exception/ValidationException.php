<?php

declare(strict_types=1);

namespace App\Exception;

use App\Dto\Exception\ExceptionDto;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends AppException
{
    public function __construct(ExceptionDto|string $errors)
    {
        if (is_string($errors)) {
            $errors = ExceptionDto::createFromString('Ошибка валидации', $errors);
        }

        parent::__construct($errors, Response::HTTP_BAD_REQUEST);
    }
}

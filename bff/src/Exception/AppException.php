<?php

declare(strict_types=1);

namespace App\Exception;

use App\Dto\Exception\ErrorDto;
use App\Dto\Exception\ExceptionDto;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AppException extends RuntimeException implements HttpExceptionInterface
{
    protected $code;

    protected ExceptionDto $exceptionDto;

    public function __construct(ExceptionDto|string $exceptionDto, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct("Ошибка приложения", $code);
        $this->code = $code;

        match (true) {
            is_string($exceptionDto) => $this->exceptionDto = ExceptionDto::createFromString('Ошибка приложения', $exceptionDto),
            $exceptionDto instanceof ExceptionDto => $this->exceptionDto = $exceptionDto,
            default => $this->exceptionDto = new ExceptionDto(),
        };

        $this->exceptionDto->code = $code;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getExceptionDto(): ExceptionDto
    {
        return $this->exceptionDto;
    }

    public function getErrorsText(): array
    {
        return $this->exceptionDto->errors->map(static fn (ErrorDto $dto) => $dto->description)->getValues();
    }

    public function getHeaders(): array
    {
        return [];
    }
}

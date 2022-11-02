<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\DtoInterface;

abstract class AbstractEvent implements EventInterface
{
    private int $statusCode = 200;

    private ?DtoInterface $requestDto = null;

    private ?DtoInterface $responseModel = null;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getRequestDto(): ?DtoInterface
    {
        return $this->requestDto;
    }

    public function setRequestDto(?DtoInterface $requestDto): self
    {
        $this->requestDto = $requestDto;

        return $this;
    }

    public function getResponseDto(): ?DtoInterface
    {
        return $this->responseModel;
    }

    public function setResponseDto(?DtoInterface $responseModel): self
    {
        $this->responseModel = $responseModel;

        return $this;
    }
}

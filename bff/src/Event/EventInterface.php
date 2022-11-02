<?php

declare(strict_types=1);


namespace App\Event;

use App\Dto\DtoInterface;

interface EventInterface
{
    public function setStatusCode(int $statusCode): self;

    public function getStatusCode(): int;

    public function setRequestDto(DtoInterface $dto): self;

    public function getRequestDto(): ?DtoInterface;

    public function setResponseDto(DtoInterface $dto): self;

    public function getResponseDto(): ?DtoInterface;
}
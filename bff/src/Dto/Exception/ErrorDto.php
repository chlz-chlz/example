<?php

declare(strict_types=1);

namespace App\Dto\Exception;

use App\Dto\DtoInterface;

class ErrorDto extends AbstractDto
{
    public ?string $field = null;

    public string $message;

    public ?string $description = null;

    public mixed $value = null;
}
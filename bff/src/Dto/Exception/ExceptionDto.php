<?php

declare(strict_types=1);

namespace App\Dto\Exception;

use App\Dto\AbstractDto;
use Doctrine\Common\Collections\ArrayCollection;

final class ExceptionDto extends AbstractDto
{
    public bool $success = false;

    public int $code;

    public ?string $errorCode = null;

    /** @psalm-var  ArrayCollection<int, ErrorDto> */
    public ArrayCollection $errors;

    public string $file;

    public int $line;

    public array $trace;

    public static function createFromString(string $message, ?string $description = null): self
    {
        return (new self([
            'errors' => new ArrayCollection([
                new ErrorDto([
                    'message' => $message,
                    'description' => $description,
                ])
            ])
        ]));
    }
}
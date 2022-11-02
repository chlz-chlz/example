<?php

declare(strict_types=1);

namespace App\Dto\Client;

use App\Dto\AbstractDto;

class EditInfoDto extends AbstractDto
{
    private string $clientGuid;

    private ?array $data = null;

    public function getClientGuid(): string
    {
        return $this->clientGuid;
    }

    public function setClientGuid(string $clientGuid): self
    {
        $this->clientGuid = $clientGuid;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }
}

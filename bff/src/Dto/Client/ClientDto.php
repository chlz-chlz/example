<?php

declare(strict_types=1);

namespace App\Dto\Client;

use App\Dto\AbstractDto;
use Doctrine\Common\Collections\ArrayCollection;

class ClientDto extends AbstractDto
{
    private string $guid;

    private ?ArrayCollection $addresses = null;

    private ?array $data = null;

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): void
    {
        $this->guid = $guid;
    }

    public function getAddresses(): ?ArrayCollection
    {
        return $this->addresses;
    }

    public function setAddresses(?array $addresses): self
    {
        $this->addresses = ($addresses && 0 < count($addresses)) ? new ArrayCollection($addresses) : null;

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

<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Dto\Client\EditInfoDto;

final class ClientService
{
    public function editInfo(EditInfoDto $editInfoDto): EditInfoDto
    {
        $event = (new EditInfoDto())->setRequestDto($editInfoDto);

        return $event->getResponseDto();
    }
}
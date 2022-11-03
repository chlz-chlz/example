<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Dto\Client\EditInfoDto;
use App\Dto\DtoInterface;
use App\Event\Client\EditInfoEvent;
use App\EventSubscriber\Client\EditInfoEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ClientService
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function editInfo(EditInfoDto $editInfoDto): DtoInterface
    {
        $event = (new EditInfoEvent())->setRequestDto($editInfoDto);
        $this->eventDispatcher->addSubscriber(new EditInfoEventSubscriber());
        $this->eventDispatcher->dispatch($event);

        return $event->getResponseDto();
    }
}
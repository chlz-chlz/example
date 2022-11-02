<?php

declare(strict_types=1);

namespace App\EventSubscriber\Client;

use App\Dto\Client\EditInfoDto;
use App\Event\Client\EditInfoEvent;
use App\Event\EventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EditInfoEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EditInfoEvent::class => EditInfoEvent::EVENT_NAME,
        ];
    }

    /**
     * @param EditInfoEvent $event
     * @return void
     */
    public function onEditInfoRequest(EventInterface $event): void
    {
        $event->setResponseDto(
            $event->getRequestDto()->setUuid('asdasdasd-12312-asdasda-2131')
        );
    }
}
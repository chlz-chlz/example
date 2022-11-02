<?php

declare(strict_types=1);

namespace App\Event\Client;

use App\Event\AbstractEvent;

final class EditInfoEvent extends AbstractEvent
{
    public const EVENT_NAME = 'onEditInfoRequest';
}
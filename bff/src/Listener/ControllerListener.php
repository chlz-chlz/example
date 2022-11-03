<?php

declare(strict_types=1);


namespace App\Listener;

use App\Controller\Api\Contracts\ApiErrorHandlerBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ControllerListener
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ApiExceptionListener $exceptionListener
    ) {
    }

    public function onController(ControllerEvent $event): void
    {
        /** @var AbstractController $controller */
        [$controller] = $event->getController();

        if ($controller instanceof ApiErrorHandlerBehavior) {
            $this->eventDispatcher->addListener(ExceptionEvent::class, [$this->exceptionListener, 'onException']);
        }
    }
}
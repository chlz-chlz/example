<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Service\System\HeartbeatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/heartbeat', name: 'heartbeat')]
final class HeartbeatController extends AbstractController
{
    public function __construct(private readonly HeartbeatService $heartbeatService)
    {
    }

    #[Route('/check', name: '_check', methods: ['GET'])]
    public function check(): Response
    {
        return $this->heartbeatService->check()
            ? $this->json(['status' => Response::HTTP_OK])
            : $this->json(['status' => Response::HTTP_INTERNAL_SERVER_ERROR])
            ;
    }
}

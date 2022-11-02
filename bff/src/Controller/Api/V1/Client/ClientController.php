<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Client;

use App\Dto\Client\EditInfoDto;
use App\Service\Client\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'client')]
final class ClientController extends AbstractController
{
    public function __construct(private readonly ClientService $clientService)
    {
    }

    public function editInfo(EditInfoDto $editInfoDto): Response
    {
        return $this->json($this->clientService->editInfo($editInfoDto));
    }
}
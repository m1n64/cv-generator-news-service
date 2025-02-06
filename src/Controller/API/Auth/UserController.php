<?php

declare(strict_types=1);

namespace App\Controller\API\Auth;

use App\Services\Log\LoggerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/user', name: 'user', methods: ['GET'])]
    public function user(LoggerService $loggerService): Response
    {
        $loggerService->log("User logged in: " . $this->getUser()->getEmail());

        return $this->json($this->getUser(), context: ['groups' => 'user:read']);
    }
}

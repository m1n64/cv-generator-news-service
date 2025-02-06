<?php

declare(strict_types=1);

namespace App\Controller\API\Logs;

use App\Services\Log\LoggerService;
use App\Services\User\Enums\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LogsController extends AbstractController
{
    #[Route('/api/logs', name: 'logs', methods: ['GET'])]
    #[IsGranted(RolesEnum::ROLE_ADMIN->value)]
    public function index(Request $request, LoggerService $loggerService): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $logs = $loggerService->getLogsWithPagination($page);

        return $this->json($logs, context: ['groups' => 'log:read']);
    }
}

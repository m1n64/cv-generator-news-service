<?php

declare(strict_types=1);

namespace App\Controller\API\News;

use App\Entity\News;
use App\Entity\User;
use App\Services\News\NewsService;
use App\Services\User\Enums\RolesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/news', name: 'news_')]
class NewsController extends AbstractController
{
    public function __construct(
        protected NewsService $newsService,
    )
    {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $news = $this->newsService->getNewsList($page, $limit);

        return $this->json($news, Response::HTTP_OK, [], ['groups' => 'news:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted(RolesEnum::ROLE_EDITOR->value)]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->newsService->createNews($data, $user);

        if (isset($result['errors'])) {
            return $this->json($result['errors'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result['news'], Response::HTTP_CREATED, [], ['groups' => 'news:read']);
    }
}

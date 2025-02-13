<?php

declare(strict_types=1);

namespace App\Controller\API\News;

use App\Entity\News;
use App\Entity\User;
use App\Services\News\Enums\NewsStatusesEnum;
use App\Services\News\NewsService;
use App\Services\User\Enums\RolesEnum;
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

    #[Route('/public', name: 'list-public', methods: ['GET'])]
    public function publicList(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $news = $this->newsService->getNewsList($page, $limit, NewsStatusesEnum::PUBLISHED->value);

        return $this->json($news, Response::HTTP_OK, [], ['groups' => 'news:read']);
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

    #[Route('/statuses', name: 'statuses', methods: ['GET'])]
    public function statuses(): JsonResponse
    {
        return $this->json($this->newsService->getStatuses(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(News $news): JsonResponse
    {
        return $this->json($news, Response::HTTP_OK, [], ['groups' => 'news:read']);
    }

    #[Route('/public/{id}', name: 'get-public', methods: ['GET'])]
    public function getPublic(News $news): JsonResponse
    {
        if ($news->getStatus() !== NewsStatusesEnum::PUBLISHED->value) {
            return $this->json(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($news, Response::HTTP_OK, [], ['groups' => 'news:read']);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    #[IsGranted(RolesEnum::ROLE_EDITOR->value)]
    public function update(News $news, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $result = $this->newsService->updateNews($news, $data);

        if (isset($result['error'])) {
            return $this->json($result, Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result['news'], Response::HTTP_OK, [], ['groups' => 'news:read']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted(RolesEnum::ROLE_EDITOR->value)]
    public function delete(News $news, NewsService $newsService): JsonResponse
    {
        $newsService->deleteNews($news);
        return $this->json(['message' => 'News deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}

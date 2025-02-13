<?php
declare(strict_types=1);

namespace App\Services\News;

use App\Entity\News;
use App\Entity\User;
use App\Services\Log\LoggerService;
use App\Services\News\DTO\NewsPaginateResponseDTO;
use App\Services\News\Enums\NewsStatusesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private LoggerService $loggerService,
    ) {}

    public function getNewsList(
        int $page = 1,
        int $limit = 10,
        ?string $status = null
    ): NewsPaginateResponseDTO {
        $queryBuilder = $this->em->getRepository(News::class)
            ->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC');

        if ($status !== null) {
            $queryBuilder->where('n.status = :status')
                ->setParameter('status', $status);
        }

        $totalItems = (clone $queryBuilder)
            ->resetDQLPart('orderBy')
            ->select('COUNT(n.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = (int) ceil($totalItems / $limit);

        $news = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return new NewsPaginateResponseDTO($page, $totalPages, $totalItems, $news);
    }


    public function createNews(array $data, User $author, NewsStatusesEnum $status = NewsStatusesEnum::DRAFT): array
    {
        $news = new News();
        $news->setTitle($data['title'] ?? '');
        $news->setContent($data['content'] ?? '');
        $news->setAuthor($author);
        $news->setStatus($status);
        $news->setCreatedAt(new \DateTimeImmutable());

        $errors = $this->validator->validate($news);
        if (count($errors) > 0) {
            return ['errors' => $errors];
        }

        $this->em->persist($news);
        $this->em->flush();

        $this->loggerService->log('News created: ' . $news->getTitle());

        return ['news' => $news];
    }

    public function updateNews(News $news, array $data): array
    {
        if (!$data) {
            return ['error' => 'Invalid JSON'];
        }

        if (!empty($data['title'])) {
            $news->setTitle($data['title']);
        }

        if (!empty($data['content'])) {
            $news->setContent($data['content']);
        }

        if (!empty($data['status'])) {
            $news->setStatus(NewsStatusesEnum::tryFrom($data['status']));
        }

        $news->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($news);
        $this->em->flush();

        $this->loggerService->log('News updated: ' . $news->getTitle());

        return ['news' => $news];
    }

    public function deleteNews(News $news): void
    {
        $this->em->remove($news);
        $this->em->flush();
    }

    public function getStatuses(): array
    {
        return (array) NewsStatusesEnum::getValues();
    }
}
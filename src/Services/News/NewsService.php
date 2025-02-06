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

    public function getNewsList(int $page = 1, int $limit = 10, string $status = NewsStatusesEnum::DRAFT->value): NewsPaginateResponseDTO
    {
        $queryBuilder = $this->em->getRepository(News::class)
            ->createQueryBuilder('n')
            ->where('n.status = :status')
            ->setParameter('status', $status)
            ->orderBy('n.createdAt', 'DESC');

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
        $news->setStatus($status->value);
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
}
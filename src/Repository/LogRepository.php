<?php

namespace App\Repository;

use App\Entity\Log;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @extends ServiceEntityRepository<Log>
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function create(string $action, User|null $user, Request|null $request): void
    {
        $log = new Log();
        $log->setAction($action);
        $log->setAuthor($user instanceof User ? $user : null);
        $log->setIpAddress($request ? $request->getClientIp() : '127.0.0.1');
        $log->setCreatedAt(new \DateTimeImmutable());

        $this->_em->persist($log);
        $this->_em->flush();
    }

    public function getLogsWithPagination(int $page, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('l')
            ->orderBy('l.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

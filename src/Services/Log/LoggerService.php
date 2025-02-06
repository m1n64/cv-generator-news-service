<?php
declare(strict_types=1);

namespace App\Services\Log;

use App\Entity\Log;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class LoggerService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        private Security $security,
    )
    {
    }

    public function log(string $action): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->security->getUser();

        $this->em->getRepository(Log::class)->create($action, $user instanceof User ? $user : null, $request);
    }

    public function getLogsWithPagination(int $page, int $limit = 10): array
    {
        return $this->em->getRepository(Log::class)->getLogsWithPagination($page, $limit);
    }
}
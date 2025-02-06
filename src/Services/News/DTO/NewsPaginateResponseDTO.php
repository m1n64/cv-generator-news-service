<?php
declare(strict_types=1);

namespace App\Services\News\DTO;

use Symfony\Component\Serializer\Annotation\Groups;

class NewsPaginateResponseDTO
{
    public function __construct(
        #[Groups(['news:read'])]
        public int $currentPage,

        #[Groups(['news:read'])]
        public int $totalPages,

        #[Groups(['news:read'])]
        public int $totalItems,

        #[Groups(['news:read'])]
        public array $items
    ) {}
}
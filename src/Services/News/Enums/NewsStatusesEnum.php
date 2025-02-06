<?php
declare(strict_types=1);

namespace App\Services\News\Enums;

enum NewsStatusesEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}

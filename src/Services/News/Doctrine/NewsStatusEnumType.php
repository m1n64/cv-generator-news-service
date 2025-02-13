<?php
declare(strict_types=1);

namespace App\Services\News\Doctrine;

use App\Services\News\Enums\NewsStatusesEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class NewsStatusEnumType extends Type
{
    const NAME = 'news_status_enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "VARCHAR(255)";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?NewsStatusesEnum
    {
        return $value !== null ? NewsStatusesEnum::tryFrom($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof NewsStatusesEnum ? $value->value : throw new \InvalidArgumentException("Invalid status");
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
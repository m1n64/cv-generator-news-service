<?php
declare(strict_types=1);

namespace App\Services\News\Serializer;

use App\Services\News\Enums\NewsStatusesEnum;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NewsStatusEnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $data, string $format = null, array $context = []): array|\ArrayObject|bool|float|int|null|string
    {
        return $data instanceof NewsStatusesEnum ? $data->value : null;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        return NewsStatusesEnum::tryFrom($data);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof NewsStatusesEnum;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_string($data) && is_a($type, NewsStatusesEnum::class, true);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [NewsStatusesEnum::class => true];
    }
}
<?php

namespace App\Infrastructure\Serializer;

use App\Domain\Model\Stock;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class StockNormalize extends GetSetMethodNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Stock;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return (array) parent::normalize($object, $format, $context);
    }
}

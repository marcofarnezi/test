<?php

namespace App\Infrastructure\Serializer;

use App\Domain\Model\Product;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ProductNormalize extends GetSetMethodNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Product;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return (array) parent::normalize($object, $format, $context);
    }
}

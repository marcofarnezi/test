<?php

namespace App\Infrastructure\Serializer;

use App\Domain\Model\OrderItem;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class OrderItemNormalize extends GetSetMethodNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof OrderItem;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return (array) parent::normalize($object, $format, $context);
    }
}


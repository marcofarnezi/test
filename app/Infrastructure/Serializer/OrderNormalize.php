<?php

namespace App\Infrastructure\Serializer;

use App\Domain\Model\Order;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class OrderNormalize extends GetSetMethodNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Order;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return (array) parent::normalize($object, $format, $context);
    }
}

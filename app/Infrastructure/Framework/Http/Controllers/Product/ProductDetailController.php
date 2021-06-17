<?php

namespace App\Infrastructure\Framework\Http\Controllers\Product;

use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductDetailController extends Controller
{
    private $productRepository;
    private $normalizer;

    public function __construct(
        ProductRepository $productRepository,
        NormalizerInterface $normalizer
    )
    {
        $this->productRepository = $productRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke(int $productId): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->normalizer->normalize(
                    $this->productRepository->get($productId),
                    null,
                    ['groups' => ['productDetailData']]
                ),
                200
            );
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }
}

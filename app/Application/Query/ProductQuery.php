<?php

namespace App\Application\Query;

use App\Domain\Model\Product;
use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Framework\Models\Stock as StockEntity;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Infrastructure\Repository\Eloquent\Transformer\StockTransformer;
use App\Infrastructure\Repository\Eloquent\Transformer\ProductTransformer;

class ProductQuery
{
    private $productTransformer;
    private $stockTransformer;
    private $productRepository;
    private $normalizer;

    public function __construct(
        ProductTransformer $productTransformer,
        StockTransformer $stockTransformer,
        ProductRepository $productRepository,
        NormalizerInterface $normalizer
    ) {
        $this->productTransformer = $productTransformer;
        $this->stockTransformer = $stockTransformer;
        $this->productRepository = $productRepository;
        $this->normalizer = $normalizer;
    }

    public function getProducts(?array $productsId = null): array
    {
        return (array) $this->normalizer->normalize(
            $this->productRepository->getProducts($productsId),
            null,
            ['groups' => ['productData']]
        );
    }

    public function getProductStocks(Product $product, int $amount, ?int $orderId = null): array
    {
        $productEntity = $this->productTransformer->domainToEntity($product);
        $stock = empty($orderId) ? $productEntity->stockAvailable->take($amount) : $productEntity->stockAvailable()
            ->leftJoin('order_items', 'order_items.stock_id', '=', 'stocks.id')
            ->whereRaw('stocks.id NOT IN(SELECT stock_id FROM order_items WHERE order_id = \''.$orderId.'\')')
            ->select('stocks.*')
            ->take($amount)
            ->get();

        return empty($stock) ? [] : array_map(
            function (StockEntity $stock) {
                return $this->stockTransformer->entityToDomain($stock);
            },
            $stock->all()
        );
    }

    public function find(int $productId): ?Product
    {
        return $this->productRepository->find($productId);
    }
}

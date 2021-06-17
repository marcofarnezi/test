<?php

namespace App\Infrastructure\Framework\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Application\Query\ProductQuery;
use App\Infrastructure\Framework\Http\Controllers\Controller;

class ProductsController extends Controller
{
    /**
     * @var ProductQuery
     */
    private $productQuery;

    public function __construct(ProductQuery $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function __invoke(): JsonResponse
    {
        $productsData = $this->productQuery->getProducts();
        if (is_null($productsData)) {
            return new JsonResponse($productsData, 404);
        }

        return new JsonResponse($productsData, 200);
    }
}

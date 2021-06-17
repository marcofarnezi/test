<?php

namespace App\Infrastructure\Framework\Http\Controllers\Cart;

use App\Application\Query\OrderQuery;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetOrderInfoController extends Controller
{
    private $normalizer;
    private $orderQuery;

    public function __construct(
        NormalizerInterface $normalizer,
        OrderQuery          $orderQuery
    )
    {
        $this->normalizer = $normalizer;
        $this->orderQuery = $orderQuery;
    }

    public function __invoke(int $orderId): JsonResponse
    {
        $order = $this->orderQuery->find($orderId);
        if (!empty($orderId) && empty($order)) {
            return new JsonResponse('Order not found', 404);
        }
        return new JsonResponse(
            $this->normalizer->normalize(
                [
                    'order' => $order,
                    'items' => $this->orderQuery->getItems($order)
                ],
                null,
                ['groups' => ['orderData', 'productData']]
            ),
            200
        );
    }
}

<?php

namespace App\Infrastructure\Framework\Http\Controllers\Cart;

use App\Application\Query\OrderQuery;
use App\Domain\Enum\OrderEnum;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RemoveProductController extends Controller
{
    private $orderQuery;
    private $normalizer;

    public function __construct(
        OrderQuery $orderQuery,
        NormalizerInterface $normalizer
    )
    {
        $this->orderQuery = $orderQuery;
        $this->normalizer = $normalizer;
    }

    public function __invoke(int $orderId, int $productId): JsonResponse
    {
        try {
            $order = $this->orderQuery->find($orderId);
            if (OrderEnum::CREATED !== $order->getStatus()) {
                return new JsonResponse('Order cannot be edited', 404);
            }
            $order = $this->orderQuery->removeItem($orderId, $productId);
            $items = $this->orderQuery->getItems($order);
            $order = $this->orderQuery->updateCoupon($order);
            return new JsonResponse(
                $this->normalizer->normalize(
                    [
                        'order' => $order,
                        'items' => $items
                    ],
                    null,
                    ['groups' => ['orderData', 'productData']]
                ),
                200
            );
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 404);
        }
    }
}

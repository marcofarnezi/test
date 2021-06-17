<?php

namespace App\Infrastructure\Framework\Http\Controllers\Cart;

use App\Application\Query\OrderQuery;
use App\Domain\Enum\OrderEnum;
use App\Domain\Repository\CouponRepository;
use App\Domain\Repository\OrderRepository;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AddCouponController extends Controller
{
    private $orderRepository;
    private $couponRepository;
    private $orderQuery;
    private $normalizer;

    public function __construct(
        OrderRepository $orderRepository,
        CouponRepository $couponRepository,
        OrderQuery $orderQuery,
        NormalizerInterface $normalizer
    )
    {
        $this->orderRepository = $orderRepository;
        $this->couponRepository = $couponRepository;
        $this->orderQuery = $orderQuery;
        $this->normalizer = $normalizer;
    }

    public function __invoke(string $couponCode, int $orderId): JsonResponse
    {
        try {
            $order = $this->orderRepository->get($orderId);
            if (OrderEnum::CREATED !== $order->getStatus()) {
                return new JsonResponse('Order cannot be edited', 404);
            }
            $user = $order->getUser();

            $coupon = $this->couponRepository->findByCode(
                $couponCode,
                empty($user) ? null : $user->getId()
            );

            if ($coupon) {
                $this->orderRepository->applyCoupon($order, $coupon);
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

            return new JsonResponse('This coupon cannot be applied', 400);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 404);
        }
    }
}

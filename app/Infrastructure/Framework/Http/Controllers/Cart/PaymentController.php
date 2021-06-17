<?php

namespace App\Infrastructure\Framework\Http\Controllers\Cart;

use App\Application\Query\OrderQuery;
use App\Domain\Enum\OrderEnum;
use App\Domain\Repository\PaymentRepository;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaymentController extends Controller
{
    private $paymentRepository;
    private $orderQuery;
    private $userRepository;
    private $normalizer;

    public function __construct(
        PaymentRepository $paymentRepository,
        OrderQuery $orderQuery,
        UserRepository $userRepository,
        NormalizerInterface $normalizer
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->orderQuery = $orderQuery;
        $this->userRepository = $userRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke(int $orderId, int $userId): JsonResponse
    {
        try {
            $user = $this->userRepository->get($userId);
            $order = $this->orderQuery->get($orderId);
            if (OrderEnum::CREATED !== $order->getStatus()) {
                return new JsonResponse('Order cannot be edited', 404);
            }
            if (empty($user) || empty($order)) {
                throw new \Exception('Error during payment');
            }
            $order = $this->paymentRepository->pay($order, $user);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 404);
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

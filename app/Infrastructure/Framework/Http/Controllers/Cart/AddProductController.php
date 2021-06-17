<?php

namespace App\Infrastructure\Framework\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Domain\Enum\OrderEnum;
use Illuminate\Http\JsonResponse;
use App\Application\Query\OrderQuery;
use App\Application\Query\ProductQuery;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AddProductController extends Controller
{
    private $normalizer;
    private $orderQuery;
    private $productQuery;
    private $userRepository;

    public function __construct(
        NormalizerInterface $normalizer,
        OrderQuery $orderQuery,
        ProductQuery $productQuery,
        UserRepository $userRepository
    ) {
        $this->normalizer = $normalizer;
        $this->orderQuery = $orderQuery;
        $this->productQuery = $productQuery;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, ?int $orderId = null): JsonResponse
    {
        $order = $this->orderQuery->find($orderId);
        if (! empty($orderId) && empty($order)) {
            return new JsonResponse('Order not found', 404);
        }
        if (empty($orderId)) {
            $user = null;
            if ($request->has('userId')) {
                $user = $this->userRepository->find($request->userId);
            }
            $order = $this->orderQuery->new($user);
        }
        if (OrderEnum::CREATED !== $order->getStatus()) {
            return new JsonResponse('Order cannot be edited', 404);
        }
        $product = $this->productQuery->find($request->productId);
        if (empty($product)) {
            return new JsonResponse('Product not found', 404);
        }
        $amount = $request->amount;
        $stockList = $this->productQuery->getProductStocks($product, $amount, $orderId);
        $this->orderQuery->saveItems(
            $order,
            $stockList
        );
        $order = $this->orderQuery->updateCoupon($order);

        return new JsonResponse(
            $this->normalizer->normalize(
                [
                    'order' => $order,
                    'items' => $this->orderQuery->getItems($order),
                ],
                null,
                ['groups' => ['orderData', 'productData']]
            ),
            200
        );
    }
}

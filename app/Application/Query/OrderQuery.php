<?php

namespace App\Application\Query;

use Carbon\Carbon;
use App\Domain\Model\User;
use App\Domain\Model\Order;
use App\Domain\Model\Stock;
use App\Domain\Enum\OrderEnum;
use App\Domain\Model\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Domain\Database\Transaction;
use App\Domain\Repository\OrderRepository;
use App\Domain\Repository\CouponRepository;
use App\Domain\Repository\OrderItemRepository;
use App\Infrastructure\Framework\Models\Order as OrderEntity;
use App\Infrastructure\Repository\Eloquent\Transformer\OrderTransformer;
use App\Infrastructure\Repository\Eloquent\Transformer\ProductTransformer;
use App\Infrastructure\Repository\Eloquent\Transformer\OrderItemTransformer;

class OrderQuery
{
    private $transaction;
    private $orderRepository;
    private $orderItemRepository;
    private $orderTransformer;
    private $orderItemTransformer;
    private $productTransformer;
    private $couponRepository;

    public function __construct(
        Transaction $transaction,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        OrderTransformer $orderTransformer,
        OrderItemTransformer $orderItemTransformer,
        ProductTransformer $productTransformer,
        CouponRepository $couponRepository
    ) {
        $this->transaction = $transaction;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->orderTransformer = $orderTransformer;
        $this->orderItemTransformer = $orderItemTransformer;
        $this->productTransformer = $productTransformer;
        $this->couponRepository = $couponRepository;
    }

    public function find(?int $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function get(?int $id): Order
    {
        return $this->orderRepository->get($id);
    }

    public function new(?User $user = null): Order
    {
        return $this->orderRepository->new($user);
    }

    public function newItem(Order $order, Stock $stock): OrderItem
    {
        return $this->orderItemRepository->new($order, $stock);
    }

    public function saveItems(Order &$order, array $stocks): array
    {
        $this->transaction->beginTransaction();
        $total = $order->getTotal();
        $items = [];
        try {
            /** @var Stock $stock */
            foreach ($stocks as $stock) {
                $items[] = $this->newItem($order, $stock);
                $total += $stock->getPrice();
            }
            $order->setTotal($total);
            $this->orderRepository->save($order);
            $this->transaction->commit();
        } catch (\Exception $e) {
            $this->transaction->rollBack();

            return [];
        }

        return $items;
    }

    public function getItemOrderByProduct(int $orderId, int $productId): ?OrderItem
    {
        $orderItem = DB::table('order_items')
            ->join('stocks', 'order_items.stock_id', '=', 'stocks.id')
            ->where('stocks.product_id', $productId)
            ->where('order_items.order_id', $orderId)
            ->select('order_items.id')
            ->first();

        return empty($orderItem) ? null : $this->orderItemRepository->find($orderItem->id);
    }

    public function removeItem(int $orderId, int $productId): Order
    {
        $orderItem = $this->getItemOrderByProduct($orderId, $productId);
        $order = $this->orderRepository->find($orderId);
        if (empty($orderItem)) {
            return $order;
        }
        $this->transaction->beginTransaction();
        try {
            $amount = $orderItem->getStock()->getPrice();
            $total = $order->getTotal();
            $this->orderItemRepository->remove($orderItem);
            $order->setTotal($total - $amount);
            $this->orderRepository->save($order);
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
        }

        return $order;
    }

    public function updateCoupon(Order $order): Order
    {
        if ($order->getCoupon()) {
            $order = $this->orderRepository->applyCoupon($order, $order->getCoupon());
        }

        return $order;
    }

    public function getItems(Order $order): ?array
    {
        $orderEntity = $this->orderTransformer->domainToEntity($order);
        $items = [];
        foreach ($orderEntity->items->all() as $orderItem) {
            $productId = $orderItem->stock->product->id;
            if (! array_key_exists($productId, $items)) {
                $items[$productId]['count'] = 1;
                $items[$productId]['product'] = $this->productTransformer->entityToDomain($orderItem->stock->product);
                continue;
            }
            ++$items[$productId]['count'];
        }

        return array_values($items);
    }

    public function getUsersFirstPurchase(): array
    {
        $results = OrderEntity::select('orders.user_id')
            ->groupBy('orders.user_id')
            ->leftJoin('coupons', 'coupons.user_id', '=', 'orders.user_id')
            ->havingRaw('count(orders.id) = 1')
            ->where('orders.created_at', '<', Carbon::now()->addMinutes(15))
            ->where('orders.status', '=', OrderEnum::PAID)
            ->whereNull('coupons.id')
            ->get()
            ;

        $users = [];
        foreach ($results as $result) {
            $users[] = $result->user_id;
        }

        return $users;
    }
}

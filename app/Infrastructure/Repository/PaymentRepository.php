<?php

namespace App\Infrastructure\Repository;

use Carbon\Carbon;
use App\Domain\Model\User;
use App\Domain\Model\Order;
use App\Domain\Enum\OrderEnum;
use App\Domain\Database\Transaction;
use App\Application\Query\ProductQuery;
use App\Domain\Repository\OrderRepository;
use App\Domain\Repository\StockRepository;
use App\Domain\Repository\CouponRepository;
use App\Infrastructure\Framework\Models\Order as OrderEntity;
use App\Infrastructure\Framework\Models\OrderItem as OrderItemEntity;
use App\Domain\Repository\PaymentRepository as PaymentDomainRepository;
use App\Infrastructure\Repository\Eloquent\Transformer\OrderTransformer;
use App\Infrastructure\Repository\Eloquent\Transformer\StockTransformer;
use App\Infrastructure\Repository\Eloquent\Transformer\CouponTransformer;

class PaymentRepository implements PaymentDomainRepository
{
    private $transaction;
    private $orderRepository;
    private $stockRepository;
    private $couponRepository;
    private $orderTransformer;
    private $stockTransformer;
    private $couponTransformer;
    private $productQuery;

    public function __construct(
        Transaction $transaction,
        OrderRepository $orderRepository,
        StockRepository $stockRepository,
        CouponRepository $couponRepository,
        OrderTransformer $orderTransformer,
        StockTransformer $stockTransformer,
        CouponTransformer $couponTransformer,
        ProductQuery $productQuery
    ) {
        $this->transaction = $transaction;
        $this->orderRepository = $orderRepository;
        $this->stockRepository = $stockRepository;
        $this->couponRepository = $couponRepository;
        $this->orderTransformer = $orderTransformer;
        $this->stockTransformer = $stockTransformer;
        $this->couponTransformer = $couponTransformer;
        $this->productQuery = $productQuery;
    }

    private function replaceItemsSold(OrderEntity $orderEntity): void
    {
        $itemsSold = $orderEntity->items()
            ->join('stocks', 'order_items.stock_id', '=', 'stocks.id')
            ->whereNotNull('stocks.sold_at')->get()->all();
        dd($itemsSold);
        $numItemSold = count($itemsSold);
        if ($numItemSold > 0) {
            $stocksAvailable = $this->productQuery->getProductStocks(end($itemsSold)->product->id, count($itemsSold));
            if (count($stocksAvailable) !== (count($itemsSold)));
        }
    }

    public function pay(Order $order, User $user): Order
    {
        $orderEntity = $this->orderTransformer->domainToEntity($order);
        $items = $orderEntity->items;
        $couponEntity = $orderEntity->coupon;
        $this->transaction->beginTransaction();
        try {
            array_map(
                function (OrderItemEntity $item) {
                $stock = $this->stockTransformer->entityToDomain($item->stock);
                $stock->setSoldAt(Carbon::now());

                return $this->stockRepository->save($stock);
            },
                $items->all()
            );
            $order->setUser($user);
            $order->setStatus(OrderEnum::PROCESSING);
            $this->orderRepository->save($order);
            if (! empty($couponEntity)) {
                $couponEntity->user_at = Carbon::now();
                $this->couponRepository->save($this->couponTransformer->entityToDomain($couponEntity));
            }
            $this->transaction->commit();
        } catch (\Exception $exception) {
            $this->transaction->rollBack();
            throw $exception;
        }

        /*
         * after bank transaction
         */

        /*
         * @todo trigger coupon
         */

        $order->setStatus(OrderEnum::PAID);
        $this->orderRepository->save($order);

        return $order;
    }
}

<?php

namespace App\Domain\Model;

class OrderItem
{
    private $id;
    private $stock;
    private $coupon;
    private $order;

    public function __construct(
        int $id,
        Order $order,
        Stock $stock,
        ?Coupon $coupon = null
    )
    {
        $this->id = $id;
        $this->order = $order;
        $this->stock = $stock;
        $this->coupon = $coupon;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     */
    public function setStock(Stock $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return Coupon|null
     */
    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    /**
     * @param Coupon|null $coupon
     */
    public function setCoupon(?Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }


}

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
    ) {
        $this->id = $id;
        $this->order = $order;
        $this->stock = $stock;
        $this->coupon = $coupon;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStock(): Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): void
    {
        $this->stock = $stock;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}

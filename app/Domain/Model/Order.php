<?php

namespace App\Domain\Model;

class Order
{
    private $id;
    private $user;
    private $coupon;
    private $total;
    private $discount;
    private $status;

    public function __construct(
        int $id,
        int $total,
        string $status,
        ?int $discount = null,
        ?User $user = null,
        ?Coupon $coupon = null
    ) {
        $this->id = $id;
        $this->total = $total;
        $this->status = $status;
        $this->discount = $discount;
        $this->user = $user;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): void
    {
        $this->discount = $discount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }
}

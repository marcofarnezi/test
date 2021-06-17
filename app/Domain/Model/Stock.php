<?php

namespace App\Domain\Model;

class Stock
{
    private $id;
    private $product;
    private $price;
    private $soldAt;

    public function __construct(
        int $id,
        Product $product,
        int $price,
        ?\DateTime $soldAt
    ) {
        $this->id = $id;
        $this->product = $product;
        $this->price = $price;
        $this->soldAt = $soldAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getSoldAt(): ?\DateTime
    {
        return $this->soldAt;
    }

    public function setSoldAt(?\DateTime $soldAt): void
    {
        $this->soldAt = $soldAt;
    }
}

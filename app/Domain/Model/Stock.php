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
    )
    {
        $this->id = $id;
        $this->product = $product;
        $this->price = $price;
        $this->soldAt = $soldAt;
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
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return \DateTime|null
     */
    public function getSoldAt(): ?\DateTime
    {
        return $this->soldAt;
    }

    /**
     * @param \DateTime|null $soldAt
     */
    public function setSoldAt(?\DateTime $soldAt): void
    {
        $this->soldAt = $soldAt;
    }
}

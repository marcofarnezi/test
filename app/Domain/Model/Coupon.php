<?php

namespace App\Domain\Model;

class Coupon
{
    private $id;
    private $campaign;
    private $user;
    private $code;
    private $discountAmount;
    private $type;
    private $startAt;
    private $endAt;

    public function __construct(
        int $id,
        string $code,
        int $discountAmount,
        string $type,
        \DateTime $startAt,
        ?\DateTime $endAt = null,
        ?Campaign $campaign = null,
        ?User $user = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->discountAmount = $discountAmount;
        $this->type = $type;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->campaign = $campaign;
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }
}

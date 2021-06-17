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
    )
    {
        $this->id = $id;
        $this->code = $code;
        $this->discountAmount = $discountAmount;
        $this->type = $type;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->campaign = $campaign;
        $this->user = $user;
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
     * @return Campaign|null
     */
    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    /**
     * @param Campaign|null $campaign
     */
    public function setCampaign(?Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    /**
     * @param int $discountAmount
     */
    public function setDiscountAmount(int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime|null $endAt
     */
    public function setEndAt(?\DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }
}

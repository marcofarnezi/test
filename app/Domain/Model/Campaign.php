<?php

namespace App\Domain\Model;

class Campaign
{
    private $id;
    private $name;
    private $startAt;
    private $endAt;

    public function __construct(
        int $id,
        string $name,
        \DateTime $startAt,
        \DateTime $endAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): \DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }
}

<?php

namespace App\DTO\Request\Promo;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePromoDTO
{
    public ?string $name = null;

    public ?string $description = null;

    #[Assert\Range(min: 0, max: 100)]
    public ?float $discount = null;

    #[Assert\GreaterThanOrEqual('today')]
    public ?\DateTimeInterface $expiredDate = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $amount = null;

    #[Assert\Choice(choices: ['Public', 'Silver', 'Gold'], message: 'Invalid condition')]
    public ?string $conditions = null;

    public function __construct(?string $name, ?string $description, ?float $discount, ?\DateTimeInterface $expiredDate, ?int $amount, ?string $conditions)
    {
        $this->name = $name;
        $this->description = $description;
        $this->discount = $discount;
        $this->expiredDate = $expiredDate;
        $this->amount = $amount;
        $this->conditions = $conditions;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function getExpiredDate(): ?\DateTimeInterface
    {
        return $this->expiredDate;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
    }

    public function setExpiredDate(?\DateTimeInterface $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function setConditions(?string $conditions): void
    {
        $this->conditions = $conditions;
    }
}

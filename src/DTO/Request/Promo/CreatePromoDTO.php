<?php

namespace App\DTO\Request\Promo;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePromoDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $name;

    public string $imgUrl;

    public ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 100)]
    public float $discount;

    public \DateTimeInterface $createdDate;

    #[Assert\NotBlank]
    public \DateTimeInterface $expiredDate;

    #[Assert\PositiveOrZero]
    public int $amount;

    #[Assert\Choice(choices: ['Public', 'Silver', 'Gold'])]
    public string $conditions;

    public function __construct(string $name, string $imgUrl, ?string $description, float $discount, \DateTimeInterface $expiredDate, int $amount, string $conditions)
    {
        $this->name = $name;
        $this->imgUrl = $imgUrl;
        $this->description = $description;
        $this->discount = $discount;
        $this->createdDate = new \DateTime();
        $this->expiredDate = $expiredDate;
        $this->amount = $amount;
        $this->conditions = $conditions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getCreatedDate(): \DateTimeInterface
    {
        return $this->createdDate;
    }
    public function getExpiredDate(): \DateTimeInterface
    {
        return $this->expiredDate;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getConditions(): string
    {
        return $this->conditions;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function setCreatedDate(): void
    {
        $this->createdDate = new \DateTime();
    }

    public function setExpiredDate(\DateTimeInterface $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setConditions(string $conditions): void
    {
        $this->conditions = $conditions;
    }
}

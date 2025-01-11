<?php

namespace App\DTO\Request\Booking;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Promo;
class CreateBookingDTO
{
    #[Assert\NotBlank]
    public int $userId;

    public ?Promo $promoId = null;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public float $totalPrice;

    #[Assert\Choice(choices: ['Pending', 'Confirmed', 'Cancelled'])]
    public string $status;

    public function __construct(int $userId, ?Promo $promoId, float $totalPrice, string $status)
    {
        $this->userId = $userId;
        $this->promoId = $promoId;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPromoId(): ?Promo
    {
        return $this->promoId;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setPromoId(?int $promoId): void
    {
        $this->promoId = $promoId;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}

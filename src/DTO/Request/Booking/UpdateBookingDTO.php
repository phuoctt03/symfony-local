<?php

namespace App\DTO\Request\Booking;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateBookingDTO
{
    #[Assert\GreaterThanOrEqual(0)]
    public ?float $totalPrice = null;

    #[Assert\Choice(choices: ['Pending', 'Confirmed', 'Cancelled'], message: 'Invalid status')]
    public ?string $status = null;

    public function __construct(?float $totalPrice, ?string $status)
    {
        $this->totalPrice = $totalPrice;
        $this->status = $status;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setTotalPrice(?float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}

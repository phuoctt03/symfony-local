<?php

namespace App\DTO\Request\BookingDetail;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateBookingDetailDTO
{
    #[Assert\GreaterThanOrEqual(1)]
    public ?int $quantity = null;

    #[Assert\GreaterThan('now')]
    public ?\DateTimeInterface $checkInDate = null;

    #[Assert\GreaterThan(propertyPath: 'checkInDate')]
    public ?\DateTimeInterface $checkOutDate = null;

    public function __construct(?int $quantity, ?\DateTimeInterface $checkInDate, ?\DateTimeInterface $checkOutDate)
    {
        $this->quantity = $quantity;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->checkOutDate;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setCheckInDate(?\DateTimeInterface $checkInDate): void
    {
        $this->checkInDate = $checkInDate;
    }

    public function setCheckOutDate(?\DateTimeInterface $checkOutDate): void
    {
        $this->checkOutDate = $checkOutDate;
    }
}

<?php

namespace App\DTO\Request\BookingDetail;

use App\Entity\Activity;
use App\Entity\Combo;
use App\Entity\Flight;
use App\Entity\Hotel;
use Symfony\Component\Validator\Constraints as Assert;

class CreateBookingDetailDTO
{
    #[Assert\NotBlank]
    public int $bookingId;

    public ?Flight $flightId = null;
    public ?Hotel $hotelId = null;
    public ?Activity $activityId = null;
    public ?Combo $comboId = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $quantity;

    #[Assert\NotBlank]
    public ?\DateTimeInterface $checkInDate;

    #[Assert\GreaterThan(propertyPath: 'checkInDate')]
    public ?\DateTimeInterface $checkOutDate;

    public function __construct(int $bookingId, ?Flight $flightId, ?Hotel $hotelId, ?Activity $activityId, ?Combo $comboId, int $quantity, ?\DateTimeInterface $checkInDate, ?\DateTimeInterface $checkOutDate)
    {
        $this->bookingId = $bookingId;
        $this->flightId = $flightId;
        $this->hotelId = $hotelId;
        $this->activityId = $activityId;
        $this->comboId = $comboId;
        $this->quantity = $quantity;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
    }

    public function getBookingId(): int
    {
        return $this->bookingId;
    }

    public function getFlightId(): ?Flight
    {
        return $this->flightId;
    }

    public function getHotelId(): ?Hotel
    {
        return $this->hotelId;
    }

    public function getActivityId(): ?Activity
    {
        return $this->activityId;
    }

    public function getComboId(): ?Combo
    {
        return $this->comboId;
    }

    public function getQuantity(): int
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

    public function setBookingId(int $bookingId): void
    {
        $this->bookingId = $bookingId;
    }

    public function setFlightId(?Flight $flightId): void
    {
        $this->flightId = $flightId;
    }

    public function setHotelId(?Hotel $hotelId): void
    {
        $this->hotelId = $hotelId;
    }

    public function setActivityId(?Activity $activityId): void
    {
        $this->activityId = $activityId;
    }

    public function setComboId(?Combo $comboId): void
    {
        $this->comboId = $comboId;
    }

    public function setQuantity(int $quantity): void
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

    public function toArray(): array
    {
        return [
            'bookingId' => $this->bookingId,
            'flightId' => $this->flightId,
            'hotelId' => $this->hotelId,
            'activityId' => $this->activityId,
            'comboId' => $this->comboId,
            'quantity' => $this->quantity,
            'checkInDate' => $this->checkInDate ? $this->checkInDate->format('Y-m-d H:i:s') : null,
            'checkOutDate' => $this->checkOutDate ? $this->checkOutDate->format('Y-m-d H:i:s') : null,
        ];
    }
}

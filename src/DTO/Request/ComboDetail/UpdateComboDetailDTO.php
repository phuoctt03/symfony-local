<?php

namespace App\DTO\Request\ComboDetail;

class UpdateComboDetailDTO
{
    public ?int $flightId = null;

    public ?int $hotelId = null;

    public ?int $activityId = null;

    public ?\DateTimeInterface $checkInDate = null;

    public ?\DateTimeInterface $checkOutDate = null;

    public function __construct(?int $flightId, ?int $hotelId, ?int $activityId, ?\DateTimeInterface $checkInDate, ?\DateTimeInterface $checkOutDate)
    {
        $this->flightId = $flightId;
        $this->hotelId = $hotelId;
        $this->activityId = $activityId;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
    }

    public function getFlightId(): ?int
    {
        return $this->flightId;
    }

    public function getHotelId(): ?int
    {
        return $this->hotelId;
    }

    public function getActivityId(): ?int
    {
        return $this->activityId;
    }

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->checkOutDate;
    }

    public function setFlightId(?int $flightId): void
    {
        $this->flightId = $flightId;
    }

    public function setHotelId(?int $hotelId): void
    {
        $this->hotelId = $hotelId;
    }

    public function setActivityId(?int $activityId): void
    {
        $this->activityId = $activityId;
    }

    public function setCheckInDate(\DateTimeInterface $checkInDate): void
    {
        $this->checkInDate = $checkInDate;
    }

    public function setCheckOutDate(\DateTimeInterface $checkOutDate): void
    {
        $this->checkOutDate = $checkOutDate;
    }
}

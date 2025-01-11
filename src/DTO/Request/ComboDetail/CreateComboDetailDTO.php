<?php

namespace App\DTO\Request\ComboDetail;

use App\Entity\Activity;
use App\Entity\Combo;
use App\Entity\Flight;
use App\Entity\Hotel;
use Symfony\Component\Validator\Constraints as Assert;

class CreateComboDetailDTO
{
    #[Assert\NotBlank]
    public Combo $comboId;

    public ?Flight $flightId = null;
    public ?Hotel $hotelId = null;
    public ?Activity $activityId = null;
    public ?\DateTimeInterface $checkInDate = null;
    public ?\DateTimeInterface $checkOutDate = null;

    public function __construct(Combo $comboId, ?Flight $flightId, ?Hotel $hotelId, ?Activity $activityId, ?\DateTimeInterface $checkInDate, ?\DateTimeInterface $checkOutDate)
    {
        $this->comboId = $comboId;
        $this->flightId = $flightId;
        $this->hotelId = $hotelId;
        $this->activityId = $activityId;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
    }

    public function getComboId(): Combo
    {
        return $this->comboId;
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

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->checkOutDate;
    }

    public function setComboId(Combo $comboId): void
    {
        $this->comboId = $comboId;
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

    public function setCheckInDate(\DateTimeInterface $checkInDate): void
    {
        $this->checkInDate = $checkInDate;
    }

    public function setCheckOutDate(\DateTimeInterface $checkOutDate): void
    {
        $this->checkOutDate = $checkOutDate;
    }
}

<?php

namespace App\DTO\Response\ComboDetail;

use App\Entity\ComboDetail;

class ComboDetailResponseDTO
{
    public int $id;
    public int $comboId;
    public ?int $flightId;
    public ?int $hotelId;
    public ?int $activityId;
    public ?\DateTimeInterface $checkInDate;
    public ?\DateTimeInterface $checkOutDate;

    // Constructor to initialize DTO from a ComboDetail entity
    public function __construct(ComboDetail $comboDetail)
    {
        $this->id = $comboDetail->getComboDetailId(); // assuming getComboDetailId() exists
        $this->comboId = $comboDetail->getCombo()->getComboId(); // assuming getCombo() returns a Combo entity
        $this->flightId = $comboDetail->getFlight() ? $comboDetail->getFlight()->getFlightId() : null; // nullable check
        $this->hotelId = $comboDetail->getHotel() ? $comboDetail->getHotel()->getHotelId() : null; // nullable check
        $this->activityId = $comboDetail->getActivity() ? $comboDetail->getActivity()->getActivityId() : null; // nullable check
        $this->checkInDate = $comboDetail->getCheckInDate() ? $comboDetail->getCheckInDate(): null; // nullable check
        $this->checkOutDate = $comboDetail->getCheckOutDate() ? $comboDetail->getCheckOutDate() : null;
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'comboId' => $this->comboId,
            'flightId' => $this->flightId,
            'hotelId' => $this->hotelId,
            'activityId' => $this->activityId,
            'checkInDate' => $this->checkInDate ? $this->checkInDate->format('Y-m-d H:i:s') : null,
            'checkOutDate' => $this->checkOutDate ? $this->checkOutDate->format('Y-m-d H:i:s') : null,
        ];
    }
}

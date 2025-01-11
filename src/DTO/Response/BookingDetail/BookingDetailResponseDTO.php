<?php

namespace App\DTO\Response\BookingDetail;

use App\Entity\BookingDetail;

class BookingDetailResponseDTO
{
    public int $id;
    public int $bookingId;
    public ?int $flightId;
    public ?int $hotelId;
    public ?int $activityId;
    public ?int $comboId;
    public int $quantity;
    public ?\DateTimeInterface $checkInDate;
    public ?\DateTimeInterface $checkOutDate;

    // Constructor to initialize DTO from a BookingDetail entity
    public function __construct(BookingDetail $bookingDetail)
    {
        $this->id = $bookingDetail->getBookingDetailId(); // assuming getBookingDetailId() exists
        $this->bookingId = $bookingDetail->getBooking()->getBookingId(); // assuming getBooking() returns a Booking object
        $this->flightId = $bookingDetail->getFlight() ? $bookingDetail->getFlight()->getFlightId() : null; // assuming getFlight() returns a Flight
        $this->hotelId = $bookingDetail->getHotel() ? $bookingDetail->getHotel()->getHotelId() : null; // assuming getHotel() returns a Hotel
        $this->activityId = $bookingDetail->getActivity() ? $bookingDetail->getActivity()->getActivityId() : null; // assuming getActivity() returns an Activity
        $this->comboId = $bookingDetail->getCombo() ? $bookingDetail->getCombo()->getComboId() : null; // assuming getCombo() returns a Combo
        $this->quantity = $bookingDetail->getQuantity();
        $this->checkInDate = $bookingDetail->getCheckInDate() ? $bookingDetail->getCheckInDate() : null;
        $this->checkOutDate = $bookingDetail->getCheckOutDate() ? $bookingDetail->getCheckOutDate() : null;
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
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

<?php

namespace App\DTO\Response\Booking;

use App\Entity\Booking;

class BookingResponseDTO
{
    private int $id;
    private int $userId;
    private ?int $promoId;
    private \DateTimeInterface $bookingDate;
    private float $totalPrice;
    private string $status;

    // Constructor to initialize DTO from a Booking entity
    public function __construct(Booking $booking)
    {
        $this->id = $booking->getBookingId(); // assuming getBookingId() exists
        $this->userId = $booking->getUser()->getId(); // assuming getUser() returns a User object
        $this->promoId = $booking->getPromo() ? $booking->getPromo()->getPromoId() : null; // assuming getPromo() returns Promo
        $this->bookingDate = $booking->getBookingDate();
        $this->totalPrice = $booking->getTotalPrice();
        $this->status = $booking->getStatus()->value; // assuming status is an enum and using its value
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'promoId' => $this->promoId,
            'bookingDate' => $this->bookingDate->format('Y-m-d H:i:s'), // format date if needed
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
        ];
    }
}

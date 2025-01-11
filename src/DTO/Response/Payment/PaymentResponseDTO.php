<?php

namespace App\DTO\Response\Payment;

use App\Entity\Payment;

class PaymentResponseDTO
{
    public int $id;
    public int $userId;
    public int $bookingId;
    public string $paymentMethod;
    public \DateTimeInterface $paymentDate;

    // Constructor to initialize DTO from a Payment entity
    public function __construct(Payment $payment)
    {
        $this->id = $payment->getPaymentId(); // assuming getPaymentId() exists
        $this->userId = $payment->getUser()->getId(); // assuming getUser() and getId() exist
        $this->bookingId = $payment->getBooking()->getBookingId(); // assuming getBooking() and getBookingId() exist
        $this->paymentMethod = $payment->getPaymentMethod()->value; // assuming getPaymentMethod() exists and it's an enum
        $this->paymentDate = $payment->getPaymentDate(); // assuming getPaymentDate() exists
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'bookingId' => $this->bookingId,
            'paymentMethod' => $this->paymentMethod,
            'paymentDate' => $this->paymentDate->format('Y-m-d H:i:s'), // formatting DateTime for JSON
        ];
    }
}

<?php

namespace App\DTO\Request\Payment;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
class CreatePaymentDTO
{
    #[Assert\NotBlank]
    public User $userId;

    #[Assert\NotBlank]
    public int $bookingId;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['Credit Card', 'Debit Card', 'PayPal', 'Cash'])]
    public string $paymentMethod;

    public function __construct(User $userId, int $bookingId, string $paymentMethod)
    {
        $this->userId = $userId;
        $this->bookingId = $bookingId;
        $this->paymentMethod = $paymentMethod;
    }

    public function getUserId(): User
    {
        return $this->userId;
    }

    public function getBookingId(): int
    {
        return $this->bookingId;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setUserId(User $userId): void
    {
        $this->userId = $userId;
    }

    public function setBookingId(int $bookingId): void
    {
        $this->bookingId = $bookingId;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}

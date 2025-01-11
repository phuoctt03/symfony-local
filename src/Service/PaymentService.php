<?php

namespace App\Service;

use App\DTO\Request\Payment\CreatePaymentDTO;
use App\DTO\Request\Payment\UpdatePaymentDTO;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\PaymentMethod;

class PaymentService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createPayment(CreatePaymentDTO $paymentDTO): Payment
    {
        $payment = new Payment();
        // Assuming CreatePaymentDTO has methods to get the necessary data
        $user = $this->entityManager->getRepository(User::class)->find($paymentDTO->getUserId());
        $payment->setUser($user);
        $booking = $this->entityManager->getRepository(Booking::class)->find($paymentDTO->getBookingId());
        $payment->setBooking($booking);
        $paymentMethod = PaymentMethod::from($paymentDTO->getPaymentMethod());
        $payment->setPaymentMethod($paymentMethod);
        $payment->setPaymentDate(new \DateTime());
        // Add other necessary fields here

        $this->entityManager->persist($payment);
        $this->entityManager->flush();
        return $payment;
    }

    public function getAllPayments(): array
    {
        return $this->entityManager->getRepository(Payment::class)->findAll();
    }

    public function getPaymentById(int $id): ?Payment
    {
        return $this->entityManager->getRepository(Payment::class)->find($id);
    }

    public function deletePayment(int $id): void
    {
        $payment = $this->getPaymentById($id);
        if ($payment) {
            $this->entityManager->remove($payment);
            $this->entityManager->flush();
        }
    }
}

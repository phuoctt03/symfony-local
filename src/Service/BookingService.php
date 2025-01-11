<?php

namespace App\Service;

use App\DTO\Request\Booking\CreateBookingDTO;
use App\DTO\Request\Booking\UpdateBookingDTO;
use App\Entity\Booking;
use App\Entity\User;
use App\Enum\BookingStatus;
use Doctrine\ORM\EntityManagerInterface;

class BookingService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createBooking(CreateBookingDTO $bookingDTO): Booking
    {
        $booking = new Booking();
        $now = new \DateTime();
        $bookingDate = \DateTime::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        $user = $this->entityManager->getRepository(User::class)->find($bookingDTO->getUserId());
        $booking->setUser($user);
        $booking->setBookingDate($bookingDate);
        $booking->setPromo($bookingDTO->getPromoId());
        $booking->setTotalPrice($bookingDTO->getTotalPrice());
        $booking->setStatus(BookingStatus::from($bookingDTO->getStatus()));

        $this->entityManager->persist($booking);
        $this->entityManager->flush();
        return $booking;
    }

    public function getAllBookings(): array
    {
        return $this->entityManager->getRepository(Booking::class)->findAll();
    }

    public function getBookingsByUserId(int $userId): array
    {
        return $this->entityManager->getRepository(Booking::class)->findBy(['user' => $userId]);
    }

    public function getBookingById(int $id): ?Booking
    {
        return $this->entityManager->getRepository(Booking::class)->find($id);
    }

    public function updateBooking(int $id, UpdateBookingDTO $bookingDTO): Booking
    {
        $booking = $this->getBookingById($id);
        if (!$booking) {
            throw new \Exception('Booking not found');
        }

        $booking->setTotalPrice($bookingDTO->getTotalPrice());
        $booking->setStatus(BookingStatus::from($bookingDTO->getStatus()));

        $this->entityManager->flush();
        return $booking;
    }

    public function deleteBooking(int $id): void
    {
        $booking = $this->getBookingById($id);
        if ($booking) {
            $this->entityManager->remove($booking);
            $this->entityManager->flush();
        }
    }
}

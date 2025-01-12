<?php

namespace App\Service;

use App\DTO\Request\BookingDetail\CreateBookingDetailDTO;
use App\DTO\Request\BookingDetail\UpdateBookingDetailDTO;
use App\Entity\Booking;
use App\Entity\BookingDetail;
use Doctrine\ORM\EntityManagerInterface;

class BookingDetailService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createBookingDetail(CreateBookingDetailDTO $bookingDetailDTO): BookingDetail
    {
        $bookingDetail = new BookingDetail();
        
        $booking = $this->entityManager->getRepository(Booking::class)->find($bookingDetailDTO->getBookingId());
        $bookingDetail->setBooking($booking);
        $bookingDetail->setFlight($bookingDetailDTO->getFlightId());
        $bookingDetail->setHotel($bookingDetailDTO->getHotelId());
        $bookingDetail->setActivity($bookingDetailDTO->getActivityId());
        $bookingDetail->setCombo($bookingDetailDTO->getComboId());
        $bookingDetail->setQuantity($bookingDetailDTO->getQuantity());
        $bookingDetail->setCheckInDate($bookingDetailDTO->getCheckInDate());
        $bookingDetail->setCheckOutDate($bookingDetailDTO->getCheckOutDate());       

        $this->entityManager->persist($bookingDetail);
        $this->entityManager->flush();
        return $bookingDetail;
    }

    public function getBookingDetailById(int $id): ?BookingDetail
    {
        return $this->entityManager->getRepository(BookingDetail::class)->find($id);
    }

    public function getBookingDetailByBookingId(int $bookingId): ?BookingDetail
    {
        return $this->entityManager->getRepository(BookingDetail::class)->findOneBy(['booking' => $bookingId]);
    }

    public function getAllBookingDetails(): array
    {
        return $this->entityManager->getRepository(BookingDetail::class)->findAll();
    }

    public function updateBookingDetail(int $id, UpdateBookingDetailDTO $bookingDetailDTO): BookingDetail
    {
        $bookingDetail = $this->getBookingDetailById($id);
        if (!$bookingDetail) {
            throw new \Exception("BookingDetail not found");
        }
        $bookingDetail->setQuantity($bookingDetailDTO->getQuantity());
        $bookingDetail->setCheckInDate($bookingDetailDTO->getCheckInDate());
        $bookingDetail->setCheckOutDate($bookingDetailDTO->getCheckOutDate());

        $this->entityManager->flush();
        return $bookingDetail;
    }

    public function deleteBookingDetail(int $id): void
    {
        $bookingDetail = $this->getBookingDetailById($id);
        if ($bookingDetail) {
            $this->entityManager->remove($bookingDetail);
            $this->entityManager->flush();
        }
    }
}

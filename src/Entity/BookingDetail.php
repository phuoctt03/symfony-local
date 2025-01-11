<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 // Kích hoạt API Platform cho Entity này
#[ORM\Entity]
#[ORM\Table(name: "bookingdetail")]
class BookingDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Booking::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Booking $booking;

    #[ORM\ManyToOne(targetEntity: Flight::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?Flight $flight = null;

    #[ORM\ManyToOne(targetEntity: Hotel::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(targetEntity: Activity::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(targetEntity: Combo::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?Combo $combo = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTimeInterface $checkInDate = null;
    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTimeInterface $checkOutDate = null;

    // Getter methods
    public function getBookingDetailId(): int
    {
        return $this->id;
    }

    public function getBooking(): Booking
    {
        return $this->booking;
    }

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function getCombo(): ?Combo
    {
        return $this->combo;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCheckInDate(): ?\DateTime
    {
        return $this->checkInDate;
    }

    public function getCheckOutDate(): ?\DateTime
    {
        return $this->checkOutDate;
    }

    // Setter methods
    public function setBooking(Booking $booking): void
    {
        $this->booking = $booking;
    }

    public function setFlight(?Flight $flight): void
    {
        $this->flight = $flight;
    }

    public function setHotel(?Hotel $hotel): void
    {
        $this->hotel = $hotel;
    }

    public function setActivity(?Activity $activity): void
    {
        $this->activity = $activity;
    }

    public function setCombo(?Combo $combo): void
    {
        $this->combo = $combo;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setCheckInDate(?\DateTimeInterface $checkInDate): void
    {
        $this->checkInDate = $checkInDate;
    }

    public function setCheckOutDate(?\DateTimeInterface $checkOutDate): void
    {
        $this->checkOutDate = $checkOutDate;
    }
}

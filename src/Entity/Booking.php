<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\BookingStatus;
#[ORM\Entity]
#[ORM\Table(name: "booking")]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Promo::class)]
    #[ORM\JoinColumn(name: "Promo_ID", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Promo $promo = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $bookingDate;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $totalPrice;

    #[ORM\Column(type: 'string', enumType: BookingStatus::class)]
    private BookingStatus $status;

    // Getter methods
    public function getBookingId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function getBookingDate(): \DateTime
    {
        return $this->bookingDate;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getStatus(): BookingStatus
    {
        return $this->status;
    }

    // Setter methods
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setPromo(?Promo $promo): void
    {
        $this->promo = $promo;
    }

    public function setBookingDate(\DateTime $bookingDate): void
    {
        $this->bookingDate = $bookingDate;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function setStatus(BookingStatus $status): void
    {
        $this->status = $status;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentMethod;
use ApiPlatform\Metadata\ApiResource;
 // Kích hoạt API Platform cho Entity này
#[ORM\Entity]
#[ORM\Table(name: "payment")]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Booking::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Booking $booking;

    #[ORM\Column(type: 'string', enumType: PaymentMethod::class)]
    private PaymentMethod $paymentMethod;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $paymentDate;

    // Getter methods
    public function getPaymentId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBooking(): Booking
    {
        return $this->booking;
    }

    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function getPaymentDate(): \DateTime
    {
        return $this->paymentDate;
    }

    // Setter methods
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setBooking(Booking $booking): void
    {
        $this->booking = $booking;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function setPaymentDate(\DateTime $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
    }
}

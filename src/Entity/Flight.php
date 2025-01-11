<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 // Kích hoạt API Platform cho Entity này
#[ORM\Entity]
#[ORM\Table(name: "flight")]
class Flight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $brand;

    #[ORM\Column(type: "string")]
    private string $imgUrl;

    #[ORM\Column(type: 'integer')]
    private int $emptySlot;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $startTime;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $endTime;

    #[ORM\Column(type: 'string', length: 100)]
    private string $startLocation;

    #[ORM\Column(type: 'string', length: 100)]
    private string $endLocation;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    // Getter methods
    public function getFlightId(): int
    {
        return $this->id;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function getEmptySlot(): int
    {
        return $this->emptySlot;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    public function getStartLocation(): string
    {
        return $this->startLocation;
    }

    public function getEndLocation(): string
    {
        return $this->endLocation;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    // Setter methods
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setEmptySlot(int $emptySlot): void
    {
        $this->emptySlot = $emptySlot;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function setStartTime(\DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function setEndTime(\DateTime $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function setStartLocation(string $startLocation): void
    {
        $this->startLocation = $startLocation;
    }

    public function setEndLocation(string $endLocation): void
    {
        $this->endLocation = $endLocation;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

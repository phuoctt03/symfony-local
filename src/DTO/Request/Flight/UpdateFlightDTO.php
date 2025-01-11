<?php

namespace App\DTO\Request\Flight;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateFlightDTO
{
    public ?string $brand = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $emptySlot = null;

    #[Assert\GreaterThan('now')]
    public ?\DateTimeInterface $startTime = null;

    #[Assert\GreaterThan(propertyPath: 'startTime')]
    public ?\DateTimeInterface $endTime = null;

    public ?string $startLocation = null;

    public ?string $endLocation = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?float $price = null;

    public function __construct(?string $brand, ?int $emptySlot, ?\DateTimeInterface $startTime, ?\DateTimeInterface $endTime, ?string $startLocation, ?string $endLocation, ?float $price)
    {
        $this->brand = $brand;
        $this->emptySlot = $emptySlot;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->startLocation = $startLocation;
        $this->endLocation = $endLocation;
        $this->price = $price;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getEmptySlot(): ?int
    {
        return $this->emptySlot;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function getStartLocation(): ?string
    {
        return $this->startLocation;
    }

    public function getEndLocation(): ?string
    {
        return $this->endLocation;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function setEmptySlot(?int $emptySlot): void
    {
        $this->emptySlot = $emptySlot;
    }

    public function setStartTime(?\DateTimeInterface $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function setStartLocation(?string $startLocation): void
    {
        $this->startLocation = $startLocation;
    }

    public function setEndLocation(?string $endLocation): void
    {
        $this->endLocation = $endLocation;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}

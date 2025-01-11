<?php

namespace App\DTO\Request\Hotel;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateHotelDTO
{
    public ?string $name = null;

    public ?string $location = null;

    #[Assert\Length(max: 15)]
    public ?string $phone = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $emptyRoom = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?float $price = null;

    public ?string $description = null;

    public function __construct(?string $name, ?string $location, ?string $phone, ?int $emptyRoom, ?float $price, ?string $description)
    {
        $this->name = $name;
        $this->location = $location;
        $this->phone = $phone;
        $this->emptyRoom = $emptyRoom;
        $this->price = $price;
        $this->description = $description;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmptyRoom(): ?int
    {
        return $this->emptyRoom;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setEmptyRoom(?int $emptyRoom): void
    {
        $this->emptyRoom = $emptyRoom;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}

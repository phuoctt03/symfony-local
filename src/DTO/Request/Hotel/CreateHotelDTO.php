<?php

namespace App\DTO\Request\Hotel;

use Symfony\Component\Validator\Constraints as Assert;

class CreateHotelDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $name;
    public string $imgUrl;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $location;

    #[Assert\Length(max: 15)]
    public ?string $phone = null;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public int $emptyRoom;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public float $price;

    public ?string $description = null;

    public function __construct(string $name, string $imgUrl, string $location, ?string $phone, int $emptyRoom, float $price, ?string $description)
    {
        $this->name = $name;
        $this->imgUrl = $imgUrl;
        $this->location = $location;
        $this->phone = $phone;
        $this->emptyRoom = $emptyRoom;
        $this->price = $price;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmptyRoom(): int
    {
        return $this->emptyRoom;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setEmptyRoom(int $emptyRoom): void
    {
        $this->emptyRoom = $emptyRoom;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'imgUrl' => $this->imgUrl,
            'location' => $this->location,
            'phone' => $this->phone,
            'emptyRoom' => $this->emptyRoom,
            'price' => $this->price,
            'description' => $this->description,
        ];
    }
}

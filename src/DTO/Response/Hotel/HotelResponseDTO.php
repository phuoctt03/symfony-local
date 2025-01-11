<?php

namespace App\DTO\Response\Hotel;

use App\Entity\Hotel;

class HotelResponseDTO
{
    public int $id;
    public string $name;
    public string $imgUrl;
    public string $location;
    public ?string $phone;
    public int $emptyRoom;
    public float $price;
    public ?string $description;

    // Constructor to initialize DTO from a Hotel entity
    public function __construct(Hotel $hotel)
    {
        $this->id = $hotel->getHotelId(); // assuming getHotelId() exists
        $this->name = $hotel->getName(); // assuming getName() exists
        $this->imgUrl = $hotel->getImgUrl(); // assuming getImgUrl() exists
        $this->location = $hotel->getLocation(); // assuming getLocation() exists
        $this->phone = $hotel->getPhone(); // assuming getPhone() exists
        $this->emptyRoom = $hotel->getEmptyRoom(); // assuming getEmptyRoom() exists
        $this->price = $hotel->getPrice(); // assuming getPrice() exists
        $this->description = $hotel->getDescription(); // assuming getDescription() exists
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
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

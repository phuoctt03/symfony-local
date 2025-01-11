<?php

namespace App\DTO\Response\Flight;

use App\Entity\Flight;

class FlightResponseDTO
{
    public int $id;
    public string $brand;
    public string $imgUrl;
    public int $emptySlot;
    public \DateTimeInterface $startTime;
    public \DateTimeInterface $endTime;
    public string $startLocation;
    public string $endLocation;
    public float $price;

    // Constructor to initialize DTO from a Flight entity
    public function __construct(Flight $flight)
    {
        $this->id = $flight->getFlightId(); // assuming getFlightId() exists
        $this->brand = $flight->getBrand(); // assuming getBrand() exists
        $this->imgUrl = $flight->getImgUrl(); // assuming getImgUrl() exists
        $this->emptySlot = $flight->getEmptySlot(); // assuming getEmptySlot() exists
        $this->startTime = $flight->getStartTime(); // assuming getStartTime() exists
        $this->endTime = $flight->getEndTime(); // assuming getEndTime() exists
        $this->startLocation = $flight->getStartLocation(); // assuming getStartLocation() exists
        $this->endLocation = $flight->getEndLocation(); // assuming getEndLocation() exists
        $this->price = $flight->getPrice(); // assuming getPrice() exists
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'imgUrl' => $this->imgUrl,
            'emptySlot' => $this->emptySlot,
            'startTime' => $this->startTime->format('Y-m-d H:i:s'), // Format as needed
            'endTime' => $this->endTime->format('Y-m-d H:i:s'), // Format as needed
            'startLocation' => $this->startLocation,
            'endLocation' => $this->endLocation,
            'price' => $this->price,
        ];
    }
}

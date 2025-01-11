<?php

namespace App\Service;

use App\DTO\Request\Hotel\CreateHotelDTO;
use App\DTO\Request\Hotel\UpdateHotelDTO;
use App\Entity\Hotel;
use Doctrine\ORM\EntityManagerInterface;

class HotelService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createHotel(CreateHotelDTO $hotelDTO): Hotel
    {
        $hotel = new Hotel();
        $hotel->setName($hotelDTO->getName());
        $hotel->setImgUrl($hotelDTO->getImgUrl());
        $hotel->setLocation($hotelDTO->getLocation());
        $hotel->setPhone($hotelDTO->getPhone());
        $hotel->setEmptyRoom($hotelDTO->getEmptyRoom());
        $hotel->setPrice($hotelDTO->getPrice());
        $hotel->setDescription($hotelDTO->getDescription());
        // Set other properties from $hotelDTO to $hotel as needed

        $this->entityManager->persist($hotel);
        $this->entityManager->flush();
        return $hotel;
    }

    public function getAllHotels(): array
    {
        return $this->entityManager->getRepository(Hotel::class)->findAll();
    }

    public function getHotelById(int $id): ?Hotel
    {
        return $this->entityManager->getRepository(Hotel::class)->find($id);
    }

    public function decreaseEmptyRoom(int $id): void
    {
        $hotel = $this->getHotelById($id);
        if ($hotel) {
            $hotel->setEmptyRoom($hotel->getEmptyRoom() - 1);
            $this->entityManager->flush();
        }
    }
    public function updateHotel(int $id, UpdateHotelDTO $hotelDTO): Hotel
    {
        $hotel = $this->getHotelById($id);
        if (!$hotel) {
            throw new \Exception("Hotel not found");
        }

        $hotel->setName($hotelDTO->getName());
        $hotel->setLocation($hotelDTO->getLocation());
        $hotel->setPhone($hotelDTO->getPhone());
        $hotel->setEmptyRoom($hotelDTO->getEmptyRoom());
        $hotel->setPrice($hotelDTO->getPrice());
        $hotel->setDescription($hotelDTO->getDescription());
        // Update other properties from $hotelDTO to $hotel as needed

        $this->entityManager->flush();
        return $hotel;
    }

    public function deleteHotel(int $id): void
    {
        $hotel = $this->getHotelById($id);
        if ($hotel) {
            $this->entityManager->remove($hotel);
            $this->entityManager->flush();
        }
    }
}

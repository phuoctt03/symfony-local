<?php

namespace App\Service;

use App\DTO\Request\ComboDetail\CreateComboDetailDTO;
use App\DTO\Request\ComboDetail\UpdateComboDetailDTO;
use App\Entity\Combo;
use App\Entity\ComboDetail;
use Doctrine\ORM\EntityManagerInterface;

class ComboDetailService
{
    public function __construct(private EntityManagerInterface $entityManager, 
    private FlightService $flightService,
    private HotelService $hotelService,
    private ActivityService $activityService
    ) {}

    public function getComboDetailByComboId(int $comboId): ?ComboDetail
    {
        return $this->entityManager->getRepository(ComboDetail::class)->findOneBy(['combo' => $comboId]);
    }

    public function createComboDetail(CreateComboDetailDTO $comboDetailDTO): ComboDetail
    {
        $comboDetail = new ComboDetail();
        // Assuming ComboDetail has setters for each property in CreateComboDetailDTO
        $combo = $this->entityManager->getRepository(Combo::class)->find($comboDetailDTO->getComboId());
        $comboDetail->setCombo($combo);
        $comboDetail->setFlight($comboDetailDTO->getFlightId());
        $comboDetail->setHotel($comboDetailDTO->getHotelId());
        $comboDetail->setActivity($comboDetailDTO->getActivityId());
        $comboDetail->setCheckInDate($comboDetailDTO->getCheckInDate());
        $comboDetail->setCheckOutDate($comboDetailDTO->getCheckOutDate());
        // Add other properties as needed

        $this->entityManager->persist($comboDetail);
        $this->entityManager->flush();
        return $comboDetail;
    }

    public function getAllComboDetails(): array
    {
        return $this->entityManager->getRepository(ComboDetail::class)->findAll();
    }

    public function getComboDetailById(int $id): ?ComboDetail
    {
        return $this->entityManager->getRepository(ComboDetail::class)->find($id);
    }

    public function updateComboDetail(int $id, UpdateComboDetailDTO $comboDetailDTO): ComboDetail
    {
        $comboDetail = $this->getComboDetailById($id);
        if (!$comboDetail) {
            throw new \Exception('ComboDetail not found');
        }
        $flight = $this->flightService->getFlightById($comboDetailDTO->getFlightId());
        $hotel = $this->hotelService->getHotelById($comboDetailDTO->getHotelId());
        $activity = $this->activityService->getActivityById($comboDetailDTO->getActivityId());

        // Assuming ComboDetail has setters for each property in UpdateComboDetailDTO
        $comboDetail->setFlight($flight);
        $comboDetail->setHotel($hotel);
        $comboDetail->setActivity($activity);
        $comboDetail->setCheckInDate($comboDetailDTO->getCheckInDate());
        $comboDetail->setCheckOutDate($comboDetailDTO->getCheckOutDate());
        // Add other properties as needed

        $this->entityManager->flush();
        return $comboDetail;
    }

    public function deleteComboDetail(int $id): void
    {
        $comboDetail = $this->getComboDetailById($id);
        if ($comboDetail) {
            $this->entityManager->remove($comboDetail);
            $this->entityManager->flush();
        }
    }
}

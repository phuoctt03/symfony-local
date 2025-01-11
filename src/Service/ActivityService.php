<?php

namespace App\Service;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\Request\Activity\CreateActivityDTO;
use App\DTO\Request\Activity\UpdateActivityDTO;
class ActivityService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createActivity(CreateActivityDTO $activityDTO): Activity
    {
        $activity = new Activity();
        $activity->setName($activityDTO->getName());
        $activity->setImgUrl($activityDTO->getImgUrl());
        $activity->setDescription($activityDTO->getDescription());
        $activity->setEmptySlot($activityDTO->getEmptySlot());
        $activity->setLocation($activityDTO->getLocation());
        $activity->setPrice($activityDTO->getPrice());

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
        return $activity;
    }

    public function getAllActivities(): array
    {
        return $this->entityManager->getRepository(Activity::class)->findAll();
    }

    public function getActivityById(int $id): ?Activity
    {
        return $this->entityManager->getRepository(Activity::class)->find($id);
    }

    public function decreaseEmptySlot(int $id): void
    {
        $activity = $this->getActivityById($id);
        if ($activity) {
            $activity->setEmptySlot($activity->getEmptySlot() - 1);
            $this->entityManager->flush();
        }
    }

    public function updateActivity(int $id, UpdateActivityDTO $activityDTO): ?Activity
    {
        $activity = $this->getActivityById($id);
        if ($activity) {
            $activity->setName($activityDTO->getName());
            $activity->setDescription($activityDTO->getDescription());
            $activity->setEmptySlot($activityDTO->getEmptySlot());
            $activity->setLocation($activityDTO->getLocation());
            $activity->setPrice($activityDTO->getPrice());

            $this->entityManager->flush();
        }
        return $activity;
    }

    public function deleteActivity(int $id): void
    {
        $activity = $this->getActivityById($id);
        if ($activity) {
            $this->entityManager->remove($activity);
            $this->entityManager->flush();
        }
    }
}

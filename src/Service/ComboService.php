<?php

namespace App\Service;

use App\DTO\Request\Combo\CreateComboDTO;
use App\DTO\Request\Combo\UpdateComboDTO;
use App\Entity\Combo;
use Doctrine\ORM\EntityManagerInterface;

class ComboService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createCombo(CreateComboDTO $comboDTO): Combo
    {
        $combo = new Combo();
        // Assuming Combo entity has setName and setDescription methods
        $combo->setName($comboDTO->getName());
        $combo->setImgUrl($comboDTO->getImgUrl());
        $combo->setDescription($comboDTO->getDescription());
        $combo->setPrice($comboDTO->getPrice());
        
        $this->entityManager->persist($combo);
        $this->entityManager->flush();
        return $combo;
    }

    public function getAllCombos(): array
    {
        return $this->entityManager->getRepository(Combo::class)->findAll();
    }

    public function getComboById(int $id): ?Combo
    {
        return $this->entityManager->getRepository(Combo::class)->find($id);
    }

    public function updateCombo(int $id, UpdateComboDTO $comboDTO): Combo
    {
        $combo = $this->getComboById($id);
        if (!$combo) {
            throw new \Exception("Combo not found");
        }
        
        $combo->setName($comboDTO->getName());
        $combo->setDescription($comboDTO->getDescription());
        $combo->setPrice($comboDTO->getPrice());
        
        $this->entityManager->flush();
        return $combo;
    }

    public function deleteCombo(int $id): void
    {
        $combo = $this->getComboById($id);
        if ($combo) {
            $this->entityManager->remove($combo);
            $this->entityManager->flush();
        }
    }
}

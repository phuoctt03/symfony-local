<?php

namespace App\Service;

use App\DTO\Request\Promo\CreatePromoDTO;
use App\DTO\Request\Promo\UpdatePromoDTO;
use App\Entity\Promo;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\PromoCondition;
class PromoService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createPromo(CreatePromoDTO $promoDTO): Promo
    {
        $promo = new Promo();
        $date = new \DateTime();
        $createdDate = \DateTime::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        // Assuming CreatePromoDTO has methods to get the necessary data
        $promo->setName($promoDTO->getName());
        $promo->setImgUrl($promoDTO->getImgUrl());
        $promo->setDescription($promoDTO->getDescription());
        $promo->setDiscount($promoDTO->getDiscount());
        $promo->setCreatedDate($createdDate);
        $promo->setExpiredDate($promoDTO->getExpiredDate());
        $promo->setAmount($promoDTO->getAmount());
        $promo->setConditions(PromoCondition::from($promoDTO->getConditions()));
        // Add other necessary fields here
        $this->entityManager->persist($promo);
        $this->entityManager->flush();
        return $promo;
    }

    public function getAllPromosForSilver(): array
    {   $conditions = PromoCondition::SILVER;
        $silver = $this->entityManager->getRepository(Promo::class)->findBy(['conditions' => $conditions]);
        $public = $this->getAllPromosForUser();
        return array_merge($silver, $public);
    }

    public function getAllPromosForUser(): array
    {
        return $this->entityManager->getRepository(Promo::class)->findBy(['conditions' => PromoCondition::PUBLIC]);
    }

    public function getAllPromos(): array
    {
        return $this->entityManager->getRepository(Promo::class)->findAll();
    }

    public function getPromoById(int $id): ?Promo
    {
        return $this->entityManager->getRepository(Promo::class)->find($id);
    }

    public function decreaseAmount(int $id): void
    {
        $promo = $this->getPromoById($id);
        if ($promo) {
            $promo->setAmount($promo->getAmount() - 1);
            $this->entityManager->flush();
        }
    }

    public function updatePromo(int $id, UpdatePromoDTO $promoDTO): Promo
    {
        $promo = $this->getPromoById($id);
        if (!$promo) {
            throw new \Exception("Promo not found");
        }

        // Assuming UpdatePromoDTO has methods to get the necessary data
        $promo->setName($promoDTO->getName());
        $promo->setDescription($promoDTO->getDescription());
        $promo->setDiscount($promoDTO->getDiscount());
        $promo->setExpiredDate($promoDTO->getExpiredDate());
        $promo->setAmount($promoDTO->getAmount());
        $promo->setConditions(PromoCondition::from($promoDTO->getConditions()));
        // Add other necessary fields here

        $this->entityManager->flush();
        return $promo;
    }

    public function deletePromo(int $id): void
    {
        $promo = $this->getPromoById($id);
        if ($promo) {
            $this->entityManager->remove($promo);
            $this->entityManager->flush();
        }
    }
}

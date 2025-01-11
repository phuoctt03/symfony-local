<?php

namespace App\DTO\Response\Promo;

use App\Entity\Promo;

class PromoResponseDTO
{
    public int $id;
    public string $name;
    public string $imgUrl;
    public ?string $description;
    public float $discount;
    public \DateTimeInterface $createdDate;
    public \DateTimeInterface $expiredDate;
    public int $amount;
    public string $conditions;

    // Constructor to initialize DTO from a Promo entity
    public function __construct(Promo $promo)
    {
        $this->id = $promo->getPromoId(); // assuming getPromoId() exists
        $this->name = $promo->getName(); // assuming getName() exists
        $this->imgUrl = $promo->getImgUrl(); // assuming getImgUrl() exists
        $this->description = $promo->getDescription(); // assuming getDescription() exists
        $this->discount = $promo->getDiscount(); // assuming getDiscount() exists
        $this->createdDate = $promo->getCreatedDate(); // assuming getCreatedDate() exists
        $this->expiredDate = $promo->getExpiredDate(); // assuming getExpiredDate() exists
        $this->amount = $promo->getAmount(); // assuming getAmount() exists
        $this->conditions = $promo->getConditions()->value; // assuming getConditions() returns an enum and has a 'value' property
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'imgUrl' => $this->imgUrl,
            'description' => $this->description,
            'discount' => $this->discount,
            'createdDate' => $this->createdDate->format('Y-m-d H:i:s'), // formatting DateTime for JSON
            'expiredDate' => $this->expiredDate->format('Y-m-d H:i:s'), // formatting DateTime for JSON
            'amount' => $this->amount,
            'conditions' => $this->conditions,
        ];
    }
}

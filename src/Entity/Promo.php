<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\PromoCondition;
#[ORM\Entity]
#[ORM\Table(name: "promo")]
class Promo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: "string")]
    private string $imgUrl;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private float $discount;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdDate;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $expiredDate;

    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'string', enumType: PromoCondition::class)]
    private PromoCondition $conditions;

    // Getter methods
    public function getPromoId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    public function getExpiredDate(): \DateTime
    {
        return $this->expiredDate;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getConditions(): PromoCondition
    {
        return $this->conditions;
    }

    // Setter methods
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    public function setExpiredDate(\DateTime $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setConditions(PromoCondition $conditions): void
    {
        $this->conditions = $conditions;
    }
}

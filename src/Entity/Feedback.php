<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\RatedType;
use ApiPlatform\Metadata\ApiResource;
 // Kích hoạt API Platform cho Entity này
#[ORM\Entity]
#[ORM\Table(name: "feedback")]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: 'string', enumType: RatedType::class)]
    private RatedType $ratedType;

    #[ORM\Column(type: 'integer')]
    private int $relatedId;

    #[ORM\Column(type: 'integer')]
    private int $rating;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdDate;

    // Getter methods
    public function getFeedbackId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRatedType(): RatedType
    {
        return $this->ratedType;
    }

    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    // Setter methods
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setRatedType(RatedType $ratedType): void
    {
        $this->ratedType = $ratedType;
    }

    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}

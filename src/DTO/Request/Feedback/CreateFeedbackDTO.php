<?php

namespace App\DTO\Request\Feedback;

use Symfony\Component\Validator\Constraints as Assert;

class CreateFeedbackDTO
{
    #[Assert\NotBlank]
    public int $userId;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['Flight', 'Hotel', 'Activity', 'Combo'])]
    public string $ratedType;

    #[Assert\NotBlank]
    public int $relatedId;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 5)]
    public int $rating;

    public ?string $comment = null;

    public function __construct(int $userId, string $ratedType, int $relatedId, int $rating, ?string $comment)
    {
        $this->userId = $userId;
        $this->ratedType = $ratedType;
        $this->relatedId = $relatedId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRatedType(): string
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

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setRatedType(string $ratedType): void
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
}

<?php

namespace App\DTO\Request\Feedback;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateFeedbackDTO
{
    #[Assert\Choice(choices: ['Flight', 'Hotel', 'Activity', 'Combo'], message: 'Invalid rated type')]
    public ?string $ratedType = null;

    public ?int $relatedId = null;

    #[Assert\Range(min: 0, max: 5)]
    public ?int $rating = null;

    public ?string $comment = null;

    public function __construct(?string $ratedType, ?int $relatedId, ?int $rating, ?string $comment)
    {
        $this->ratedType = $ratedType;
        $this->relatedId = $relatedId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getRatedType(): ?string
    {
        return $this->ratedType;
    }

    public function getRelatedId(): ?int
    {
        return $this->relatedId;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setRatedType(?string $ratedType): void
    {
        $this->ratedType = $ratedType;
    }

    public function setRelatedId(?int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}

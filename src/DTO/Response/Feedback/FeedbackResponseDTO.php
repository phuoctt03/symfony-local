<?php

namespace App\DTO\Response\Feedback;

use App\Entity\Feedback;

class FeedbackResponseDTO
{
    public int $id;
    public int $userId;
    public string $ratedType;
    public int $relatedId;
    public int $rating;
    public ?string $comment;
    public \DateTimeInterface $createdDate;

    // Constructor to initialize DTO from a Feedback entity
    public function __construct(Feedback $feedback)
    {
        $this->id = $feedback->getFeedbackId(); // assuming getFeedbackId() exists
        $this->userId = $feedback->getUser()->getId(); // assuming getUser() returns a User entity
        $this->ratedType = $feedback->getRatedType()->value; // assuming RatedType is an enum
        $this->relatedId = $feedback->getRelatedId(); // assuming getRelatedId() exists
        $this->rating = $feedback->getRating(); // assuming getRating() exists
        $this->comment = $feedback->getComment(); // assuming getComment() exists
        $this->createdDate = $feedback->getCreatedDate(); // assuming getCreatedDate() exists
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'ratedType' => $this->ratedType,
            'relatedId' => $this->relatedId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'createdDate' => $this->createdDate->format('Y-m-d H:i:s'), // Format as needed
        ];
    }
}

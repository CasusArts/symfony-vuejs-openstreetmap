<?php

namespace App\Messenger\Event;

use App\Entity\Review;

class ReviewUpdatedEvent
{
    private $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function getReview(): Review
    {
        return $this->review;
    }
}
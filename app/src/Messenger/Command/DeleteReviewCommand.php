<?php

namespace App\Messenger\Command;

use App\Entity\Review;

class DeleteReviewCommand
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
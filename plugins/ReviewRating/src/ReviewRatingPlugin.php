<?php

namespace Plugins\ReviewRating;

use App\Core\Plugin\Plugin;

class ReviewRatingPlugin extends Plugin
{
    public function boot(): void
    {
        $this->addFilter('product.show.extra_info', [$this, 'showReviews']);
    }

    public function showReviews($product)
    {
        $reviews = $product->reviews()->latest()->paginate(5);
        return view('review-rating::reviews', ['reviews' => $reviews]);
    }
}

@forelse ($book->reviews as $review)
    <li class="book-item mb-4">
        <div>
            <div class="mb-2 flex items-center justify-between">
                <div class="font-medium text-lg">
                    {{ $review->rating }}
                    <x-star-rating :rating="$review->rating" />
                </div>
                <div class="book-review-count">
                    {{ $review->created_at->format('M j, Y') }}</div>
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>
        </div>
    </li>
@empty
    <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
        </div>
    </li>
@endforelse

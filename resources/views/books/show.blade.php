@extends('layouts.app')

@section('page-title')
    {{ $book->title }}
@endsection

@section('content')
    <div class="mb-4">
        <h1 class="top-0 mb-2 text-2xl">{{ $book->title }}</h1>

        <div class="book-info">
            <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
            <div class="book-rating flex items-center ">
                <div class="mr-2 text-lg font-medium text-slate-700">
                    {{ number_format($book->reviews_avg_rating, 1) }}
                    <x-star-rating :rating="$book->reviews_avg_rating" />
                </div>
                <span class="book-review-count text-sm text-gray-500 ">
                    {{ $book->reviews_count }} {{ Str::plural('review', 5) }}
                </span>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('books.reviews.create', $book) }}" class="reset-link">
            Add a review</a>
    </div>

    <div>
        <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
        <ul id="review-list">
            @include('books.reviews.reviews-list')
        </ul>
        <div id="loading" class="hidden text-center py-4">Loading...</div>
        @include('scripts.infinite-loading-reviews')
    </div>
@endsection

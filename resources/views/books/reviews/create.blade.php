@extends('layouts.app')

@section('page-title')
    Post a review
@endsection

@section('content')
    <h1 class="mb-10 text-2xl">Write a review for: {{ $book->title }}</h1>
    <form method="POST" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <div>
            <label for="review" class="review-label">Review</label>
            <textarea name="review" id="review" required class="input mb-4"></textarea>
        </div>
        <div>
            <label for="rating" class="review-label">Rating</label>
            <div>
                <select name="rating" id="rating" class="filter-container" required>
                    <option value="" class="filter-item">Select a rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" class="filter-item">{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn float-right">Post review</button>
            </div>
        </div>
    </form>
@endsection

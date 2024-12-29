@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    <form method="GET" action="{{ route('books.index') }}" class="flex-wrap">
        <div class="mb-4 flex items-center space-x-2 w-full">

            <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}"
                class="input h-10" />
            <button type="submit" class="btn h-10">Search</button>
            <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
        </div>

        <div class="mb-4 flex items-center space-x-2 w-full">
            <select name="filter" id="filter" class="filter-container">
                @foreach ($filters as $key => $value)
                    @if ($key == $filter_selected)
                        <option selected='selected' value="{{ $key }}" class="filter-item">{{ $value }}
                        </option>
                    @else
                        <option value="{{ $key }}" class="filter-item">{{ $value }}</option>
                    @endif
                @endforeach
            </select>
            <div id="date-range-picker" class="flex items-center date-picker-container">
                <span class="mx-4 text-gray-500">From</span>
                <div class="relative max-w-sm">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-start" datepicker name="date_start" type="text"
                        class="date-picker" placeholder="Select date start" value="{{ request('date_start') }}">
                </div>
                <span class="mx-4 text-gray-500">to</span>
                <div class="relative max-w-sm">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-end" datepicker name="date_end" type="text"
                        class="date-picker" placeholder="Now" value="{{ request('date_end') }}">
                </div>
            </div>
        </div>
    </form>

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ number_format((float) $book->reviews_avg_rating, 2, '.', '') }}
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection

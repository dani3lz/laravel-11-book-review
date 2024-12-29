<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Enums\Filters;
use App\Enums\Rating;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function getFilters()
    {
        return [
            Filters::LATEST->value => 'Latest',
            Filters::POPULAR->value => 'Popular',
            Filters::HIGHEST->value => 'With highest review',
            Filters::LOWEST->value => 'With lowest review'
        ];
    }

    public function index(Request $request)
    {
        $filters = $this->getFilters();
        $title = $request->input('title');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title)
        );

        $books = match ($request->get('filter')) {
            Filters::POPULAR->value => $books->popular($date_start, $date_end),
            Filters::HIGHEST->value => $books->byRating(Rating::GOOD, $date_start, $date_end),
            Filters::LOWEST->value => $books->byRating(Rating::BAD, $date_start, $date_end),
            default => $books->orderLatest($date_start, $date_end)
        };
        $books = $books->paginate(15);

        if ($request->ajax()) {
            if ($books->isEmpty()) {
                return '';
            }
            return view('books.books-list', compact('books'))->render();
        }

        return view('books.index', compact('books', 'filters'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $book = Book::with([
            'reviews' => fn($query) => $query->latest()->paginate(10)
        ])->fetchBooks()->findOrFail($id);

        if ($request->ajax()) {
            if ($book->reviews->isEmpty()) {
                return '';
            }
            return view('books.reviews.reviews-list', compact('book'))->render();
        }    

        return view('books.show', compact('book'));
    }
}

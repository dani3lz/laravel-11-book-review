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

        $filter_selected = $request->get('filter');

        $books = match ($filter_selected) {
            Filters::POPULAR->value => $books->popular($date_start, $date_end),
            Filters::HIGHEST->value => $books->byRating(Rating::GOOD, $date_start, $date_end),
            Filters::LOWEST->value => $books->byRating(Rating::BAD, $date_start, $date_end),
            default => $books->orderLatest($date_start, $date_end)
        };
        $books = $books->get();

        return view('books.index', [
            'books' => $books,
            'filters' => $filters,
            'filter_selected' => $filter_selected
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', [ 'book' => $book->load([
            'reviews' => fn($query) => $query->latest()
            //'reviews_count' => fn($query) => $query->withCount('title')
        ])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index');
    }
}

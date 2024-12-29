<?php

namespace App\Models;

use App\Enums\Rating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $querry, string $title): Builder
    {
        return $querry->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeOrderLatest(Builder $querry, $from = null, $to = null): Builder
    {
        return $querry
            ->fetchBooks($from, $to)
            ->orderBy('created_at', 'desc');
    }

    public function scopePopular(Builder $querry, $from = null, $to = null): Builder
    {
        return $querry
            ->fetchBooks($from, $to)
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeByRating(Builder $querry, Rating $option, $from = null, $to = null): Builder
    {
        return $querry
            ->fetchBooks($from, $to)
            ->orderBy('reviews_avg_rating', $option->value);
    }

    public function scopeMinReviews(Builder $query, $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    public function scopeWithRangeDate(Builder $querry, $from = null, $to = null)
    {
        return $querry->where(fn(Builder $q) => $this->dateRangeFilter($q, $from, $to));
    }

    public function scopeFetchBooks(Builder $querry, $from = null, $to = null)
    {
        return $querry
            ->withRangeDate($from, $to)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');
    }

    private function dateRangeFilter(Builder $q, $from = null, $to = null)
    {
        if ($from && !$to) {
            $from = date('Y-m-d', strtotime($from));
            $q->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $to = date('Y-m-d', strtotime($to));
            $q->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $from = date('Y-m-d', strtotime($from));
            $to = date('Y-m-d', strtotime($to));
            $q->whereBetween('created_at', [$from, $to]);
        }
    }
}

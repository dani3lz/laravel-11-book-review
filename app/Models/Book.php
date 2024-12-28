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

    public function scopePopular(Builder $querry, $from = null, $to = null): Builder
    {
        return $querry
            ->withCount([
                'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            ])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeByRating(Builder $querry, Rating $option, $from = null, $to = null): Builder
    {
        return $querry
            ->withAvg([
                'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            ], 'rating')
            ->orderBy('reviews_avg_rating', $option->value);
    }

    public function scopeMinReviews(Builder $query, $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter(Builder $q, $from = null, $to = null)
    {
        if ($from && !$to) {
            $q->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $q->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $q->whereBetween('created_at', [$from, $to]);
        }
    }
}

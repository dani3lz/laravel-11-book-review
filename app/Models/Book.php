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

    public function scopePopular(Builder $querry): Builder
    {
        return $querry
            ->withCount('reviews')
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeByRating(Builder $querry, Rating $option): Builder
    {
        switch ($option) {
            case Rating::GOOD:
                return $querry
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc');
                break;
            case Rating::BAD:
                return $querry
                    ->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating');
                break;
        }
    }
}

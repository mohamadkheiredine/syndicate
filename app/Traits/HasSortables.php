<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

trait HasSortables
{
    /**
     * Scope a query to sort by translatable fields.
     *
     * @param Builder $query
     * @param string $column
     * @param string $direction
     * @return Builder
     */
    public function scopeSortables(Builder $query, ...$allowedSorts): Builder
    {
        $sortParam = request('sort');
        if ($sortParam) {
            $direction = $sortParam[0] == '-' ? 'desc' : 'asc';
            $column = ltrim($sortParam, '-');

            if (in_array($column, $allowedSorts)) {
                if (in_array($column, $this->sortables)) {
                    $locale = app()->getLocale();
                    // Check if the locale is valid, if not use 'en' as default
                    $locale = 'en';
                    $query->orderByRaw("LOWER({$column}->>'{$locale}') {$direction}");
                } else {
                    $query->orderBy($column, $direction);
                }
            }
        }

        return $query;
    }
}

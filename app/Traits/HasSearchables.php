<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearchables
{
    /**
     * Scope a query to only include results that match the search term.
     *
     * @param Builder $query
     * @param string|null $searchTerm
     * @return Builder
     */
    public function scopeSearchables(Builder $query, ...$allowedSearches): Builder
    {
        $searchTerm = request('search');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm, $allowedSearches) {
                foreach ($allowedSearches as $column) {
                    if (in_array($column, $this->searchables)) {
                        $query->orWhereRaw("LOWER({$column}->>'en') like ?", ['%' . strtolower($searchTerm) . '%']);
                    } else {
                        $query->orWhere($column, 'like', '%' . $searchTerm . '%');
                    }
                }
            });
        }

        return $query;
    }
}



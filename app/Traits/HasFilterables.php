<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

trait HasFilterables
{
    /**
     * Scope a query to only include results that match the filters.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilterables(Builder $query, ...$filters): Builder
    {
        foreach ($filters as $column) {
            $value = request()->input("filter.$column");
            if ($value !== null) {
                $query->where($column, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }
}

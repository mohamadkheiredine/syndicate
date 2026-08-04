<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use DB;

class PaginationHelper
{
    /**
     * Summary of paginateData
     * @param mixed $model
     * @param mixed $columns
     * @param mixed $searchColumns
     * @param mixed $relationalColumns
     * @param mixed $relationalSorts
     * @return mixed $rows
     */
    public static function paginateData($model, $columns, $searchColumns = [], $relationalColumns = [], $relationalSorts = [])
    {
        // default variables values
        $entries_per_page = 10;
        // sort by the first column by default in asc
        $sort = $columns[1];
        $order = 'asc';

        // get attributes from request url
        $entries_per_page = Request::get('entries_per_page', $entries_per_page);
        $sort = Request::get('sort', $sort);
        $order = Request::get('order', $order);

        $query = self::filteredQuery($model, $columns, $searchColumns, $relationalColumns);

        // apply sorting
        // self::applySortOrder($model, $query, $sort, $order, $relationalSorts);

        $query->orderBy($sort, $order);

        // apply pagination and return the data
        return $query->paginate($entries_per_page);
    }

    /**
     * Same search/filter logic as paginateData, without pagination applied —
     * used by exports that need every matching row, not just the current page.
     *
     */
    public static function filteredQuery($model, $columns, $searchColumns = [], $relationalColumns = [])
    {
        $search = Request::get('search');

        // query the model
        $query = $model::select($columns);

        // apply search
        if ($search) {
          self::applySearch($query, $search, $searchColumns, $relationalColumns);
        }

        self::applyFilter($query, $searchColumns, $relationalColumns);

        return $query;
    }


    private static function applySortOrder($model, $query, $sort, $order, $relationalSorts)
    {

        // if $sort is a relationalsort 
        if (array_key_exists($sort, $relationalSorts)) {

        } else {
          // sort by the column of original model
          $query->orderBy($sort, $order);
        }
    }


    /**
     * Summary of applyFilter
     * @param mixed $query
     * @param mixed $searchColumns
     * @param mixed $relationalColumns
     * @return void
     */
    private static function applyFilter($query, $searchColumns, $relationalColumns) {
      // Retrieve the filter values from the request
      $filters = [];
      $relationalFilters = [];

      // generate hashmap of filters
      foreach ($searchColumns as $column) {
          $filters[$column] = Request::get($column);
      }

      // generate hashmap of relational filters
      foreach ($relationalColumns as $relation => $relationSearchColumns) {
          $relationalFilters[$relation] = [];
          foreach ($relationSearchColumns as $relationColumn) {
              $relationalFilters[$relation][$relationColumn] = Request::get($relation);
          }
      }

      // build query on filters and relationalFilters 
      $query->where(function ($q) use ($filters, $relationalFilters) {

        // apply filter to relational columns
        foreach ($relationalFilters as $relation => $relationSearchColumns) {
            $q->orWhereHas($relation, function ($relationQuery) use ($relationSearchColumns) {
                $relationQuery->where(function ($subQuery) use ($relationSearchColumns) {
                    foreach ($relationSearchColumns as $relationColumn => $value) {
                        if ($value) {
                          $subQuery->orWhere($relationColumn, 'like', '%' . $value . '%');
                        }
                    }
                });
            });
          }

        $queries = [];

        // build query on filters
        foreach ($filters as $column => $value) {
            if ($value) {
              array_push($queries, [$column, 'like', '%' . $value . '%']);
            }
        }

        // run where on combined quereies
        $q->where($queries);

      });

    }

    private static function applySearch($query, $search, $searchColumns, $relationalColumns)
    {
      // if search then search in all searchableColumns
      $query->where(function ($q) use ($search, $searchColumns, $relationalColumns) {

        // search in all searchableColumns
        foreach ($searchColumns as $column) {
            $q->orWhere($column, 'like', '%' . $search . '%');
        }

        // search in all relationalColumns
        foreach ($relationalColumns as $relation => $relationSearchColumns) {
            $q->orWhereHas($relation, function ($relationQuery) use ($search, $relationSearchColumns) {
                $relationQuery->where(function ($subQuery) use ($search, $relationSearchColumns) {
                    foreach ($relationSearchColumns as $relationColumn) {
                        $subQuery->orWhere($relationColumn, 'like', '%' . $search . '%');
                    }
                });
            });
          }
      });
    }
}

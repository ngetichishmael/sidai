<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Searchable
{
   public function scopeSearch(Builder $builder, string $term = '')
   {
      foreach ($this->searchable as $searchable) {
         if (str_contains($searchable, '.')) {
            $relation = Str::beforeLast($searchable, '.');
            $column = Str::afterLast($searchable, '.');
            $builder->orWhereRelation($relation, $column, 'like', $term);
            continue;
         }
         $builder->orWhere($searchable, 'like', $term);
      }
      return $builder;
   }
}

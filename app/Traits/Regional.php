<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait Regional
{
   public function scopeRegional(Builder $builder, string $term = '')
   {
      foreach ($this->regional as $region) {
         if (str_contains($region, '.')) {
            $relation = Str::beforeLast($region, '.');
            $column = Str::afterLast($region, '.');
            $builder->orWhereRelation($relation, $column, 'like', $term);
            continue;
         }
         $builder->orWhere($region, 'like', $term);
      }
      return $builder;
   }
}

<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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

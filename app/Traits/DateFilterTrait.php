<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait DateFilterTrait
{
public function scopeWhereBetweenDate(Builder $query, string $column = null, string $start = null, string $end = null): Builder
{
if (is_null($start) && is_null($end)) {
return $query;
}

if (!is_null($start) && Carbon::parse($start)->eq(Carbon::parse($end))) {
return $query->whereDate($column, '=', $start);
}

$end = $end == null ? Carbon::now()->endOfDay()->format('Y-m-d H:i:s') : $end;

return $query->whereBetween($column, [$start, $end]);
}
}

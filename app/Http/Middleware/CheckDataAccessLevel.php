<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDataAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */

   public function handle($request, Closure $next, ...$requiredAccessLevels)
   {
      $user = Auth::user();
      $dataAccessLevel = $user->roles()->pluck('data_access_level')->first();

      if (!in_array($dataAccessLevel, $requiredAccessLevels)) {
//         return response()->json(['message' => 'Access denied.'], 403);
         return redirect()->route('unauthorized');
      }

      return $next($request);

   }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   public function handle($request, Closure $next, ...$roles)
   {
      $user = Auth::user();
      if ($user && in_array($user->account_type, $roles)) {
         $accessTo = $user->access_to;
         $requestedRegion = $request->route('region');
         if ($accessTo === 'admin' || $accessTo === $requestedRegion) {
            return $next($request);
         }
      }
      return redirect()->route('unauthorized');
   }
}

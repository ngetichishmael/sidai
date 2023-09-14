<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckDashboardPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   public function handle($request, Closure $next)
   {
      $requiredPermissions = ['admin_dashboard', 'manager_dashboard', 'shop_attendee_dashboard'];

//      foreach ($requiredPermissions as $permission) {
//         if (Auth::user()->can($permission)) {
//            return $next($request);
//         }
//      }
      $user = Auth()->user();
      $u = User::with('permissions')->find($user->id);
      info($u);
      info($user->permissions->pluck('name'));

      if ($user->hasAnyPermission(...$requiredPermissions)) {
         return $next($request);
      }

      Auth::logout();
       return redirect()->route('login')->with('error', 'You do not have permission to access the dashboard.');

   }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
   protected function attemptLogin(Request $request)
   {
      $credentials = $request->only('email', 'password');

      if (Auth::attempt($credentials, $request->filled('remember'))) {
         $user = Auth::user();
         $requiredPermissions = ['admin_dashboard', 'manager_dashboard', 'shop_attendee_dashboard'];
         info($user);
         foreach ($requiredPermissions as $permission) {
            info($user->hasPermission($permission));
            if ($user->hasPermission($permission)) {
               return redirect()->intended($this->redirectTo);
            }
         }

         // User didn't have any of the required permissions, so log them out.
         Auth::logout();
         return redirect()->route('login')->withErrors(['permissions' => 'Unauthorized']);
      }

      return $this->sendFailedLoginResponse($request);
   }
}

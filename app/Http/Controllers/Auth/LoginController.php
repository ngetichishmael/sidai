<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
//   use AuthenticatesUsers {
//      authenticated as protected traitAuthenticated;
//   }


   protected function authenticated(Request $request, $user)
   {
//      $requiredPermissions = ['admin_dashboard', 'manager_dashboard', 'shop_attendee_dashboard'];
//      info($user);
//      foreach ($requiredPermissions as $permission) {
//         info($user->hasPermission($permission));
//         if ($user->hasPermission($permission)) {
//            return redirect()->intended($this->redirectTo);
//         }
//      }
      info($user->account_type);

      if (strcasecmp($user->account_type, 'Admin') == 0 ||
         strcasecmp($user->account_type, 'RSM') == 0 ||
         strcasecmp($user->account_type, 'NSM') == 0 ||
         strcasecmp($user->account_type, 'shop-attendee') == 0) {
         return redirect()->intended($this->redirectTo);
      }
      // User didn't have any of the required permissions, so log them out.
      Auth::logout();
     // return redirect()->route('login')->withErrors(['permissions' => 'Unauthorized']);
      return redirect()->route('login')->with('error', 'You do not have permission to access the dashboard.');
   }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\systemMail;
use App\Models\business;
use App\Models\laratrust\Role_user;
use App\Models\User;
use App\Models\warehousing;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
   /*
   |--------------------------------------------------------------------------
   | Register Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the registration of new users as well as their
   | validation and creation. By default this controller uses a trait to
   | provide this functionality without requiring any additional code.
   |
   */

   use RegistersUsers;

   /**
    * Where to redirect users after registration.
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
      $this->middleware('guest');
   }

   /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {
      return Validator::make($data, [
         'name' => ['required', 'string', 'max:255'],
         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
         'password' => ['required', 'string', 'min:8', 'confirmed'],
      ]);
   }

   /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return \App\User
    */
   protected function create(array $data)
   {
      return User::create([
         'name' => $data['name'],
         'email' => $data['email'],
         'password' => Hash::make($data['password']),
      ]);
   }


   /**
    * Create a new user instance after a valid registration.
    *
    *
   */
   public function signup_form(){
      return view('auth.register');
   }

   /**
    * Create a new user instance after a valid registration.
    *
    *
   */
   public function signup(Request $request){
      $this->validate($request, [
         'full_names' => 'required',
         'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
         'password' => ['required', 'string', 'min:6', 'confirmed'],
      ]);

      //token
      $token = Str::random(18);

      $checkEmail = User::where('email',$request->email)->count();
      if($checkEmail != 0){
         Session()->flash('warning','The email your entered is already in use');

         return redirect()->back();
      }

      //add to user table
      $user = new User;
      $user->name = $request->full_names;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->remember_token = $token;
      $user->save();


      //add the business
      $code = Str::random(16);
      $business = new business;
      $business->name = $request->full_names;
      $business->primary_email = $request->email;
      $business->industry = 1;
      $business->base_currency = 132;
      $business->country = 110;
      $business->company_size = 1;
      $business->business_code = $code;
      $business->userID = $user->id;
      $business->ip =  request()->ip();
      $business->save();

      //add business ID to user account plus employeeID
      $update = User::find($user->id);
      $update->business_code = $code;
      $update->user_code = $code;
      $update->save();

      //create main branch
      $branches = new warehousing;
      $branches->name = 'Main Branch';
      $branches->country = 'Kenya';
      $branches->is_main = 'Yes';
      $branches->business_code = $code;
      $branches->created_by = $code;
      $branches->save();

      //add admin role to user
      $role = new Role_user;
      $role->role_id = 1;
      $role->user_id = $user->id;
      $role->user_type = 'App\Models\User';
      $role->save();

      //welcome email
      $subject = 'Welcome to sokoflow';
      $userContent = '<h4>Hello, '.$user->name.'</h4><p>Thank you for creating your account with us. Managing your business has never gotten easier.We have a powerful product suite to help you manage your business with ease. <p>';
      $to = $request->email;
      FacadesMail::to($to)->send(new systemMail($userContent,$subject));

      Session()->flash('success','Your account has been created you can now login');

      return redirect()->route('login');
   }
}

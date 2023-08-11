<?php

namespace App\Providers;
use App\Models\laratrust\Role_user;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Builder::macro('whereLike', function ($attributes, string $searchTerm) {
          $this->where(function (Builder $query) use ($attributes, $searchTerm) {
             foreach (Arr::wrap($attributes) as $attribute) {
                $query->when(
                   str_contains($attribute, '.'),
                   function (Builder $query) use ($attribute, $searchTerm) {
                      [$relationName, $relationAttribute] = explode('.', $attribute);

                      $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                         $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                      });
                   },
                   function (Builder $query) use ($attribute, $searchTerm) {
                      $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                   }
                );
             }
          });

          return $this;
       });

       Blade::if('haspermissionto', function ($expression) {
          if (!auth()->check()) {
             return false; // If the user is not authenticated, return false
          }

          $user = auth()->user();
          $roles = $user->getRoles(); // Assuming getRoles() returns an array of roles
          if (empty($roles)) {
             return false; // If the user has no roles, return false
          }
          $role = Role::where('name', $roles[0])->first();
//          $role = Role::where('name', auth()->user()->getRoles()[0])->first();
          foreach ($expression as $per) {
             $expre = str_replace("'", '', $per);
             $permission = Permission::where('name', $expre)->first();

             if (!$permission) {
                return false;
             }
             $user_permission = DB::table('permission_role')->where('role_id', $role->id)->where('permission_id', $permission->id)->first();;
            // dd($user_permission);
             if ($user_permission) {
                return true;
             }
            // dd($permission);

          }


          return false;
       });
       Blade::if('hasdataaccessto', function ($expression) {
          $user_roles = DB::table('role_user')->where('user_id', auth()->id())->get();

          if (count($user_roles) < 0) {
             return false;
          }

          foreach ($user_roles as $role) {
             foreach ($expression as $per) {
                $expre = str_replace("'", '', $per);
                $access_level = DB::table('roles')->where('id', $role->role_id)->where('data_access_level', $expre)->first();

                if ($access_level) {
                   return true;
                }
             }
//             $expre = str_replace("'", '', $expression);
            return false;
          }
       });
    }
}

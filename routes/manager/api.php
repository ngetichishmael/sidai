<?php

use App\Http\Controllers\Api\Manager\AuthenticationController;
use App\Http\Controllers\Api\Manager\CustomerController;
use App\Http\Controllers\Api\Manager\DashboardAppController;
use App\Http\Controllers\Api\Manager\OrdersController;
use App\Http\Controllers\Api\Manager\SendNotificationController;
use App\Http\Controllers\Api\Manager\TerritoryInformationsController;
use App\Http\Controllers\Api\Manager\UsersController;
use App\Http\Controllers\app\routesController;
use Illuminate\Support\Facades\Route;
use Knuckles\Scribe\Annotations as Scribe;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

   /*
   |--------------------------------------------------------------------------
   | Authentication
   |--------------------------------------------------------------------------
   */
   Route::post('/manager/login',  [AuthenticationController::class, 'login']);
   Route::post('signup', 'AuthController@userSignUp');
   Route::middleware(['auth:sanctum'])->group(function () {
      Route::get('/manager/customers', [CustomerController::class, 'getCustomers']);
      Route::get('/manager/users', [UsersController::class, 'getUsers']);
      Route::post('/manager/send/notification', [SendNotificationController::class, 'receiveNotification']);
      Route::get('/manager/all/regions', [TerritoryInformationsController::class, 'getAllTerritories']);
      Route::get('/manager/all/orders', [OrdersController::class, 'allOrders']);
      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);

      Route::get('/manager/simplified/orders', [OrdersController::class, 'allOrdersUsingAPIResource'])->name('manager.orders');
      Route::get('/manager/customers/orders', [OrdersController::class, 'allOrderForCustomers']);
      Route::get('/manager/allocation/data', [OrdersController::class, 'allocationItems']);
      Route::post('/manager/order/approval', [OrdersController::class, 'orderApproval']);
      Route::post('/manager/order/disapproval', [OrdersController::class, 'orderDisapproval']);
      Route::post('/manager/allocation/allocate', [OrdersController::class, 'allocateOrders']);

      Route::get('/manager/orders/transaction', [OrdersController::class, 'transaction'])->name('manager.transaction');
      Route::post('/manager/orders/custom/transaction', [OrdersController::class, 'customTransaction']);

      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);
      Route::post('/manager/dashboard/custom/data', [DashboardAppController::class, 'custom']);

      Route::get('/manager/routes/data', [RoutesController::class, 'getRoutes']);
   });
});

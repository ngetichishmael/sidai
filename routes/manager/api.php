<?php

use App\Http\Controllers\Api\Manager\AuthenticationController;
use App\Http\Controllers\Api\Manager\CustomerController;
use App\Http\Controllers\Api\Manager\DashboardAppController;
use App\Http\Controllers\Api\Manager\OrdersController;
use App\Http\Controllers\Api\Manager\RoutesController;
use App\Http\Controllers\Api\Manager\SendNotificationController;
use App\Http\Controllers\Api\Manager\TerritoryInformationsController;
use App\Http\Controllers\Api\Manager\UsersController;
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
      Route::get('/manager/users/list', [UsersController::class, 'usersList']);
      Route::post('/manager/send/notification', [SendNotificationController::class, 'receiveNotification']);
      Route::get('/manager/all/regions', [TerritoryInformationsController::class, 'getAllTerritories']);
      Route::get('/manager/all/orders', [OrdersController::class, 'allOrders']);
      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);

      Route::post('/manager/suspend/user', [UsersController::class, 'suspendUser']);
      Route::post('/manager/activate/user', [UsersController::class, 'activateUser']);

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

      Route::get('/manager/reports/data', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'reports']);


      Route::get('/manager/vansales/today', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'vanSalesToday']);
      Route::get('/manager/vansales/last-week', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'vanSalesWeek']);
      Route::get('/manager/vansales/last-month', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'vanSalesMonth']);

      Route::get('/manager/preorder/today', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'preOrderToday']);
      Route::get('/manager/preorder/last-week', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'preOrderWeek']);
      Route::get('/manager/preorder/last-month', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'preOrderMonth']);

      Route::get('/manager/order-fulfillment/today', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'orderFulfillmentToday']);
      Route::get('/manager/order-fulfillment/last-week', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'orderFulfillmentWeek']);
      Route::get('/manager/order-fulfillment/last-month', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'orderFulfillmentMonth']);

      Route::get('/manager/visits/today', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'visitsToday']);
      Route::get('/manager/visits/last-week', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'visitsWeek']);
      Route::get('/manager/visits/last-month', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'visitsMonth']);

      Route::get('/manager/active-users/today', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'activeUsersToday']);
      Route::get('/manager/active-users/last-week', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'activeUsersWeek']);
      Route::get('/manager/active-users/last-month', [\App\Http\Controllers\Api\Manager\ReportsController::class, 'activeUsersMonth']);

      Route::post('/manager/assign/lead/target', [\App\Http\Controllers\Api\Manager\TargetController::class, 'assignLeadTarget']);
      Route::post('/manager/assign/sale/target', [\App\Http\Controllers\Api\Manager\TargetController::class, 'assignSaleTarget']);
      Route::post('/manager/assign/visit/target', [\App\Http\Controllers\Api\Manager\TargetController::class, 'assignVisitTarget']);
      Route::post('/manager/assign/order/target', [\App\Http\Controllers\Api\Manager\TargetController::class, 'assignOrderTarget']);
      Route::post('/manager/add/customer', [CustomerController::class, 'addCustomer']);

   });
});

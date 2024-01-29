<?php

use App\Http\Controllers\Api\DeliveriesController;
use App\Http\Controllers\Api\Manager\AuthenticationController;
use App\Http\Controllers\Api\Manager\CustomerController;
use App\Http\Controllers\Api\Manager\DashboardAppController;
use App\Http\Controllers\Api\Manager\OrdersController;
use App\Http\Controllers\Api\Manager\ProductsController;
use App\Http\Controllers\Api\Manager\RequisitionController;
use App\Http\Controllers\Api\Manager\RouteSchedulesController;
use App\Http\Controllers\Api\Manager\RoutesController;
use App\Http\Controllers\Api\Manager\SendNotificationController;
use App\Http\Controllers\Api\Manager\TargetController;
use App\Http\Controllers\Api\Manager\TerritoryInformationsController;
use App\Http\Controllers\Api\Manager\UserActivityController;
use App\Http\Controllers\Api\Manager\UsersController;
use App\Http\Controllers\Api\Manager\ReportsController;
use Illuminate\Support\Facades\Route;

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
      Route::get('/manager/unapproved_customers', [CustomerController::class, 'getUnapprovedCustomers']);
      Route::get('/manager/users', [UsersController::class, 'getUsers']);
      Route::get('/manager/account_types', [UsersController::class, 'accountTypes']);
      Route::get('/manager/distributors', [UsersController::class, 'distributors']);
      Route::post('/manager/users/list', [UsersController::class, 'usersList']);
      Route::post('/manager/send/notification', [SendNotificationController::class, 'receiveNotification']);

      Route::get('/manager/all/regions', [TerritoryInformationsController::class, 'getAllTerritories']);
      Route::get('/manager/all/orders', [OrdersController::class, 'allOrders']);
      Route::get('/manager/pending/orders', [OrdersController::class, 'pendingOrders']);
      Route::get('/manager/all/vansales', [OrdersController::class, 'allVansales']);
      Route::get('/manager/users/vansales/{user_code}', [OrdersController::class, 'userVansales']);
      Route::get('/manager/users/orders/{user_code}', [OrdersController::class, 'userOrders']);
      Route::get('/manager/customer/orders/{customer_id}', [OrdersController::class, 'customerOrders']);
      Route::get('/manager/pending/deliveries', [OrdersController::class, 'pendingDeriveries']);
      Route::get('/manager/customer/deliveries/{customer_id}', [OrdersController::class, 'customerDeriveries']);
      Route::get('/manager/pending/distributor/orders', [OrdersController::class, 'pendingDistributorOrders']);
      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);

      Route::post('/manager/suspend/user', [UsersController::class, 'suspendUser']);
      Route::post('/manager/activate/user', [UsersController::class, 'activateUser']);

      Route::get('/manager/simplified/orders', [OrdersController::class, 'allOrdersUsingAPIResource']);
      Route::get('/manager/customers/orders', [OrdersController::class, 'allOrderForCustomers']);
      Route::get('/manager/allocation/data', [OrdersController::class, 'allocationItems']);
      Route::post('/manager/order/approval', [OrdersController::class, 'orderApproval']);
      Route::post('/manager/order/disapproval', [OrdersController::class, 'orderDisapproval']);
      Route::post('/manager/allocation/allocate', [OrdersController::class, 'allocateOrders']);
      Route::post('/manager/orders/allocation', [OrdersController::class, 'allocateOrders2']);

      Route::get('/manager/orders/transaction', [OrdersController::class, 'payments']);
      Route::post('/manager/orders/custom/transaction', [OrdersController::class, 'customTransaction']);

      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);
      Route::post('/manager/dashboard/custom/data', [DashboardAppController::class, 'custom']);

      Route::get('/manager/routes/data', [RoutesController::class, 'getRoutes']);

      Route::get('/manager/reports/data', [ReportsController::class, 'reports']);

      Route::get('/manager/vansales/today', [ReportsController::class, 'vanSalesToday']);
      Route::get('/manager/vansales/last-week', [ReportsController::class, 'vanSalesWeek']);
      Route::get('/manager/vansales/last-month', [ReportsController::class, 'vanSalesMonth']);

      Route::get('/manager/preorder/today', [ReportsController::class, 'preOrderToday']);
      Route::get('/manager/preorder/last-week', [ReportsController::class, 'preOrderWeek']);
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

      Route::post('/managers/get/deliveries', [DeliveriesController::class, 'getManagersDeliveries']);
      Route::post('/managers/custom/deliveries', [DeliveriesController::class, 'getManagersCustomDeliveries']);

      Route::get('manager/requisitions/list', [RequisitionController::class ,'index']);
      Route::get('manager/requisitions/history', [RequisitionController::class ,'history']);
      Route::post('manager/approve/{id}',  [RequisitionController::class ,'approve']);
      Route::post('manager/approve/requisitions', [RequisitionController::class ,'handleApproval']);

      //adding products
      Route::get('managers/all/products', [ProductsController::class,'index']);
      Route::get('managers/products/categories', [ProductsController::class,'categories']);
      Route::post('managers/products/store', [ProductsController::class, 'store']);
      Route::get('managers/products/{sku}/edit', [ProductsController::class, 'edit']);
      Route::post('managers/products/{sku}/restock', [ProductsController::class, 'restock']);
      Route::get('managers/products/{sku}/details', [ProductsController::class, 'details']);
     //activities
      Route::get('managers/monthly/activites', [UserActivityController::class, 'index']);

      //schedules
      Route::get('managers/all/route/schedules', [RouteSchedulesController::class,'index']);

      Route::get('managers/all/customer/visits', [UsersController::class, 'visits']);
      Route::get('managers/all/user/leads/{user_code}', [CustomerController::class, 'userLeads']);

      Route::get('managers/all/user/visits/{user_code}', [UsersController::class, 'userVisits']);
      Route::get('managers/all/active/users', [UsersController::class, 'activeUsers']);
      Route::get('managers/customer/visits/{customer_id}', [UsersController::class, 'customerVisits']);
      Route::get('managers/get/targets/{user_code}', [TargetController::class, 'getSalespersonTarget']);
   });
});

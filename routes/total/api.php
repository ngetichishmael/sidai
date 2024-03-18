<?php

use App\Http\Controllers\Api\CustomerCommentController;
use App\Http\Controllers\Api\FormResponseController;
use App\Http\Controllers\Api\Manager\AuthenticationController;
use App\Http\Controllers\Api\Manager\CustomerController;
use App\Http\Controllers\Api\Manager\DashboardAppController;
use App\Http\Controllers\Api\Manager\OrdersController;
use App\Http\Controllers\Api\Manager\SendNotificationController;
use App\Http\Controllers\Api\Manager\TerritoryInformationsController;
use App\Http\Controllers\Api\Manager\UsersController;
use App\Http\Controllers\Api\Total\DeliveryController;
use App\Http\Controllers\Api\total\RegionalFilter;
use App\Http\Controllers\Api\Total\TerritoryController;
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
   Route::post('/manager/login',  [AuthenticationController::class, 'login']);
   Route::middleware(['auth:sanctum'])->group(function () {
      Route::get('/manager/customers', [CustomerController::class, 'getCustomers']);
      Route::get('/manager/users', [UsersController::class, 'getUsers']);
      Route::post('/manager/send/notification', [SendNotificationController::class, 'receiveNotification']);
      Route::get('/manager/all/regions', [TerritoryInformationsController::class, 'getAllTerritories']);
      Route::get('/manager/all/orders', [OrdersController::class, 'allOrders']);
      Route::get('/manager/dashboard/data', [DashboardAppController::class, 'dashboard']);

      /**
       * Regions
       */
      Route::get('/total/regions/data', [TerritoryController::class, 'terriory']);
      Route::get('/total/routes', [TerritoryController::class, 'routes']);
      /**
       *
       * Customer filtered by region
       */
      Route::get('/total/customer/data', [RegionalFilter::class, 'filterRegionalCustomers']);
      /**
       * Delivery
       */
      Route::post('/partial/delivery/{delivery_code}', [DeliveryController::class, 'partialDelivery']);
      Route::post('/full/delivery/{delivery_code}', [DeliveryController::class, 'fullDelivery']);
      Route::post('/edit/delivery/{delivery_code}', [DeliveryController::class, 'editDelivery']);
      Route::post('/cancel/delivery/{delivery_code}', [DeliveryController::class, 'cancel']);

      //forms
      Route::post('/form/responses/{customer_id}/{checking_code}', [FormResponseController::class, 'store'])->middleware('auth:sanctum');

      /**
       * Comments section
       */
      Route::get('customer/comments/{id}', [CustomerCommentController::class, 'show']);
      Route::post('customer/comment', [CustomerCommentController::class, 'store']);
   });
});

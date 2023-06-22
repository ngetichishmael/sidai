<?php

use App\Http\Controllers\Api\AppsPermissionController;
use App\Http\Controllers\Api\CompanyRouteController;
use App\Http\Controllers\Api\CurrentDeviceInformationController;
use App\Http\Controllers\Api\CustomersProductsController;
use App\Http\Controllers\Api\CustomerVisitsOrders;
use App\Http\Controllers\Api\DeliveriesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OutletTypesController;
use App\Http\Controllers\Api\productCategoriesController;
use App\Http\Controllers\Api\StockRequisitionController;
use App\Http\Controllers\Api\surveyController;
use App\Http\Controllers\Api\SurveryAnswersController;
use App\Http\Controllers\Api\ReconcilationController;
use App\Http\Controllers\Api\ReconciledProductsController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\TargetsController;
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

require __DIR__ . '/manager/api.php';
require __DIR__ . '/customer/api.php';
require __DIR__ . '/total/api.php';
Route::group(['namespace' => 'Api'], function () {

   //customers
   Route::get('customers/{businessCode}', 'customersController@index')->middleware('auth:sanctum');
   Route::post('customers/add-customer', 'customersController@add_customer')->middleware('auth:sanctum');
   Route::post('customer/edit-customer', 'customersController@editCustomer')->middleware('auth:sanctum');
   Route::get('customers/{code}/details', 'customersController@details')->middleware('auth:sanctum');
   Route::post('customer/request/toBeCreditor', 'customersController@RequestToBeCreditor')->middleware('auth:sanctum');
   Route::post('customer/creditor/status', 'customersController@creditorStatus')->middleware('auth:sanctum');
   Route::get('customers/{customerID}/{businessCode}/deliveries', 'customersController@deliveries')->middleware('auth:sanctum');
   Route::get('customers/delivery/{code}/details', 'customersController@delivery_details')->middleware('auth:sanctum');
   Route::get('customers/{customerID}/orders', 'customersController@orders')->middleware('auth:sanctum');
   Route::get('customer/groups', 'customersController@groups')->middleware('auth:sanctum');

//   Route::post('/messages', 'ChatController@sendMessage');


   Route::get('customers/order/{orderCode}/details', 'customersController@order_details')->middleware('auth:sanctum');
   Route::get('customers/{customerID}/new-order/', 'customersController@new_order')->middleware('auth:sanctum');

   //products
   Route::get('products/{businessCode}', 'productsController@index')->middleware('auth:sanctum');

   //product categories
   Route::get('products/categories/{businessCode}', 'productCategoriesController@index');
   Route::get('products/{categoryID}/category', 'productCategoriesController@products_by_category');

   //deliveries
   Route::get('deliveries/{businessCode}/{userCode}', 'deliveryController@index');
   Route::get('delivery/{code}/{businessCode}/details', 'deliveryController@details');
   Route::post('/accept/delivery', 'deliveryController@acceptDelivery')->middleware('auth:sanctum');
   Route::post('/reject/delivery', 'deliveryController@rejectDelivery')->middleware('auth:sanctum');

   //customer checking
   Route::post('customer/checkin/session', ['uses' => 'checkinController@create_checkin_session']);
   Route::get('customer/{CustomerCode}/checkin', ['uses' => 'checkinController@checkin', 'as' => 'customer.checkin']);
   Route::get('checkin/{checkinCode}/stock', ['uses' => 'checkinController@stock', 'as' => 'checkin.stock']);
   Route::get('checkin/{checkinCode}/out', ['uses' => 'checkinController@checkout', 'as' => 'check.out']);



   // Route::post('checkin/{checkinCode}/add-to-cart',['uses' => 'checkinController@add_to_cart','as' => 'add.to.cart']);
   //Route::post('checkin/{checkinCode}/add-to-cart','checkinController@add_to_cart')->middleware('auth:sanctum');

   // Van Sales
   Route::post('checkin/vansales/{checkinCode}/{random}/add-to-cart', 'CheckingSaleOrderController@VanSales')->middleware('auth:sanctum');

   //New Sales Order

   Route::post('checkin/newsales/{checkinCode}/{random}/{distributor}/add-to-cart', 'CheckingSaleOrderController@NewSales')->middleware('auth:sanctum');




   Route::get('checkin/{checkinCode}/cart', ['uses' => 'checkinController@cart', 'as' => 'checkin.cart']);
   Route::post('checkin/{checkinCode}/order-save', ['uses' => 'checkinController@save_order', 'as' => 'checkin.order.save']);
   Route::get('checkin/{checkinCode}/cart/{id}/delete', ['uses' => 'checkinController@cart_delete', 'as' => 'checkin.cart.delete']);

   Route::get('checkin/{checkinCode}/orders', ['uses' => 'checkinController@orders', 'as' => 'checkin.orders']);
   Route::get('checkin/userOrders', ['uses' => 'checkinController@userOrders', 'as' => 'checkin.userOrders'])->middleware('auth:sanctum');

   Route::post('checkin/{checkinCode}/order/edit/reason', ['uses' => 'checkinController@order_edit_reason', 'as' => 'checkin.order.edit.reason']);
   Route::get('checkin/{checkinCode}/order/{orderID}/edit', ['uses' => 'checkinController@order_edit', 'as' => 'checkin.order.edit']);
   Route::post('checkin/{checkinCode}/order/{itemID}/update', ['uses' => 'checkinController@order_update', 'as' => 'checkin.order.update']);
   Route::get('checkin/{checkinCode}/order/{itemID}/delete/item', ['uses' => 'checkinController@order_delete_item', 'as' => 'checkin.order.delete.item']);
   Route::post('checkin/checkinCode/cancel', ['uses' => 'checkinController@order_cancellation', 'as' => 'checkin.order.cancellation']);


   Route::get('checkin/{checkinCode}/visits', ['uses' => 'checkinController@visits', 'as' => 'checkin.visits']);
   Route::post('checkin/{checkinCode}/visit/add', ['uses' => 'checkinController@visit_add', 'as' => 'checkin.visit.add']);

   //checkin visits *History *
   Route::get('checkin/{checkinCode}/order/{orderID}/details', ['uses' => 'checkinController@order_details', 'as' => 'checkin.order.details']);

   Route::get('checkin/{checkinCode}/order/{orderID}/print', ['uses' => 'checkinController@order_print', 'as' => 'checkin.order.print']);

   Route::get('latest/allocation/{user_code}', ['uses' => 'checkinController@latest_allocation', 'as' => 'checkin.latest.allocation']);
   Route::get('allocation/history/{user_code}', ['uses' => 'checkinController@allocation_history', 'as' => 'checkin.allocation.history']);

   Route::post('/test/notifications', [NotificationController::class, 'sendFirebaseNotification']);
   Route::get('/customer/notifications', [NotificationController::class, 'getCustomerNotification'])->middleware('auth:sanctum');
   /*
   |--------------------------------------------------------------------------
   | Authentication
   |--------------------------------------------------------------------------
   */
   Route::post('login',  'AuthController@userLogin');
   Route::post('signup', 'AuthController@userSignUp');
   // Route::get('user/{phonenumber}/details', 'AuthController@user_details');

   // send otp
   Route::post('send/otp/{number}', 'AuthController@sendOTP');
   Route::post('verify/otp/{number}/{otp}', 'AuthController@verifyOTP');

   Route::post('/reset-password', 'AuthController@updatePassword');

   Route::get('/countOrders', 'OrdersCountController@index')->middleware('auth:sanctum');
   Route::get('/countVisits', 'VisitsCountController@index')->middleware('auth:sanctum');
   Route::get('/SalesMade', 'SalesMadeController@index')->middleware('auth:sanctum');
   Route::get('/NewLeads', 'NewLeadsController@index')->middleware('auth:sanctum');
   Route::get('/NewLeads', 'NewLeadsController@index')->middleware('auth:sanctum');


   //Sales History
   //Start
   Route::get('/SalesHistory/{shopID}', 'SalesHistoryController@index')->middleware('auth:sanctum');
   Route::get('/SalesHistory/vansale/{shopID}', 'SalesHistoryController@vansales')->middleware('auth:sanctum');
   Route::get('/SalesHistory/newsale/{shopID}', 'SalesHistoryController@preorder')->middleware('auth:sanctum');

   //End

   Route::post('/scheduleVisit/{CustomerAccountNumber}', 'VisitScheduleController@NewVisit')->middleware('auth:sanctum');
   Route::get('/scheduleVisit/checkAll', 'AddNewRouteController@index')->middleware('auth:sanctum');
   Route::post('/payment', 'PaymentController@index')->middleware('auth:sanctum');


   //Stock Lift

   Route::post('/stocklift', 'StockLiftController@index')->middleware('auth:sanctum');

   // Select all Items
   Route::get('/distributors', 'StockDistributer@index')->middleware('auth:sanctum');
   Route::get('/stocklift/show', 'StockLiftController@show')->middleware('auth:sanctum');
   Route::get('/stocklift/receive', 'StockLiftController@receive')->middleware('auth:sanctum');

   // Surveying
   Route::get('/survey', [surveyController::class, 'getAllSurvey']);
   Route::get('/survey/questions/{surveyCode}', [surveyController::class, 'getAllQuestions']);
   Route::post('/survey/responses', [SurveryAnswersController::class, 'index']);

   // Reconcillations
   Route::get('/reconcile/payment', [ReconcilationController::class, 'index'])->middleware('auth:sanctum');
   Route::post('/reconcile/products', [ReconciledProductsController::class, 'index'])->middleware('auth:sanctum');
   Route::get('/get/targets', [TargetsController::class, 'getSalespersonTarget'])->middleware('auth:sanctum');


   /**
    * Reports
    */
   Route::get('/get/reports', [ReportsController::class, 'getReports'])->middleware('auth:sanctum');

   /**
    * Deliveries
    */
   Route::get('/get/deliveries', [DeliveriesController::class, 'getDeliveries'])->middleware('auth:sanctum');

   /**
    * Visits and Order Count
    */
   Route::get('/get/count/{customerID}', [CustomerVisitsOrders::class, 'getCounts'])->middleware('auth:sanctum');

   /**
    * Routess schedules
    */
   Route::post('/AddNewRoute', 'AddNewRouteController@store')->middleware('auth:sanctum');
   Route::get('route/schedule/{id}', ['uses' => 'routeScheduleController@show', 'as' => 'route.schedule']);



   /**
    * API send image data to customer
    */
   Route::get('/all/products', [CustomersProductsController::class, "getAllProducts"])->middleware('auth:sanctum');
   Route::post('/update/default/image', [CustomersProductsController::class, "sendDefaultImage"])->middleware('auth:sanctum');
//customer offers endpoints
   Route::get('/all/products-offers', [CustomersProductsController::class, "getAllProductsOffers"])->middleware('auth:sanctum');


   /**
    * App permissions
    */
   Route::get('/get/permissions', [AppsPermissionController::class, "getAllPermission"])->middleware('auth:sanctum');

   /**
    * Product Category with product information and  Prices
    */
   Route::get('/get/category/information', [productCategoriesController::class, "getCategory"])->middleware('auth:sanctum');


   /**
    * Post Device data
    */
   Route::post('/current/device/information', [CurrentDeviceInformationController::class, "postCurrentDeviceInformation"])->middleware('auth:sanctum');

   /**
    * Get Outlet Types
    */
   Route::get('/get/outlet/types', [OutletTypesController::class, "getOutletTypes"])->middleware('auth:sanctum');

   Route::get('customer/profile', 'customersController@customerprofile')->middleware('auth:sanctum');
   Route::put('customer/profile/update', 'customersController@updateCustomerProfile')->middleware('auth:sanctum');
   Route::put('customer/image/update', 'customersController@updateCustomerImage')->middleware('auth:sanctum');
   /**
    * Get Company Routes
    */
   Route::get('/get/company/routes', [CompanyRouteController::class, "getCompanyRoutes"])->middleware('auth:sanctum');

  //stock requisition
   Route::get('stock/requisitions', [StockRequisitionController::class, "show"])->middleware('auth:sanctum');
   Route::post('/stock/create/request', [StockRequisitionController::class, "store"])->middleware('auth:sanctum');
   Route::post('/stock/cancel', [StockRequisitionController::class, "cancel"])->middleware('auth:sanctum');
   Route::post('/stock/update', [StockRequisitionController::class, "update"])->middleware('auth:sanctum');
});

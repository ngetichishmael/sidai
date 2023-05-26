<?php

use App\Http\Controllers\app\Map\MapsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
   Route::resource('target/sales', app\Target\SalesController::class)->names([
      'index' => 'sales.target',
      'show' => 'sales.target.show',
      'edit' => 'sales.target.edit',
      'update' => 'sales.target.update',
      'destroy' => 'sales.target.destroy',
      'create' => 'sales.target.create',
      'store' => 'sales.target.store',
   ]);
   Route::resource('target/visit', app\Target\VisitsController::class)->names([
      'index' => 'visit.target',
      'show' => 'visit.target.show',
      'edit' => 'visit.target.edit',
      'update' => 'visit.target.update',
      'destroy' => 'visit.target.destroy',
      'create' => 'visit.target.create',
      'store' => 'visit.target.store',
   ]);
   Route::resource('target/leads', app\Target\LeadsController::class)->names([
      'index' => 'leads.target',
      'show' => 'leads.target.show',
      'edit' => 'leads.target.edit',
      'update' => 'leads.target.update',
      'destroy' => 'leads.target.destroy',
      'create' => 'leads.target.create',
      'store' => 'leads.target.store',
   ]);
   Route::resource('target/order', app\Target\OrdersController::class)->names([
      'index' => 'order.target',
      'show' => 'order.target.show',
      'edit' => 'order.target.edit',
      'update' => 'order.target.update',
      'destroy' => 'order.target.destroy',
      'create' => 'order.target.create',
      'store' => 'order.target.store',
   ]);
   Route::resource('current-information', app\Map\SalesAgentsController::class)->names([
      'index' => 'current-information',
      'show' => 'current-information.show',
      'edit' => 'current-information.edit',
      'update' => 'current-information.update',
      'destroy' => 'current-information.destroy',
      'create' => 'current-information.create',
      'store' => 'current-information.store',
   ]);
   Route::resource('survery/responses', SurveryResponseController::class)->names([
      'index' => 'SurveryResponses',
      'show' => 'SurveryResponses.show',
      'edit' => 'SurveryResponses.edit',
      'update' => 'SurveryResponses.update',
      'destroy' => 'SurveryResponses.destroy',
      'create' => 'SurveryResponses.create',
      'store' => 'SurveryResponses.store',
   ]);
   Route::resource('customer/pricing', PriceGroupController::class)->names([
      'index' => 'pricing',
      'show' => 'pricing.show',
      'edit' => 'pricing.edit',
      'update' => 'pricing.update',
      'destroy' => 'pricing.destroy',
      'create' => 'pricing.create',
      'store' => 'pricing.store',
   ]);
   Route::resource('customer/outlets', OutletTypesController::class)->names([
      'index' => 'outlets',
      'show' => 'outlets.show',
      'edit' => 'outlets.edit',
      'update' => 'outlets.update',
      'destroy' => 'outlets.destroy',
      'create' => 'outlets.create',
      'store' => 'outlets.store',
   ]);
   Route::resource('payment/paid', PaidPaymentController::class)->names([
      'index' => 'PaidPayment',
      'show' => 'PaidPayment.show',
      'edit' => 'PaidPayment.edit',
      'update' => 'PaidPayment.update',
      'destroy' => 'PaidPayment.destroy',
      'create' => 'PaidPayment.create',
      'store' => 'PaidPayment.store',
   ]);
   Route::resource('payment/pending', PendingPaymentController::class)->names([
      'index' => 'PendingPayment',
      'show' => 'PendingPayment.show',
      'edit' => 'PendingPayment.edit',
      'update' => 'PendingPayment.update',
      'destroy' => 'PendingPayment.destroy',
      'create' => 'PendingPayment.create',
      'store' => 'PendingPayment.store',
   ]);
   Route::resource('visits/user', UserVisitController::class)->names([
      'index' => 'UsersVisits',
      'show' => 'UsersVisits.show',
   ]);
   Route::resource('visits/customer', CustomerVisitController::class)->names([
      'index' => 'CustomerVisits',
      'show' => 'CustomerVisits.show',
   ]);
   Route::resource('customer/comment', CustomerCommentController::class)->names([
      'index' => 'CustomerComment',
   ]);
   Route::group(['prefix' => 'maps'], function () {
      Route::get('/', [MapsController::class, 'index'])->name('maps');
   });
});

<?php

use App\Http\Controllers\Notification\CustomersNotificationController;
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
   Route::get('notification/customer',[CustomersNotificationController::class,'index'])->name('CustomerNotification');
   Route::get('notification/users',[CustomersNotificationController::class,'users'])->name('UserNotification');
   Route::get('notification/all',[CustomersNotificationController::class,'all'])->name('AllNotification');
});

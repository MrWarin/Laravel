<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MemberController;

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

// Route::get('/member', [Member\MemberController::class, 'index'])->name('member');

// Route::post('/member/login', [Member\MemberController::class, 'store']);

// Route::get('/member/verify/{id}/{password}', [Backoffice\CustomerController::class, 'verify']);

// Route::group(['middleware' => 'auth:member'], function () {
//   Route::resources([
//     '/member/dashboard' => Member\DashboardController::class,
//   ]);
// });

// Route::domain('seller.example.com')->group(function () {

  Route::get('/', [Backoffice\AuthController::class, 'index'])->name('backoffice');
  Route::post('/login', [Backoffice\AuthController::class, 'store']);
  Route::get('/user/verify/{id}/{password}', [Backoffice\UserController::class, 'verify']);

  Route::group(['middleware' => ['auth:user', 'verified']], function () {

    Route::resources([
      '/home'             => Backoffice\DashboardController::class,
      '/product'          => Backoffice\ProductController::class,
      '/product-category' => Backoffice\ProductCategoryController::class,
      '/customer'         => Backoffice\CustomerController::class,
      '/user'             => Backoffice\UserController::class,
      '/order'            => Backoffice\OrderController::class,
      '/shipping'         => Backoffice\ShippingController::class,
      '/ticket'           => Backoffice\TicketController::class,
      '/log'              => Backoffice\LogController::class,
      '/setting'          => Backoffice\SettingController::class,
      '/operation'        => Backoffice\OperationController::class,
    ]);

    Route::get('/product/{page}/page', [Backoffice\ProductController::class, 'index']);
    Route::get('/order/{page}/page', [Backoffice\OrderController::class, 'index']);
    Route::get('/shipping/{page}/page', [Backoffice\ShippingController::class, 'index']);
    Route::get('/customer/{page}/page', [Backoffice\CustomerController::class, 'index']);
    Route::get('/user/{page}/page', [Backoffice\UserController::class, 'index']);
    Route::get('/log/{page}/page', [Backoffice\LogController::class, 'index']);
    Route::get('/ticket/{page}/page', [Backoffice\TicketController::class, 'index']);
    Route::get('/logout', [Backoffice\AuthController::class, 'logout']);
    Route::post('/user/{id}/reset-password', [Backoffice\UserController::class, 'reset']);
    Route::get('/attribute', [Backoffice\ProductController::class, 'showAttribute']);
    Route::get('/attribute/create', [Backoffice\ProductController::class, 'storeAttribute']);
    Route::get('/brand', [Backoffice\ProductController::class, 'showBrand']);
    Route::get('/brand/create', [Backoffice\ProductController::class, 'storeBrand']);
  });
// });

Route::get('/load_chatbox', function() { return view('includes\chatbox'); });

Auth::routes(['register' => false, 'login' => false, 'verify' => true]);

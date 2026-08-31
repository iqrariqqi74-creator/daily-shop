<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Backend\BannerController;
use App\Http\Controllers\Api\Backend\CategoryController;
use App\Http\Controllers\Api\Backend\SizeController;
use App\Http\Controllers\Api\Backend\ColorController;
use App\Http\Controllers\Api\Backend\BrandController;
use App\Http\Controllers\Api\Backend\CommonController;
use App\Http\Controllers\Api\Backend\RoleController;
use App\Http\Controllers\Api\Backend\PermissionController;
use App\Http\Controllers\Api\Backend\UserController;
use App\Http\Controllers\Api\Backend\CustomerController;
use App\Http\Controllers\Api\Backend\OrderController;
use App\Http\Controllers\Api\Backend\ProductController;
use App\Http\Controllers\Api\Backend\TaxController;
use App\Http\Controllers\Api\Backend\CouponController;
use App\Http\Controllers\Api\Backend\VendorController;
use App\Http\Controllers\Api\Backend\TransactionController;
use App\Http\Controllers\Api\FrontEnd\FrontController;

Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/admin/reset', [AuthController::class, 'resetPassword']);

Route::get('/home', [FrontController::class, 'index']);
Route::post('/add-to-cart', [FrontController::class, 'add_to_cart']);
Route::get('/category/{id}', [FrontController::class, 'category']);
Route::get('/product/{slug}', [FrontController::class, 'product']);
Route::get('/search', [FrontController::class, 'search']);
Route::post('/login', [FrontController::class, 'login_process']);
Route::post('/registration', [FrontController::class, 'registration_process']);

Route::get('/email-verification/{rand_id}', [FrontController::class, 'email_verification']);
Route::post('forgot_password', [FrontController::class, 'forgot_password']);
Route::post('/reset_password', [FrontController::class, 'reset_password']);
Route::post('place_order', [FrontController::class, 'place_order']);
Route::post('apply_coupon_code', [FrontController::class, 'apply_coupon_code']);
Route::post('remove_coupon_code', [FrontController::class, 'remove_coupon_code']);
Route::get('/cart', [FrontController::class, 'cart']);



Route::middleware('auth:customer_api')->group(function () {
Route::get('/order', [FrontController::class, 'order']);
Route::post('/logout', [FrontController::class, 'logout']);
Route::get('/order_detail/{id}',[FrontController::class, 'order_detail']);

});


Route::middleware('auth:api')->group(function () {
    Route::resource('common_options', CommonController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles_permissions', PermissionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('coupons', CouponController::class);



    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{id}/status', [CustomerController::class, 'updateStatus']);
    Route::resource('vendors', VendorController::class);
    Route::post('vendors/{id}/status', [VendorController::class, 'updateStatus']);
    Route::resource('orders', OrderController::class);
    Route::post('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post('orders/{id}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::get('customers/{id}/orders', [OrderController::class, 'getOrderByCustomer']);
    Route::get('/order/details/{id}',[OrderController::class, 'orderDetails']);
    Route::resource('transactions', TransactionController::class);
    Route::post('transactions/{id}/status', [TransactionController::class, 'updateStatus']);

    Route::get('transactions/{id}/vendors', [TransactionController::class, 'getVendorsByTransaction']);

    // Protected routes
});
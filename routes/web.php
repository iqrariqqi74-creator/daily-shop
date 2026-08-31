<?php

use App\Http\Controllers\Api\FrontEnd\FrontController;
use Illuminate\Support\Facades\Route;

// ✅ Frontend fallback route (for Vue frontend)
Route::get('/', function () {
    return view('frontend');
});

Route::get('/{any}', function () {
    return view('frontend');
})->where('any', '^(?!admin).*$');

// ✅ Admin fallback route (for Vue admin panel)
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*');


Route::get(
    '/email-verification/{rand_id}',
    [FrontController::class, 'email_verification']
)->name('email.verification');

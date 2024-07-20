<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;

use App\Http\Middleware\JwtMiddleware;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */


Route::group(['prefix' => 'v1', 'middleware' => JwtMiddleware::class], function () {
    Route::resource('products', ProductController::class);
});

Route::post('/memberregister', [MemberController::class, 'register']);
Route::post('/memberlogin', [MemberController::class, 'login']);
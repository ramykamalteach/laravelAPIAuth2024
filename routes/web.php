<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OneProduct;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [OneProduct::class, 'allProducts']);
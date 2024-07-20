<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class OneProduct extends Controller
{
    //
    public function allProducts() {
        $products = Product::all();
        return view('products.show', [
            "products" => $products,
        ]);
    }
}

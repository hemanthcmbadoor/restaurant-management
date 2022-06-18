<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function getDetails()
    {
        $categoryCount = Category::all()->count();
        $productCount = Product::all()->count();
        $bilCount = Bill::all()->count();

        return response()->json(
            [
                'status' => 1,
                'message' => [
                    'category'  => $categoryCount,
                    'product'   => $productCount,
                    'bill'      => $bilCount
                ]
            ], 200);

    }
}

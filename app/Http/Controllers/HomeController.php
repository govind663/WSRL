<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(Request $request)
    {
        return view('home');
    }

    public function fetchAvilableQuantity(Request $request)
    {
        // reqest product_id
        $productId = $request->productId;

        // fetch avilable quantity in Product Table
        $avilableQuantity = Product::where('id', $productId)->first()->pluck('available_quantity');
        return response()->json(['available_quantity' => $avilableQuantity]);
    }
}

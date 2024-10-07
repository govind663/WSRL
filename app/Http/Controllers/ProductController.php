<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->whereNull('deleted_at')->get();
        return view('product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        try {

            $product = new Product();

            // ==== Upload (image)
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $new_name = time() . rand(10, 999) . '.' . $extension;
                $image->move(public_path('/bhairaav/product/image'), $new_name);

                $image_path = "/bhairaav/product/image/" . $new_name; // Fixed missing slash
                $product->image = $new_name;
            }

            $product->name = $request->name ?? '';
            $product->description = $request->description ?? '';
            $product->in_stock = 1;
            $product->inserted_at = Carbon::now();
            $product->inserted_by = Auth::user()->id;
            $product->save();

            // ==== Generate SKU
            $product->sku = 'PRD_' . str_pad($product->id, 6, '0', STR_PAD_LEFT);

            // ==== Generate unique_no
            $product->unique_no = 'PRD_' . date('Y') . date('m') . date('d') . rand(100000, 999999) . $product->id;

            // ==== Update SKU and unique_no
            Product::where('id', $product->id)->update([
                'sku' => $product->sku,
                'unique_number' => $product->unique_no,
            ]);

            return redirect()->route('products.index')->with('message', 'Your record has been successfully created.');

        } catch(\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('product.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $data = $request->validated();
        try {

            $product = Product::findOrFail($id);

            // ==== Upload (image)
            if (!empty($request->hasFile('image'))) {
                $image = $request->file('image');
                $image_name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $new_name = time() . rand(10, 999) . '.' . $extension;
                $image->move(public_path('/bhairaav/product/image'), $new_name);

                $image_path = "/bhairaav/product/image" . $new_name;
                $product->image = $new_name;
            }

            $product->name = $request->name ?? '';
            $product->description = $request->description ?? '';
            $product->in_stock = 1;
            $product->modified_at = Carbon::now();
            $product->modified_by = Auth::user()->id;
            $product->save();

            return redirect()->route('products.index')->with('message','Your record has been successfully updated.');

        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong  - '.$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data['deleted_by'] =  Auth::user()->id;
        $data['deleted_at'] =  Carbon::now();
        try {
            $product = Product::findOrFail($id);
            $product->update($data);

            return redirect()->route('products.index')->with('message','Your record has been successfully deleted.');
        } catch(\Exception $ex){

            return redirect()->back()->with('error','Something Went Wrong - '.$ex->getMessage());
        }
    }
}

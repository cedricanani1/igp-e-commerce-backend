<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  Product::with('type','rate')->get();
        foreach ($products as $key => $product) {
            $rate=0;
            foreach ($product->rate as $key => $value) {
                $rate += (int) $value->rate;
            }
            if (count($product->rate) > 0) {
                $product->start = round($rate/count($product->rate));
            }else{
                $product->start =  0;
            }

        }
        return response()->json($products);
    }
    public function productsFilter(Request $request)
    {
        if ($request->key == 1) {
            $products =  Product::whereIn('product_type_id',$request->type)->orderBy("price","ASC")->get();
        }
        if ($request->key == 2) {
            $products =  Product::whereIn('product_type_id',$request->type)->orderByDesc("price")->get();
        }
        if ($request->key == 3) {
            $products =  Product::whereIn('product_type_id',$request->type)->orderByDesc("created_at")->get();
        }
        if ($request->key == 4) {
            $products =  Product::whereIn('product_type_id',$request->type)->withCount('order')->orderByDesc("order_count")->get();
        }
        if ($request->key == 5) {
            $products =  Product::whereIn('product_type_id',$request->type)->withCount('rate')->orderByDesc("rate_count")->get();
        }



        return response()->json($products);
    }
    public function bestRate()
    {
        $products =  Product::withCount('order','type','rate')->orderByDesc("rate_count")->get();
        foreach ($products as $key => $product) {
            $rate=0;
            foreach ($product->rate as $key => $value) {
                $rate += (int) $value->rate;
            }
            if (count($product->rate) > 0) {
                $product->start = round($rate/count($product->rate));
            }else{
                $product->start =  0;
            }
            $product->type;

        }
        return response()->json($products);
    }


    public function best(Request $request)
    {
        $status= $request->status;
        $products =  Product::withCount('order','type')->orderByDesc("order_count")->whereHas('order', function($q) use ($status){
            $q->where('status', $status);
        })->get();
        foreach ($products as $key => $product) {
            $rate=0;
            foreach ($product->rate as $key => $value) {
                $rate += (int) $value->rate;
            }

            if (count($product->rate) > 0) {
                $product->start = round($rate/count($product->rate));
            }else{
                $product->start =  0;
            }
        }
        return response()->json($products);
    }

    public function sellerAlltime()
    {

        $products= Product::has('order')->get();
        $orders =  Order::with('cart')->where('status', 'delivered')->get();
        foreach ($products as $key => $product) {
                    $product->count = 0;
                    $product->amount = 0;
            foreach ($orders as $key => $order) {
                foreach ($order->cart as $key => $cart) {
                   if ($product->id === $cart->product_id) {
                       $product->count += $cart->quantity;
                       $product->amount += $cart->quantity * $cart->price;
                   }else{
                    $product->count += 0;
                    $product->amount += 0;
                   }
                }
            }
        }
        return response()->json($products);
    }

    public function bestview()
    {
        $products =  Product::orderByDesc("view")->orderByDesc("view")->get();

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $product =  new Product;
        $product->libelle =  $request->libelle;
        $product->description =  $request->description;

        $file_path='';
        if (request()->hasFile('photo')) {
            $files = $request->file('photo');
            foreach ($files as $file) {
                $fileName= $file->getClientOriginalName();
                $path = $file->storeAs('Products', $fileName);
                $file_path = $file_path. $path .';';
            }
        }
        $product->photo = $file_path;
        $product->slug =  str_replace(" ","-",$request->libelle);
        $product->stock =  $request->stock;
        $product->price =  $request->price;
        $product->product_type_id =  $request->product_type_id;
        $product->save();

        if ($product) {
            return response()->json([
                'state' =>true
            ]);
        }else{
            return response()->json([
                'state' =>false
            ]);
        }
    }

    public function deleteFile(Request $request){
        $Product = Product::findOrFail($request->id);
        if ($request->path) {
            File::delete($request->path);
           $file =  str_replace($request->path.';',"",$Product->photo);
        }
        $Product->photo = $file;
        $Product->save();
        return response()->json([
            'state'=> true,
        ]);
    }

    public function addFile(Request $request){
        $Product = Product::findOrFail($request->id);

        $fileproduct = $Product->photo;

        if (request()->hasFile('photo')) {
            $files = $request->file('photo');
            foreach ($files as $file) {
                $fileName= $file->getClientOriginalName();
                $path = $file->storeAs('Products', $fileName);
                $fileproduct = $fileproduct. $path .';';
            }
        }
        $Product->photo =  $fileproduct;
        $Product->save();
        return response()->json([
            'state'=> true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        $rate=0;
        $product->view +=1;
        $product->save();
       foreach ($product->rate as $key => $value) {
        $rate += (int) $value->rate;
       }
       if (count($product->rate) > 0) {
        $product->start = round($rate/count($product->rate));
       }else{
        $product->start = 0;
       }

       $product->type;
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->libelle =  $request->libelle;
        $product->description =  $request->description;
        $product->discount =  $request->discount;
        $product->stock =  $request->stock;
        $product->slug =  str_replace(" ","-",$request->libelle);
        $product->price =  $request->price;
        $product->product_type_id =  $request->product_type_id;
        $product->save();

        if ($product) {
            return response()->json([
                'state' =>true
            ]);
        }else{
            return response()->json([
                'state' =>false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        if ($product) {
            return response()->json([
                'state' =>true
            ]);
        }else{
            return response()->json([
                'state' =>false
            ]);
        }
    }
}

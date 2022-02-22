<?php

namespace App\Http\Controllers;

use App\Models\ProductRate;
use App\Http\Requests\StoreProductRateRequest;
use App\Http\Requests\UpdateProductRateRequest;
use App\Models\Product;
use App\Services\UserService as User;

class ProductRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreProductRateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRateRequest $request)
    {
            $token = $request->header('Authorization');
            $user = $this->userToken($token);
            if (!$user) {
                return response()->json([
                    'state' =>false,
                    'message' =>'Veillez vous connecter au prealable',
                ]);
            }
            $rating =  new ProductRate();
            $product =  Product::findOrFail($request['product_id']);
            if ($product) {
                $rating->rate =  $request['rate'];
                $rating->product_id =  $product['id'];
                $rating->user_id =  $user['id'];
                $rating->message =  $request['message'];
                $rating->save();
            }
            if ($rating) {
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
     * Display the specified resource.
     *
     * @param  \App\Models\ProductRate  $productRate
     * @return \Illuminate\Http\Response
     */
    public function show(ProductRate $productRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductRate  $productRate
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductRate $productRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRateRequest  $request
     * @param  \App\Models\ProductRate  $productRate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRateRequest $request, ProductRate $productRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductRate  $productRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductRate $productRate)
    {
        //
    }

    private function userToken($token){

        if(User::get($token)->success == true) {
            return User::get($token)->user;
        }else{
            return null;
        }
    }
}

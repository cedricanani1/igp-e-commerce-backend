<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $producttypes =  ProductType::with('child','parent','products')->get();
        foreach ($producttypes as $key => $type) {
            foreach ($type->products as $key => $value) {
                $value->type;
            }
        }
        return response()->json($producttypes);
    }

    public function typeParent()
    {
        $producttypes =  ProductType::with('child','parent','products')->where('parent_id',0)->get();
        foreach ($producttypes as $key => $type) {
            foreach ($type->products as $key => $value) {
                $value->type;
            }
        }
        return response()->json($producttypes);
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
     * @param  \App\Http\Requests\StoreProductTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductTypeRequest $request)
    {
        $types =  new ProductType;
        $types->libelle =  $request->libelle;
        $types->slug =  $request->slug;
        $types->description =  $request->description;
        $types->parent_id =  $request->parent_id;
        $types->save();
        if ($types) {
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
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        $productType->products;
        $productType->child;
        return response()->json($productType);
    }

    public function productTypeSlug($slug)
    {
        $products = ProductType::where('slug',$slug)->first()->products;

        return response()->json($products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductTypeRequest  $request
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductTypeRequest $request, ProductType $productType)
    {
        $productType->libelle =  $request->libelle;
        $productType->slug =  $request->slug;
        $productType->description =  $request->description;
        $productType->parent_id =  $request->parent_id;
        $productType->save();
        if ($productType) {
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
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return response()->json($productType);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Http\Requests\StoreOrderProductRequest;
use App\Http\Requests\UpdateOrderProductRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\UserService as User;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $request->header('Authorization');
        $user = $this->userToken($token);
        if (!$user) {
            return response()->json([
                'state' =>false,
                'message' =>'Veillez vous connecter au prealable',
            ]);
        }
        $cart =  OrderProduct::with('product')->where('order_id',null)->where('user_id',$user->id)->get();

        return response()->json([
            'state' =>true,
            'data' =>$cart,
        ]);
        return response()->json($cart);
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
     * @param  \App\Http\Requests\StoreOrderProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderProductRequest $request)
    {
            $token = $request->header('Authorization');
            $user = $this->userToken($token);
            if (!$user) {
                return response()->json([
                    'state' =>false,
                    'message' =>'Veillez vous connecter',
                ]);
            }
            $cart =  new OrderProduct();
            $product =  Product::findOrFail($request['product_id']);
            if ($product) {
                $cart->product_id =  $request['product_id'];
                $cart->user_id =  $user->id;
                $cart->price =  $product->price;
                if (isset($request['color'])) {
                    $cart->color =  $request['color'];
                }
                if (isset($request['photo']) && request()->hasFile('photo')) {
                    $file = $request['photo'];
                    $fileName= $request['photo']->getClientOriginalName();
                    $cart->photo = $file->storeAs('Orders', $fileName);
                }
                $cart->quantity =  $request['quantity'];
                $cart->total =  $request['quantity'] * $product->price;
                $cart->save();
            }
            if ($cart) {
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
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function show(OrderProduct $orderProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderProduct $orderProduct)
    {
        //
    }

    public function updateCar(Request $request)
    {
        if (count($request->cart) > 0) {
            foreach ($request->cart as $key => $value) {
                $item =  OrderProduct::findOrFail($value['id']);
                $item->quantity =  $value['quantity'];
                $item->total =  $value['quantity'] * $item->price;
                $item->save();
            }
        }


        if ($item) {
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderProductRequest  $request
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderProductRequest $request, OrderProduct $orderProduct)
    {
        $orderProduct->price =  $request->price;
        $order = Order::findOrFail($orderProduct->order_id);
        $orderProduct->save();
        $amount = 0;
        if ($order) {

            foreach ($order->cart as $key => $value) {
                $amount += $value['quantity'] * $value['price'];
                $value->total= $value['quantity'] * $value['price'];
                $value->save();
            }
        }
        $order->total_amount = $amount;
        $order->save();
        if ($orderProduct) {
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
     * @param  \App\Models\OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderProduct $orderProduct)
    {
        $orderProduct->delete();
        if ($orderProduct) {
            return response()->json([
                'state' =>true
            ]);
        }else{
            return response()->json([
                'state' =>false
            ]);
        }

    }

    private function userToken($token){

        if(User::get($token)->success == true) {
            return User::get($token)->user;
        }else{
            return null;
        }
    }
}

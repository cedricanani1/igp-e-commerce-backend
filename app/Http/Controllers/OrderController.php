<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Services\UserService as User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  Order::with('cart')->orderBy('updated_at', 'DESC')->get();

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

    public function ordersClient(Request $request)
    {
        $token = $request->header('Authorization');
        $user = $this->userToken($token);
        if (!$user) {
            return response()->json([
                'state' =>false,
                'message' =>'Veillez vous connecter',
            ]);
        }

        $orders =  Order::where('user_id',$user->id)->orderBy('updated_at', 'DESC')->get();


        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {


        $token = $request->header('Authorization');
        $user = $this->userToken($token);
        if (!$user) {
            return response()->json([
                'state' =>false,
                'message' =>'Veillez vous connecter',
            ]);
        }
        $order =  new Order();
        $order->order_number =  'ORD-'.strtoupper(Str::random(10));
        $order->user_id =  $user->id;
        $order->payment_status =  'unpaid';
        $order->status =  'new';
        $order->nom =  $request->nom;
        $order->prenoms =  $request->prenoms;
        $order->email =  $request->email;
        $order->phone =  $request->phone;
        $order->shipping =  $request->shipping;
        $order->location =  $request->location;
        // $order->other =  $request->order['other'];
        $order->save();
        $amount = 0;
        $cart =  OrderProduct::where('order_id',null)->get();
        foreach ($cart as $key =>  $item) {

            $product =  Product::findOrFail($item['product_id']);
            if ($product) {
                $item->order_id =  $order['id'];
                $amount += $item['quantity'] * $product->price;
                $item->save();
            }

        }
        $order->total_amount =  $amount;
        $order->save();

        if ($order) {
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        foreach ($order->cart as $key => $value) {
            $value->product;
        }
        return response()->json($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->status =  $request->status;
        $order->save();

        if ($order) {
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        if ($order) {
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

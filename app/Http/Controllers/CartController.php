<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartItems = 0;
        Session::put('cartSize', 0);

        return view('cart.cart', compact('cartItems'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $transactionCode
     * @return \Illuminate\Http\Response
     */
    public function show($transactionCode)
    {
        $transactionCode = base64_decode($transactionCode);

        $cartItemsTemp = DB::table('orders')
            ->join('menus', 'orders.product_id', 'menus.id')
            ->select('orders.*', 'menus.name', 'menus.image', 'menus.price', 'menus.preparation_time')
            ->where('orders.status', config('constants.ORDER_STATUS_CART'))
            ->where('transaction_code', '=', $transactionCode)
            ->get();

        $cartItems = array();
        foreach ($cartItemsTemp as $item)
        {
            $item                   = (array) $item;
            $cartItems[$item['id']] = $item;
        }

        Session::put('cartSize', count($cartItems));

        return view('cart.cart', compact('cartItems'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = base64_decode($id);

        $cartItem = Order::find($id);

        $validatedCartItem = $this->validate($request, [
            'quantity' => 'required|numeric',
            'comment' => 'max:150',
        ]);


        if($validatedCartItem['quantity'] < 1)
        {
            $cartItem->delete();
        }
        else
        {
            $cartItem['quantity'] = $validatedCartItem['quantity'];
            $cartItem['comment'] = $validatedCartItem['comment'];
            $cartItem->save();
        }

        return redirect('cart/'.base64_encode($cartItem['transaction_code']))->with('success', 'Cart item with ID ' . $id . ' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $order = Order::find(base64_decode($id));

        $transactionCode = $order['transaction_code'];
        $order->delete();

        return redirect('cart/'.base64_encode($transactionCode))->with('success', 'Cart item has been removed');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
Use App\Menu;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = "";
        $data = array();
        $orderList = array();
        $transactions = array();

        if(Auth::user()->isOwner())
        {
            // GET TRANSACTION INFO --TO BE DELETED

            $orderListTemp = DB::table('orders')
                ->join('users', 'orders.customer_id', 'users.id')
                ->join('menus', 'orders.product_id', 'menus.id')
                ->select('orders.*', 'menus.name as product_name', 'menus.image as product_image', 'menus.price as product_price', 'users.name as customer_name')
                ->where('orders.stall_id', Auth::user()->id)
                ->where('orders.status', '<>', config('constants.ORDER_STATUS_CART'))
                ->get();


            foreach ($orderListTemp as $order)
            {
                $order  = (array) $order;
                $orderList[] = $order;
            }

            $transactions = array_column($orderList, 'customer_id', 'transaction_code');

            $view = 'orders.orderList';
        }
        else if(Auth::user()->isCustomer())
        {

            // GET TRANSACTION INFO --TO BE DELETED
            $orderListTemp = DB::table('orders')
                ->join('users', 'orders.stall_id', 'users.id')
                ->join('menus', 'orders.product_id', 'menus.id')
                ->select('orders.*', 'menus.*', 'users.name as stall_name')
                ->where('orders.customer_id', Auth::user()->id)
                ->where('orders.status', '<>', config('constants.ORDER_STATUS_CART'))
                ->get();

            foreach ($orderListTemp as $order)
            {
                $orderList[] = (array) $order;
            }

            $transactions = array_column($orderList, 'transaction_code', 'transaction_code');

            $stalls = array_column($orderList, 'stall_name', 'stall_id');

            $data['stalls'] = $stalls;

            $view = 'orders.orderListByCustomer';
        }

        $data['orderList'] = $orderList;

        $data['transactions'] = $transactions;

        return view($view, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!(\Session::has('transactionCode'))) :
            //SET $transactionCode
            $transactionCode = date("dmy") . Auth::user()->id . date("siH");
            Session::put('transactionCode', $transactionCode);
        else:
            $transactionCode = Session::get('transactionCode');
        endif;

        $validatedOrder = $this->validate($request,[
            'stallId' => 'required|numeric',
            'productId' => 'required|numeric',
            'quantity' => 'required|numeric'
        ]);

        $product = Menu::find($validatedOrder['productId']);

        $order                      = array();
        $order['transaction_code']  = $transactionCode;
        $order['stall_id']          = $validatedOrder['stallId'];
        $order['product_id']        = $validatedOrder['productId'];
        $order['customer_id']       = Auth::user()->id;
        $order['quantity']          = $validatedOrder['quantity'];
        $order['status']            = config('constants.ORDER_STATUS_CART');

        Order::create($order);

        return redirect('cart/'.base64_encode($transactionCode));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $id       = base64_decode($id);

        $this->validate($request, [
            'status' => 'required'
        ]);
        $status   = base64_decode($request->get('status'));

        Order::where('id', $id)
            ->update(['status' => $status]);

        return redirect('orders')->with('success','Order with ID ' . $id . ' status has been updated to ' . $status);
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

        $order->delete();

        return redirect('orders')->with('success', 'Order item has been deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $transactionCode
     * @return \Illuminate\Http\Response
     */
    public function destroyByTransactionCode($transactionCode)
    {
        Order::where('transaction_code', $transactionCode)->delete();
        Session::put('cartSize', 0);
        return redirect('cart/'.$transactionCode)->with('success', 'Cart has been emptied');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $transactionCode
     * @return \Illuminate\Http\Response
     */
    public function updateByTransactionCode(Request $request, $transactionCode)
    {
        $transactionCode = base64_decode($transactionCode);

        $data = array(
            'status' => config('constants.ORDER_STATUS_PENDING'),
        );

        Order::where('transaction_code', $transactionCode)
            ->update($data);

        Session::forget('cartSize');
        Session::forget('transactionCode');
        return redirect('/');
    }
}

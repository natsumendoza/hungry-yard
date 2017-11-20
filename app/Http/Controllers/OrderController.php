<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
Use App\Menu;
Use App\User;
use App\Transaction;
use App\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers;

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
        $transactionList = array();

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

            $transactionListTemp = Transaction::where([
                ['stall_id', Auth::user()->id],
                ['status', '<>', config('constants.TRANSACTION_STATUS_PENDING')],
            ])
                ->get()->toArray();


            foreach ($transactionListTemp as $tranTemp)
            {
                foreach ($transactions as $transaction_code => $transaction)
                {
                    if($transaction_code == $tranTemp['transaction_code'])
                    {
                        $transactionList[$transaction_code] = $tranTemp;
                    }
                }
            }

//            echo '<pre>';
//            print_r($transactionList);
//            die;

            $view = 'orders.orderList';
        }
        else if(Auth::user()->isCustomer())
        {

            // GET TRANSACTION INFO --TO BE DELETED
            $orderListObj = DB::table('orders')
                ->join('users', 'orders.stall_id', 'users.id')
                ->join('menus', 'orders.product_id', 'menus.id')
                ->select('orders.*', 'menus.name', 'menus.image', 'menus.price', 'menus.preparation_time', 'users.name as stall_name')
                ->where('orders.customer_id', Auth::user()->id)
                ->where('orders.status', '<>', config('constants.ORDER_STATUS_CART'))
                ->get();


            $orderListTemp = array();
            foreach ($orderListObj as $orderObj)
            {
                $orderListTemp[] = (array) $orderObj;
            }

            $transactions = array_column($orderListTemp, 'transaction_code', 'transaction_code');

            $stalls = array_column($orderListTemp, 'stall_name', 'stall_id');

            $orderList = array();
            foreach ($orderListTemp as $order)
            {
                foreach ($transactions as $transaction)
                {
                    if($transaction == $order['transaction_code'])
                    {
                        foreach ($stalls as $stall_id => $stall)
                        {
                            if($stall_id == $order['stall_id'])
                            {
                                $orderList[$transaction][$stall_id][] = (array) $order;
                            }
                        }
                    }
                }
            }


            $transactionListTemp = Transaction::where([
                ['customer_id', Auth::user()->id],
                ['status', '<>', config('constants.TRANSACTION_STATUS_PENDING')],
                ])
                ->get()->toArray();

            foreach ($transactionListTemp as $tranTemp)
            {
                foreach ($transactions as $transaction)
                {
                    if($transaction == $tranTemp['transaction_code'])
                    {
                        foreach ($stalls as $stall_id => $stall)
                        {
                            if($stall_id == $tranTemp['stall_id'])
                            {
                                $transactionList[$transaction][$stall_id] = $tranTemp;
                            }
                        }
                    }
                }
            }

            $data['stalls'] = $stalls;
            $view = 'orders.orderListByCustomer';
        }


        // GET NOTIFICATIONS INFO
        Helpers::getNotifications();

        if(!Auth::user()->isAdmin()) {
            $data['orderList'] = $orderList;
            $data['transactions'] = $transactions;
            $data['transactionList'] = $transactionList;

            return view($view, compact('data'));
        } else {
            return redirect('/');
        }
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
        if(Auth::guest())
        {
            return redirect('login');
        }
        else
        {
            if (!(\Session::has('transactionCode'))) :
                //SET $transactionCode
                $transactionCode = date("dmy") . Auth::user()->id . date("siH");
                Session::put('transactionCode', $transactionCode);
            else:
                $transactionCode = Session::get('transactionCode');
            endif;

            $validatedOrder = $this->validate($request, [
                'stallId' => 'required|numeric',
                'productId' => 'required|numeric',
            ]);

            $product = Menu::find($validatedOrder['productId']);

            $order = array();
            $order['transaction_code'] = $transactionCode;
            $order['stall_id'] = $validatedOrder['stallId'];
            $order['product_id'] = $validatedOrder['productId'];
            $order['customer_id'] = Auth::user()->id;
            $order['quantity'] = 1;
            $order['status'] = config('constants.ORDER_STATUS_CART');

            Order::create($order);

            return redirect('cart/' . base64_encode($transactionCode));
        }

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

        // STORE NOTIFICATION
        $notification           = array();
        $notification['to']     = base64_decode($request['customer_id']);
        $notification['action'] = Auth::user()->name." " . base64_decode($request['status']) ." the order with an ID " . $id ." under Transaction Code " . base64_decode($request['transaction_code']) .".";
        Helpers::storeNotification($notification);

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
        $id =base64_decode($id);
        $order = Order::find($id);

        // STORE NOTIFICATION
        $notification           = array();
        $notification['to']     = $order['stall_id'];
        $notification['action'] = "User [". Auth::user()->id ."] " . Auth::user()->name . " DELETED the order with an ID " . $id ." under Transaction Code " . $order['transaction_code'] . ".";
        Helpers::storeNotification($notification);

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
        Order::where('transaction_code', base64_decode($transactionCode))->delete();
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

        $orderList = Order::where('transaction_code', $transactionCode)
            ->get()->toArray();

        $stalls = array_column($orderList,'stall_id', 'stall_id');

        foreach ($stalls as $stall)
        {

            // STORE NOTIFICATION
            $notification           = array();
            $notification['to']     = $stall;
            $notification['action'] = "User [". Auth::user()->id ."] " . Auth::user()->name . " has placed order/s with Transaction Code " . $transactionCode . ".";
            Helpers::storeNotification($notification);

        }

        Session::forget('cartSize');
        Session::forget('transactionCode');
        return redirect('orders');
    }
}

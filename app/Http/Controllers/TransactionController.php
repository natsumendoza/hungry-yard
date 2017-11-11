<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use DateTime;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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


        $validatedTransaction = $this->validate($request,[
            'transaction_code' => 'required',
            'stall_id' => 'required',
            'pickup_time' => 'required',
            'total_price' => 'required',
//            'order_type' => 'required'
        ]);

        date_default_timezone_set('Asia/Manila');

        $pickUp = date('Y-m-d') . ' ' . $validatedTransaction['pickup_time'];



        $transaction = array();
        $transaction['transaction_code'] = base64_decode($validatedTransaction['transaction_code']);
        $transaction['customer_id'] = Auth::user()->id;
        $transaction['stall_id'] = base64_decode($validatedTransaction['stall_id']);
        $transaction['pickup_time'] = $pickUp;
        $transaction['total_price'] = base64_decode($validatedTransaction['total_price']);
        $transaction['order_type'] = config('constants.ORDER_TYPE_DI'); //$validatedTransaction['order_type'];
        $transaction['status'] = config('constants.TRANSACTION_STATUS_PAID');

        Transaction::create($transaction);

        return redirect('orders')->with('success','Transaction ' . $transaction['transaction_code'] . ' approved items has been paid.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

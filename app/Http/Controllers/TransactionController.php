<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

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
            'customer_id' => 'required|numeric',
            'stall_id' => 'required|numeric',
            'pickup_time' => 'required',
            'total_price' => 'required|numeric',
            'order_type' => 'required',
        ]);


        $transaction = array();
        $transaction['transaction_code'] = $validatedTransaction['transaction_code'];
        $transaction['customer_id'] = $validatedTransaction['customer_id'];
        $transaction['stall_id'] = $validatedTransaction['stall_id'];
        $transaction['pickup_time'] = $validatedTransaction['pickup_time'];
        $transaction['total_price'] = $validatedTransaction['total_price'];
        $transaction['order_type'] = $validatedTransaction['order_type'];
        $transaction['status'] = $validatedTransaction['customer_id'];

        Transaction::create($transaction);





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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use DateTime;
use function PHPSTORM_META\elementType;

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
        ]);

        date_default_timezone_set('Asia/Manila');

        $pickupTime = (int) substr($validatedTransaction['pickup_time'],0,2);
        $pickupDate = new DateTime(date('Y-m-d'));

        if($pickupTime < 16)
        {
            $pickupDate = date_add($pickupDate, date_interval_create_from_date_string('1 days'));
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }
        else
        {
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }


        $pickupDate = $pickupDate . ' ' . $validatedTransaction['pickup_time'];

        $cleanTransactionCode = base64_decode($validatedTransaction['transaction_code']);
        $cleanStallId         = base64_decode($validatedTransaction['stall_id']);

        $transaction = array();
        $transaction['transaction_code'] = $cleanTransactionCode;
        $transaction['customer_id'] = Auth::user()->id;
        $transaction['stall_id'] = $cleanStallId;
        $transaction['pickup_time'] = $pickupDate;
        $transaction['total_price'] = base64_decode($validatedTransaction['total_price']);
        $transaction['order_type'] = $request->get('order_type_' . $cleanTransactionCode . "_" . $cleanStallId);
        $transaction['status'] = config('constants.TRANSACTION_STATUS_PAID');

//        echo '<pre>';
//        print_r($transaction);
//        die;


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

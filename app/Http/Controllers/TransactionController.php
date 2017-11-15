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

        $request['pickup_time'] = date("h:i A", strtotime($request['pickup_time']));
        $request['preparation_time'] = base64_decode($request['preparation_time']);
        $request['total_price'] = base64_decode($request['total_price']);

        $validatedTransaction = $this->validate($request,[
            'transaction_code' => 'required',
            'stall_id' => 'required',
            'pickup_time' => 'required|date_format:h:i A|after:04:00 PM',
            'preparation_time' => 'required|numeric',
            'total_price' => 'required|numeric'
        ]);

        date_default_timezone_set('Asia/Manila');
        $pickupTime = date("H:i", strtotime($validatedTransaction['pickup_time']));
        $pickupDate = date_format(new DateTime(date('Y-m-d')), 'Y-m-d');


//        $pickupTime = (int) substr($validatedTransaction['pickup_time'],0,2);
        /*if($pickupTime < 16)
        {
            $pickupDate = date_add($pickupDate, date_interval_create_from_date_string('1 days'));
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }
        else
        {
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }*/


        $pickupDateTime = $pickupDate . ' ' . $pickupTime;

        $cleanTransactionCode = base64_decode($validatedTransaction['transaction_code']);
        $cleanStallId         = base64_decode($validatedTransaction['stall_id']);

        $transaction = array();
        $transaction['transaction_code'] = $cleanTransactionCode;
        $transaction['customer_id'] = Auth::user()->id;
        $transaction['stall_id'] = $cleanStallId;
        $transaction['preparation_time'] = $validatedTransaction['preparation_time'];
        $transaction['pickup_time'] = $pickupDateTime;
        $transaction['total_price'] = $validatedTransaction['total_price'];
        $transaction['order_type'] = $request->get('order_type_' . $cleanTransactionCode . "_" . $cleanStallId);
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
        $id       = base64_decode($id);


        $request['pickup_time'] = date("h:i A", strtotime($request['pickup_time']));

        $validatedTransaction = $this->validate($request,[
            'transaction_code' => 'required',
            'stall_id' => 'required',
            'pickup_time' => 'required|date_format:h:i A|after:04:00 PM'
        ]);

        date_default_timezone_set('Asia/Manila');
        $pickupTime = date("H:i", strtotime($validatedTransaction['pickup_time']));
        $pickupDate = new DateTime(date('Y-m-d'));
        $pickupDate = date_format($pickupDate, 'Y-m-d');

        $pickupDateTime = $pickupDate . ' ' . $pickupTime;

        $cleanStallId         = base64_decode($validatedTransaction['stall_id']);
        $cleanTransactionCode = base64_decode($validatedTransaction['transaction_code']);

        $updateTransaction                  = array();
        $updateTransaction['pickup_time']   = $pickupDateTime;
        $updateTransaction['order_type']   = $request->get('order_type_' . $cleanTransactionCode . "_" . $cleanStallId);

        Transaction::where([
                ['id', $id],
                ['transaction_code', $cleanTransactionCode],
                ['customer_id', Auth::user()->id]
            ])
            ->update($updateTransaction);

        return redirect('orders')->with('success','Transaction has been updated.');
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

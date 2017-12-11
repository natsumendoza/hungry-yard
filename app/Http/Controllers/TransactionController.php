<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;
use DateTime;
use function PHPSTORM_META\elementType;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Notification;
use App\Http\Helpers;
use App\StallImage;

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

        $cleanTransactionCode = base64_decode($request['transaction_code']);
        $cleanStallId         = base64_decode($request['stall_id']);

        $request['pickup_time'] = date("h:i A", strtotime($request['pickup_time']));
        $request['preparation_time'] = base64_decode($request['preparation_time']);
        $request['total_price'] = base64_decode($request['total_price']);
        $recommended = base64_decode($request['recommended_'.$cleanTransactionCode.'_'.$cleanStallId]);

        $validatedTransaction = $this->validate($request,[
            'transaction_code' => 'required',
            'stall_id' => 'required',
            'pickup_time' => 'required|date_format:h:i A|after_or_equal :' . $recommended,
            'preparation_time' => 'required|numeric',
            'total_price' => 'required|numeric'
        ]);

        date_default_timezone_set('Asia/Manila');
        $pickupTime = date("H:i", strtotime($validatedTransaction['pickup_time']));
        $pickupDate = date_format(new DateTime(date('Y-m-d')), 'Y-m-d');


        $validatePickupTime = (int) substr($validatedTransaction['pickup_time'],0,2);
        if($validatePickupTime < 16)
        {
            $pickupDate = date_add(new DateTime(date('Y-m-d')), date_interval_create_from_date_string('1 days'));
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }
        else
        {
            $pickupDate = date_format($pickupDate, 'Y-m-d');
        }


        $pickupDateTime = $pickupDate . ' ' . $pickupTime;

        // DELETES ORDER THAT IS NOT APPROVED
        Order::where([
            ['transaction_code', $cleanTransactionCode],
            ['stall_id', $cleanStallId],
            ['status', '<>', config('constants.ORDER_STATUS_APPROVED')]
            ])->delete();

        $transaction = array();
        $transaction['transaction_code'] = $cleanTransactionCode;
        $transaction['customer_id'] = Auth::user()->id;
        $transaction['stall_id'] = $cleanStallId;
        $transaction['preparation_time'] = $validatedTransaction['preparation_time'];
        $transaction['pickup_time'] = $pickupDateTime;
        $transaction['total_price'] = $validatedTransaction['total_price'];
        $transaction['order_type'] = $request->get('order_type_' . $cleanTransactionCode . "_" . $cleanStallId);
//        $transaction['status'] = config('constants.TRANSACTION_STATUS_PAID');
        $transaction['status'] = config('constants.TRANSACTION_STATUS_PENDING');

        $createdTrans = Transaction::create($transaction);

        $transactionPending = array();
        $transactionPending['trans_id'] = base64_encode($createdTrans->id);
        $transactionPending['transaction_code'] = base64_encode($cleanTransactionCode);
        $transactionPending['total_price'] = base64_encode($validatedTransaction['total_price']);
        $transactionPending['product_ids'] = base64_encode($request['productIds']);
        $transactionPending['quantities'] = base64_encode($request['quantities']);
        $transactionPending['stall_id'] = base64_encode($createdTrans->stall_id);

        session(['transactionPending' => $transactionPending]);
        return redirect()->action(
            'PaymayaAPIController@store'
        );

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
     * Update transaction detail (order type, pickup time).
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
            'pickup_time' => 'required|date_format:h:i A|after:04:00 PM',
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


        if(Auth::user()->isOwner())
        {
            Transaction::where([
                ['id', $id],
                ['transaction_code', $cleanTransactionCode],
                ['stall_id', Auth::user()->id]
            ])
                ->update($updateTransaction);

            // STORE NOTIFICATION
            $notification           = array();
            $notification['to']     = base64_decode($request['customer_id']);
            $notification['action'] = Auth::user()->name . " updates the Orders with Transaction Code " . $cleanTransactionCode . ".";
            Helpers::storeNotification($notification);

        }

        if(Auth::user()->isCustomer())
        {
            $updateTransaction['order_type']   = $request->get('order_type_' . $cleanTransactionCode . "_" . $cleanStallId);
            Transaction::where([
                ['id', $id],
                ['transaction_code', $cleanTransactionCode],
                ['customer_id', Auth::user()->id]
            ])
                ->update($updateTransaction);

            // STORE NOTIFICATION
            $notification           = array();
            $notification['to']     = $cleanStallId;
            $notification['action'] ="User [" . Auth::user()->id . "] "  . Auth::user()->name .  " updates the Orders with Transaction Code " . $cleanTransactionCode.".";
            Helpers::storeNotification($notification);

        }

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


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $id = base64_decode($id);

        $validatedTransaction = $this->validate($request,[
            'status' => 'required',
            'transaction_code' => 'required'
        ]);

        $data = array(
            'status' =>  base64_decode($validatedTransaction['status'])
        );


        Transaction::where([
                     ['id', $id]
                 ])
            ->update($data);

        // STORE NOTIFICATION
        $notification           = array();
        $notification['to']     = base64_decode($request['customer_id']);
        $notification['action'] = "[".Auth::user()->name."] The Orders with Transaction Code " . base64_decode($validatedTransaction['transaction_code']) . " is now " . base64_decode($validatedTransaction['status']) . ".";
        Helpers::storeNotification($notification);

        return redirect('orders')->with('success','Transaction has been updated.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  string  $transactionCode
     * @param  int  $stallId
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($transactionCode, $stallId)
    {

        $transactionCode = base64_decode($transactionCode);
        $stallId = base64_decode($stallId);

        //GET STALL DETAILS
        $stallName = User::find($stallId);

        $receiptTemp = DB::table('transactions')
            ->join('orders', function ($join)
            {
                $join->on('transactions.transaction_code', 'orders.transaction_code');
                $join->on('transactions.stall_id', 'orders.stall_id');
            })
            ->join('menus', 'orders.product_id', 'menus.id')
            ->select('transactions.*', 'orders.quantity', 'menus.name')
            ->where('transactions.transaction_code', $transactionCode)
            ->where('transactions.stall_id', $stallId)
//            ->where('transactions.customer_id', Auth::user()->id)
            ->where('transactions.status', '<>', config('constants.TRANSACTION_STATUS_PENDING'))
            ->get();


        $receipt = array();

        $receipt[] = (array) $receiptTemp[0];

        $stallImage = StallImage::where('user_id', $stallId)->get()->toArray();

        $data   = array();
        $data['receipt']    = $receipt;
        $data['stallName']  = $stallName['name'];
        $data['stallImage'] = $stallImage[0]['image_path'];

        $pdf = PDF::loadView('receipt.receipt', compact('data'));

        return $pdf->download('receipt_'. $transactionCode . '_' . $stallId . '.pdf');

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateViewFlag(Request $request, $id)
    {
        $id = base64_decode($id);

        $data = array();
        if(Auth::user()->isOwner())
        {
            $data = array(
                'stall_view' => config('constants.ENUM_NO')
            );
        }

        if(Auth::user()->isCustomer())
        {
            $data = array(
                'customer_view' => config('constants.ENUM_NO')
            );
        }

        Transaction::where([
            ['id', $id]
        ])
            ->update($data);

        return redirect('orders')->with('success','Transaction has been deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymayaUser;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use PayMaya\PayMayaSDK;
use PayMaya\Model\Checkout\Item;
use PayMaya\Model\Checkout\ItemAmount;
use PayMaya\Model\Checkout\ItemAmountDetails;
use PayMaya\API\Checkout;
use App\Menu;
use App\Transaction;

class PaymayaAPIController extends Controller
{

    private $itemCheckout;

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->processPaymaya();
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

    public function processPaymaya() {

//        $itemCheckout->buyer = $user->buyerInfo();

        $publicApiKey = env('PAYMAYA_PUBLIC_API_KEY');
        $privateApiKey = env('PAYMAYA_SECRET_API_KEY');
        $apiEnvironment = env('PAYMAYA_API_ENV');
        PayMayaSDK::getInstance()->initCheckout(
            $publicApiKey,
            $privateApiKey,
            $apiEnvironment
        );

        //Checkout
        $itemCheckout = new Checkout();

        $transactionPending = session('transactionPending');


// Item
        $itemAmountDetails = new ItemAmountDetails();
        $itemAmountDetails->shippingFee = "";
        $itemAmountDetails->tax = "";
        $itemAmountDetails->subtotal = "50.00";

        $itemAmountTotal = new ItemAmount();
        $itemAmountTotal->currency = "PHP";
        $itemAmountTotal->value = base64_decode($transactionPending['total_price']);
//        $item = new Item();
//        $item->name = "Leather Belt";
//        $item->code = "pm_belt";
//        $item->description = "Medium-sized belt made from authentic leather";
//        $item->quantity = "1";
//        $item->amount = $itemAmount;
//        $item->totalAmount = $itemAmount;

        $decodedPID = base64_decode($transactionPending['product_ids']);
        $decodedQuantities = base64_decode($transactionPending['quantities']);

        $productIds = (strpos($decodedPID, ',') > 0) ? explode(',', $decodedPID) : array($decodedPID);

        $quantities = (strpos($decodedQuantities, ',') > 0) ? explode(',', $decodedQuantities) : array($decodedQuantities);

        $items = array();
        for ($i = 0; $i < count($productIds); $i++) {
//            echo $productIds[$i];
            $menu = Menu::find($productIds[$i]);

            $itemAmount = new ItemAmount();
            $itemAmount->currency = "PHP";
            $itemAmount->value = $menu['price'];

            $item = new Item();
            $item->name = $menu['name'];
            $item->quantity = $quantities[$i];
            $item->amount = $itemAmount;
            $item->totalAmount = $itemAmount;
            $items[$i] = $item;
        }

        $itemCheckout->items = $items;
        $itemCheckout->totalAmount = $itemAmountTotal;
        $itemCheckout->requestReferenceNumber = "123456789";
//        $itemCheckout->redirectUrl = array(
//            "success" => "https://shop.com/success",
//            "failure" => "https://shop.com/failure",
//            "cancel" => "https://shop.com/cancel"
//        );
        $itemCheckout->redirectUrl = array(
            "success" => "http://127.0.0.1:8000/success",
            "failure" => "http://127.0.0.1:8000/testpaymaya",
            "cancel" => "http://127.0.0.1:8000/testpaymaya"
        );
        $itemCheckout->execute();


//        echo $itemCheckout->id .'<br>'; // Checkout ID
//        echo @$itemCheckout->url .'<br>'; // Checkout URL
//
//        echo '<pre>';
//        print_r(@$itemCheckout->retrieve());


//        echo session('checkoutId');
//        die;
        session(['checkoutId' => $itemCheckout->id]);
        Redirect::to(@$itemCheckout->url)->send();

    }

    public function success() {
        $publicApiKey = env('PAYMAYA_PUBLIC_API_KEY');
        $privateApiKey = env('PAYMAYA_SECRET_API_KEY');
        $apiEnvironment = env('PAYMAYA_API_ENV');
        PayMayaSDK::getInstance()->initCheckout(
            $publicApiKey,
            $privateApiKey,
            $apiEnvironment
        );

        //Checkout
        $itemCheckout = new Checkout();

        $transactionPending = session('transactionPending');
        $itemCheckout->id = session('checkoutId');
        $retrievedCheckout = json_decode(@$itemCheckout->retrieve(), true);
        $status = $retrievedCheckout['status'];
        print_r($retrievedCheckout);

        if($status === 'COMPLETED') {
            $transId = base64_decode($transactionPending['trans_id']);

            $transaction = Transaction::find($transId);
            $transaction['status'] = config('constants.TRANSACTION_STATUS_PAID');
            $transaction['paymaya_receipt_number'] = $retrievedCheckout['receiptNumber'];
            $transaction['paymaya_transaction_reference_number'] = $retrievedCheckout['transactionReferenceNumber'];
            $transaction->save();

            return redirect('orders')->with('success','Transaction ' . $transaction['transaction_code'] . ' approved items has been paid.')->send();
        }

    }
}

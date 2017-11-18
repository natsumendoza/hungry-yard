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

class PaymayaAPIController extends Controller
{

    private $itemCheckout;

    public function __construct()
    {
        $publicApiKey = env('PAYMAYA_PUBLIC_API_KEY');
        $privateApiKey = env('PAYMAYA_SECRET_API_KEY');
        $apiEnvironment = env('PAYMAYA_API_ENV');
        PayMayaSDK::getInstance()->initCheckout(
            $publicApiKey,
            $privateApiKey,
            $apiEnvironment
        );

        //Checkout
        $this->itemCheckout = new Checkout();
        $user = new PaymayaUser();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo '<pre>';
        $this->itemCheckout->id = session('checkoutId');
        echo '<pre>';
        print_r(@$this->itemCheckout->retrieve());
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

//        $itemCheckout->buyer = $user->buyerInfo();




// Item
        $itemAmountDetails = new ItemAmountDetails();
        $itemAmountDetails->shippingFee = "";
        $itemAmountDetails->tax = "";
        $itemAmountDetails->subtotal = "50.00";
        $itemAmount = new ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = base64_decode($request['total_price']);
//        $item = new Item();
//        $item->name = "Leather Belt";
//        $item->code = "pm_belt";
//        $item->description = "Medium-sized belt made from authentic leather";
//        $item->quantity = "1";
//        $item->amount = $itemAmount;
//        $item->totalAmount = $itemAmount;

        $productIds = (strpos($request['productIds'], ',') > 0) ? explode(',', $request['productIds']) : array($request['productIds']);

        $quantities = (strpos($request['quantities'], ',') > 0) ? explode(',', $request['quantities']) : array($request['quantities']);

        $items = array();
        for ($i = 0; $i < count($productIds); $i++) {
//            echo $productIds[$i];
            $menu = Menu::find($productIds[$i]);
            $item = new Item();
            $item->name = $menu['name'];
            $item->quantity = $quantities[$i];
            $item->amount = $itemAmount;
            $item->totalAmount = $itemAmount;
            $items[$i] = $item;
        }

        $this->itemCheckout->items = array($item);
        $this->itemCheckout->totalAmount = $itemAmount;
        $this->itemCheckout->requestReferenceNumber = "123456789";
//        $itemCheckout->redirectUrl = array(
//            "success" => "https://shop.com/success",
//            "failure" => "https://shop.com/failure",
//            "cancel" => "https://shop.com/cancel"
//        );
        $this->itemCheckout->redirectUrl = array(
            "success" => "http://127.0.0.1:8000/success",
            "failure" => "http://127.0.0.1:8000/testpaymaya",
            "cancel" => "http://127.0.0.1:8000/testpaymaya"
        );
        $this->itemCheckout->execute();


//        echo $itemCheckout->id .'<br>'; // Checkout ID
//        echo $itemCheckout->url .'<br>'; // Checkout URL
//
//        echo '<pre>';
//        print_r(@$itemCheckout->retrieve());

        session(['checkoutId' => $this->itemCheckout->id]);
//        echo session('checkoutId');
//        die;

        return Redirect::to($this->itemCheckout->url);
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

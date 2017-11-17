<?php

namespace App\Http\Controllers;

use App\PaymayaUser;
use Illuminate\Http\Request;
use PayMaya\PayMayaSDK;
use PayMaya\Model\Checkout\Item;
use PayMaya\Model\Checkout\ItemAmount;
use PayMaya\Model\Checkout\ItemAmountDetails;
use PayMaya\API\Checkout;
use Illuminate\Foundation\Auth\User;

class PaymayaController extends Controller
{
    public function index() {
        $publicApiKey = env('PAYMAYA_PUBLIC_API_KEY');
        $privateApiKey = env('PAYMAYA_SECRET_API_KEY');
        $apiEnvironment = env('PAYMAYA_API_ENV');
        PayMayaSDK::getInstance()->initCheckout(
            $publicApiKey,
            $privateApiKey,
            $apiEnvironment
        );

        // Checkout
        $itemCheckout = new Checkout();
        $user = new PaymayaUser();
        $itemCheckout->buyer = $user->buyerInfo();

// Item
        $itemAmountDetails = new ItemAmountDetails();
        $itemAmountDetails->shippingFee = "14.00";
        $itemAmountDetails->tax = "5.00";
        $itemAmountDetails->subtotal = "50.00";
        $itemAmount = new ItemAmount();
        $itemAmount->currency = "PHP";
        $itemAmount->value = "69.00";
        $itemAmount->details = $itemAmountDetails;
        $item = new Item();
        $item->name = "Leather Belt";
        $item->code = "pm_belt";
        $item->description = "Medium-sized belt made from authentic leather";
        $item->quantity = "1";
        $item->amount = $itemAmount;
        $item->totalAmount = $itemAmount;

        $itemCheckout->items = array($item);
        $itemCheckout->totalAmount = $itemAmount;
        $itemCheckout->requestReferenceNumber = "123456789";
        $itemCheckout->redirectUrl = array(
            "success" => "https://shop.com/success",
            "failure" => "https://shop.com/failure",
            "cancel" => "https://shop.com/cancel"
        );
        $itemCheckout->execute();

        echo $itemCheckout->id .'<br>'; // Checkout ID
        echo $itemCheckout->url; // Checkout URL
    }
}

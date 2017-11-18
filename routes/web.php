<?php

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Menu;
use App\StallImage;
use App\Gallery;
use App\Event;
use App\Order;
use PayMaya\API\Webhook;
use PayMaya\PayMayaSDK;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/testpaymaya', 'PaymayaController@index');

    Route::get('/success', 'PaymayaAPIController@success');
    //function () {
//    return view('paymaya');
//    print_r($request->getContent());
//    $publicApiKey = env('PAYMAYA_PUBLIC_API_KEY');
//    $privateApiKey = env('PAYMAYA_SECRET_API_KEY');
//    $apiEnvironment = env('PAYMAYA_API_ENV');
//    PayMayaSDK::getInstance()->initCheckout(
//        $publicApiKey,
//        $privateApiKey,
//        $apiEnvironment
//    );
//
//    $successWebhook = new Webhook();
//    $successWebhook->name = Webhook::CHECKOUT_SUCCESS;
//    $successWebhook->callbackUrl = "http://localhost:8000/success";
//    $successWebhook->register();
//    $failureWebhook = new Webhook();
//    $failureWebhook->name = Webhook::CHECKOUT_FAILURE;
//    $failureWebhook->callbackUrl = "http://shop.someserver.com/failure";
//    $failureWebhook->register();
//    $webhooks = Webhook::retrieve();
//    print_r($webhooks);
//    $webhook = $webhooks[0];
//    $webhook->callbackUrl .= "Updated";
//    $webhook->update();
//    print_r(Webhook::retrieve());
//    $webhookCopy = clone $webhook;
//    echo $webhook->delete();
//    print_r(Webhook::retrieve());
//    $webhookCopy->register();
//    print_r(Webhook::retrieve());
//}
//);

    Route::get('/', function () {
        $stallsWithImage = DB::table('users')
            ->join('stall_images', 'users.id', 'stall_images.user_id')
            ->select('users.id', 'users.name', 'users.name', 'users.email', 'stall_images.image_path')
            ->where('users.role_id', '2')
            ->get();

        $gallery = Gallery::orderBy('created_at', 'desc')->take(5)->get();
        $events = Event::orderBy('date', 'desc')->take(5)->get();

        $stalls = array();
        foreach ($stallsWithImage as $stallWithImage)
        {
            $stallWithImage                   = (array) $stallWithImage;
            $stalls[$stallWithImage['id']] = $stallWithImage;
        }

        if (!Auth::guest())
        {
            if (Auth::user()->isCustomer()) {
                $cartItems = Order::where('customer_id', Auth::user()->id)
                    ->where('status', config('constants.ORDER_STATUS_CART'))
                    ->get()->toArray();

                if (!empty($cartItems)) {
                    Session::put('cartSize', count($cartItems));
                    if (!(\Session::has('transactionCode'))) {
                        Session::put('transactionCode', $cartItems[0]['transaction_code']);
                    }
                }
            }
        }


        return view('index')->with(array('stalls' => $stalls, 'gallery' => $gallery, 'events' => $events));
    });

    Route::get('/stalls/{id}', function ($id) {

        $id = base64_decode($id);

        $stallImageTemp = DB::table('stall_images')
            ->join('users', 'stall_images.user_id', 'users.id')
            ->select('stall_images.image_path', 'users.id as stall_id', 'users.name as stall_name')
            ->where('user_id', $id)->get();


        $stallImage = array();
        foreach($stallImageTemp as $stall)
        {
            $stallImage[] = (array) $stall;
        }

        $menus = Menu::all()->where('stall_id', $id)->toArray();
        $stall = User::find($id);

        return view('stalls/stall')->with(array('menus' => $menus, 'stallImage' => $stallImage[0], 'stall' => $stall));
    });

    Route::resource('stall', 'StallController');

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('menu','MenuController');
    Route::delete('orders/transaction/{transactionCode}', 'OrderController@destroyByTransactionCode');
    Route::patch('orders/transaction/{transactionCode}', 'OrderController@updateByTransactionCode');
    Route::get('orders/{userId}', 'OrderController@showByUserId');
    Route::resource('orders', 'OrderController');
    Route::resource('cart', 'CartController');
    Route::patch('transactions/status/{id}', 'TransactionController@updateStatus');
    Route::get('receipt/{transactionCode}/{stallId}', 'TransactionController@downloadReceipt');
    Route::resource('transactions', 'TransactionController');
    Route::resource('event', 'EventController');
    Route::resource('gallery', 'GalleryController');
    Route::resource('customer', 'CustomerController');
    Route::resource('paymaya', 'PaymayaAPIController');
    Route::resource('notifications', 'NotificationController');
});




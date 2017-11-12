<?php

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Menu;
use App\StallImage;

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

Route::get('/', function () {
    $stallsWithImage = DB::table('users')
        ->join('stall_images', 'users.id', 'stall_images.user_id')
        ->select('users.id', 'users.name', 'users.name', 'users.email', 'stall_images.image_path')
        ->where('users.role_id', '2')
        ->get();

    $stalls = array();
    foreach ($stallsWithImage as $stallWithImage)
    {
        $stallWithImage                   = (array) $stallWithImage;
        $stalls[$stallWithImage['id']] = $stallWithImage;
    }

    return view('index')->with(array('stalls' => $stalls));
});

Route::get('/stalls/{id}', function ($id) {
    $stallImage = StallImage::where('user_id', $id)->get();

    $menus = Menu::all()->where('stall_id', $id)->toArray();

    return view('stalls/stall')->with(array('menus' => $menus, 'stallImage' => $stallImage[0]));
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
Route::resource('transactions', 'TransactionController');

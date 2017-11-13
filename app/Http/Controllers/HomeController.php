<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session;
use Illuminate\Support\Facades\Auth;
use App\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.if(!(\Session::has('transactionCode'))) :
            //SET $transactionCode
                $transactionCode = date("dmy") . Auth::user()->id . date("siH");
            Session::put('transactionCode', $transactionCode);

        else:
            $transactionCode = Session::get('transactionCode');
        end
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('home');
    }
}

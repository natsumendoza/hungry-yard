<!-- orderList.blade.php -->
@extends('layouts.layout')
@section('content')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
</head>
<body>
<div id="about">
    <div class="container container-height">
        <br />
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div><br />
        @endif
        @php
            $ctr=0;
        @endphp
        @if(count($data['transactions'])>0)
            @foreach($data['transactions'] as $transactionCode => $user)
                @if(ISSET($data['transactionList'][$transactionCode]))
                    @if($data['transactionList'][$transactionCode]['stall_view'] == config('constants.ENUM_YES'))
                        @php
                            $ctr++;
                        @endphp
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th colspan="9">Transaction code: <i>{{$transactionCode}}</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ordered By: <i>{{$user}}</i></th>
                            </tr>
                            <tr style="background-color: #D2D4DC">
                                <th style="text-align: center">ID</th>
                                <th style="text-align: center">Transaction Code</th>
                                <th style="text-align: center" colspan="2">Product ID</th>
                                <th style="text-align: center">Quantity</th>
                                <th style="text-align: center">Total Price</th>
                                <th style="text-align: center">Comment</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($data['orderList'])>0)
                                @foreach($data['orderList'] as $order)
                                    @if( $transactionCode == $order['transaction_code'] )
                                        @php
                                            $color = '';

                                            if($order['status'] == config('constants.ORDER_STATUS_APPROVED')):
                                                $color = '#7FBF7F';
                                            elseif ($order['status'] == config('constants.ORDER_STATUS_CANCELLED')):
                                                $color = '#ff7f7f';
                                            endif

                                        @endphp
                                        <tr style="background-color: {{$color}}">
                                            <td style="text-align: center;">{{$order['id']}}</td>
                                            <td>{{$order['transaction_code']}}</td>
                                            <td>{{$order['product_name']}}</td>
                                            <td><a href="{{asset('images/menu/'.$order['product_image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="50" width="70" src="{{asset('images/menu/'.$order['product_image'])}}"></a></td>
                                            <td style="text-align: center;">{{$order['quantity']}}</td>
                                            <td style="text-align: right;">{{number_format($order['quantity'] * $order['product_price'], 2)}}</td>
                                            <td style="text-align: center; max-width: 300px;" >{{$order['comment']}}</td>
                                            <td style="text-align: center;">{{$order['status']}}</td>
                                            <td style="text-align: center;">
                                                <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                    {{csrf_field()}}
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                    <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                    @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                                                        <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                            {{csrf_field()}}
                                                            <input name="_method" type="hidden" value="PATCH">
                                                            <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                            <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                            <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_APPROVED'))}}">
                                                            <button class="btn btn-small btn-success" type="submit">Approve</button>
                                                        </form>
                                                    @elseif($order['status'] == config('constants.ORDER_STATUS_PAID'))
                                                        <input name="status" type="hidden" value="{{base64_encode(config('ORDER_STATUS_SERVED'))}}">
                                                        <button class="btn btn-small btn-success" type="submit">Serve</button>
                                                    @elseif( $order['status'] == config('constants.ORDER_STATUS_APPROVED') OR $order['status'] == config('constants.ORDER_STATUS_CANCELLED') OR $order['status'] == config('constants.ORDER_STATUS_SERVED') )
                                                        <i>No action available</i>
                                                    @endif

                                                    @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                                                        <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                            {{csrf_field()}}
                                                            <input name="_method" type="hidden" value="PATCH">
                                                            <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                            <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                            <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_CANCELLED'))}}">
                                                            <button class="btn btn-small btn-danger" type="submit">Cancel</button>
                                                        </form>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;" colspan="9"><i>No item available.</i></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    @endif
                @else
                    @php
                        $ctr++;
                    @endphp
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th colspan="9">Transaction code: <i>{{$transactionCode}}</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ordered By: <i>{{$user}}</i></th>
                        </tr>
                        <tr style="background-color: #D2D4DC">
                            <th style="text-align: center">ID</th>
                            <th style="text-align: center">Transaction Code</th>
                            <th style="text-align: center" colspan="2">Product ID</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Total Price</th>
                            <th style="text-align: center">Comment</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(count($data['orderList'])>0)
                            @foreach($data['orderList'] as $order)
                                @if( $transactionCode == $order['transaction_code'] )
                                    @php
                                        $color = '';

                                        if($order['status'] == config('constants.ORDER_STATUS_APPROVED')):
                                            $color = '#7FBF7F';
                                        elseif ($order['status'] == config('constants.ORDER_STATUS_CANCELLED')):
                                            $color = '#ff7f7f';
                                        endif

                                    @endphp
                                    <tr style="background-color: {{$color}}">
                                        <td style="text-align: center;">{{$order['id']}}</td>
                                        <td>{{$order['transaction_code']}}</td>
                                        <td>{{$order['product_name']}}</td>
                                        <td><a href="{{asset('images/menu/'.$order['product_image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="50" width="70" src="{{asset('images/menu/'.$order['product_image'])}}"></a></td>
                                        <td style="text-align: center;">{{$order['quantity']}}</td>
                                        <td style="text-align: right;">{{number_format($order['quantity'] * $order['product_price'], 2)}}</td>
                                        <td style="text-align: center; max-width: 300px;" >{{$order['comment']}}</td>
                                        <td style="text-align: center;">{{$order['status']}}</td>
                                        <td style="text-align: center;">
                                            <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                {{csrf_field()}}
                                                <input name="_method" type="hidden" value="PATCH">
                                                <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                                                    <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                        {{csrf_field()}}
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                        <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                        <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_APPROVED'))}}">
                                                        <button class="btn btn-small btn-success" type="submit">Approve</button>
                                                    </form>
                                                @elseif($order['status'] == config('constants.ORDER_STATUS_PAID'))
                                                    <input name="status" type="hidden" value="{{base64_encode(config('ORDER_STATUS_SERVED'))}}">
                                                    <button class="btn btn-small btn-success" type="submit">Serve</button>
                                                @elseif( $order['status'] == config('constants.ORDER_STATUS_APPROVED') OR $order['status'] == config('constants.ORDER_STATUS_CANCELLED') OR $order['status'] == config('constants.ORDER_STATUS_SERVED') )
                                                    <i>No action available</i>
                                                @endif

                                                @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                                                    <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                                                        {{csrf_field()}}
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                                                        <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($user)}}">
                                                        <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_CANCELLED'))}}">
                                                        <button class="btn btn-small btn-danger" type="submit">Cancel</button>
                                                    </form>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td style="text-align: center;" colspan="9"><i>No item available.</i></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                @endif
                @if(ISSET($data['transactionList'][$transactionCode]) AND !EMPTY($data['transactionList'][$transactionCode]) AND $data['transactionList'][$transactionCode]['stall_view'] == config('constants.ENUM_YES'))

                    <table class="table table-responsive" style="width:40%; float: right;">
                        <form class="form-horizontal" method="POST" action="{{action('TransactionController@update', base64_encode($data['transactionList'][$transactionCode]['id']))}}">
                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="PATCH">
                            <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
                            <input type="hidden" name="stall_id" id="stall_id" value="{{base64_encode(Auth::user()->id)}}">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($data['transactionList'][$transactionCode]['customer_id'])}}">
                            <input type="hidden" name="status" id="status" value="{{base64_encode($data['transactionList'][$transactionCode]['status'])}}">
                            <thead>
                            <tr>
                                <th colspan="2">Information (Note: For <i>approved</i> orders only.)</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td style="width: 330px;">Transaction code: </td>
                                <td style="width: 380px;"><b>{{$transactionCode}}</b></td>
                            </tr>

                            <tr>
                                @php
                                    $hasError = FALSE;
                                    $errFontColor = "";
                                    $fontWeight = "";
                                    $pickupDateTime = "";
                                    $pickupDate = "";
                                    $pickupTime = "";

                                    if($errors->has('pickup_time'))
                                    {
                                        $hasError = TRUE;
                                        $errFontColor = "#a94442";
                                        $fontWeight = "font-weight: bold;";
                                    }

                                    if(ISSET($data['transactionList'][$transactionCode]['pickup_time']) AND !EMPTY($data['transactionList'][$transactionCode]['pickup_time']))
                                        {
                                            // SET PICKUP DATETIME
                                            $pickupDateTime = strtotime($data['transactionList'][$transactionCode]['pickup_time']);
                                            $pickupDate = date("m/d/Y", $pickupDateTime);
                                            $pickupTime = date("H:i", $pickupDateTime);
                                        }
                                @endphp
                                <td style="color: {{$errFontColor}}; {{$fontWeight}}">Pickup Time: </td>
                                <td>
                                    <input type="time" class="time_{{$transactionCode}}" id="pickup_time" name="pickup_time" class="form-control" value="{{$pickupTime}}" min="16:00" max="24:00" required autofocus> &nbsp;&nbsp;<button class="btn btn-warning">Update</button>
                                    @if ($hasError)
                                        <br>
                                        <span class="help-blocker">
                        <strong style="color: {{$errFontColor}};">{{ str_replace("date", "time", $errors->first('pickup_time')) }}</strong>
                    </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Total price: </td>
                                <td>{{$data['transactionList'][$transactionCode]['total_price']}}</td>
                            </tr>
                            <tr>
                                <td>Order Type: </td>
                                <td>{{$data['transactionList'][$transactionCode]['order_type']}}</td>
                            </tr>

                        </form>
                        <tr>
                            <td>
                                @if(true)
                                    <input name="_method" type="hidden" value="PATCH">
                                    <a href="{{url('receipt/'.base64_encode($transactionCode).'/'.base64_encode(Auth::user()->id))}}">Download Receipt</a>
                                @endif
                            </td>
                            <td>
                                <form class="form-horizontal" method="POST" action="{{action('TransactionController@updateStatus', base64_encode($data['transactionList'][$transactionCode]['id']))}}">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="PATCH">
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{base64_encode($data['transactionList'][$transactionCode]['customer_id'])}}">
                                    <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($data['transactionList'][$transactionCode]['transaction_code'])}}">

                                    @if($data['transactionList'][$transactionCode]['status'] == config('constants.TRANSACTION_STATUS_PAID'))
                                        <input type="hidden" name="status" id="status" value="{{base64_encode(config('constants.TRANSACTION_STATUS_READY'))}}">
                                        <button type="submit" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Ready to serve</button>
                                    @elseif($data['transactionList'][$transactionCode]['status'] == config('constants.TRANSACTION_STATUS_READY'))
                                        <input type="hidden" name="status" id="status" value="{{base64_encode(config('constants.TRANSACTION_STATUS_SERVED'))}}">
                                        <button type="submit" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Serve</button>
                                    @elseif($data['transactionList'][$transactionCode]['status'] == config('constants.TRANSACTION_STATUS_SERVED'))
                                        <p style="font-size: 20px; text-align: center; width: 175px; float: right;"><i><b>Served</b></i></p>
                                    @endif
                                </form>
                                @if($data['transactionList'][$transactionCode]['status'] == config('constants.TRANSACTION_STATUS_SERVED'))
                                    <form action="{{action('TransactionController@updateViewFlag', base64_encode($data['transactionList'][$transactionCode]['id']))}}" method="post">
                                        {{csrf_field()}}
                                        <input name="_method" type="hidden" value="PATCH">
                                        <button type="submit" class="btn btn-danger" style="width:175px; float: right; margin-top: 5px;">Delete Transaction</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @endif

                <br />
                <br />

            @endforeach

        @else
            <table class="table table-responsive">
                <thead>
                <tr style="background-color: #D2D4DC">
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Transaction Code</th>
                    <th style="text-align: center" colspan="2">Product ID</th>
                    <th style="text-align: center">Quantity</th>
                    <th style="text-align: center">Total Price</th>
                    <th style="text-align: center">Comment</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center;" colspan="9"><i>No item available.</i></td>
                </tr>
                </tbody>
            </table>

        @endif

        @if( count($data['transactions'])>0 AND $ctr==0)
            <table class="table table-striped">
                <thead>
                <tr style="background-color: #D2D4DC">
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Transaction Code</th>
                    <th style="text-align: center" colspan="2">Product ID</th>
                    <th style="text-align: center">Quantity</th>
                    <th style="text-align: center">Total Price</th>
                    <th style="text-align: center">Comment</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center;" colspan="9"><i>No item available.</i></td>
                </tr>
                </tbody>
            </table>
        @endif


    </div>
</div>
</body>
</html>
@endsection
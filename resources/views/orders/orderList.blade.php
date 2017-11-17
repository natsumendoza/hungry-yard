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
<div class="container container-height">
    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif

    @if(count($data['transactions'])>0)
    @foreach($data['transactions'] as $transaction_code => $user)
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="8">Transaction code: <i>{{$transaction_code}}</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ordered By: <i>{{$user}}</i></th>
            </tr>
            <tr>
                <th style="text-align: center">ID</th>
                <th style="text-align: center">Transaction Code</th>
                <th style="text-align: center" colspan="2">Product ID</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Total Price</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center">Action</th>
            </tr>
        </thead>
        <tbody>

        @if(count($data['orderList'])>0)
        @foreach($data['orderList'] as $order)
            @if( $transaction_code == $order['transaction_code'] )
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
                <td><a href="{{asset('images/menu/'.$order['product_image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="30" width="40" src="{{asset('images/menu/'.$order['product_image'])}}"></a></td>
                <td style="text-align: center;">{{$order['quantity']}}</td>
                <td style="text-align: right;">{{number_format($order['quantity'] * $order['product_price'], 2)}}</td>
                <td style="text-align: center;">{{$order['status']}}</td>
                <td style="text-align: center;">
                    @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                    <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                        {{csrf_field()}}
                        <input name="_method" type="hidden" value="PATCH">
                        <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_APPROVED'))}}">
                        <button class="btn btn-small btn-success" type="submit">Approve</button>
                    </form>
                    @elseif($order['status'] == config('constants.ORDER_STATUS_PAID'))
                        <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="PATCH">
                            <input name="status" type="hidden" value="{{base64_encode(config('ORDER_STATUS_SERVED'))}}">
                            <button class="btn btn-small btn-success" type="submit">Serve</button>
                        </form>
                    @elseif( $order['status'] == config('constants.ORDER_STATUS_APPROVED') OR $order['status'] == config('constants.ORDER_STATUS_CANCELLED') OR $order['status'] == config('constants.ORDER_STATUS_SERVED') )
                        <i>No action available</i>
                    @endif

                    @if($order['status'] == config('constants.ORDER_STATUS_PENDING'))
                    <form action="{{action('OrderController@update', base64_encode($order['id']))}}" method="post">
                        {{csrf_field()}}
                        <input name="_method" type="hidden" value="PATCH">
                        <input name="status" type="hidden" value="{{base64_encode(config('constants.ORDER_STATUS_CANCELLED'))}}">
                        <button class="btn btn-small btn-danger" type="submit">Cancel</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
        @else
            <tr>
                <td style="text-align: center;" colspan="8"><i>No item available.</i></td>
            </tr>
        @endif
        </tbody>
    </table>
    @if(ISSET($data['transactionList'][$transaction_code]) AND !EMPTY($data['transactionList'][$transaction_code]))
    <form class="form-horizontal" method="POST" action="{{action('TransactionController@update', base64_encode($transaction_code))}}">
    {{csrf_field()}}
    <table class="table" style="width:40%; float: right;">
        <thead>
        <tr>
            <th colspan="2">Information (Note: For <i>approved</i> orders only.)</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td style="width: 330px;">Transaction code: </td>
            <td style="width: 380px;"><b>{{$transaction_code}}</b></td>
        </tr>
        <tr>
            <td>Preparation Time: </td>
            <td><input type="time" class="time_{{$transaction_code}}" id="preparation_time" name="preparation_time" class="form-control" required>&nbsp;&nbsp;<button class="btn btn-warning">Update</button></td>
        </tr>
        <tr>
            <td>Pickup time: </td>
            <td>{{$data['transactionList'][$transaction_code]['order_type']}}</td>
        </tr>
        <tr>
            <td>Total price: </td>
            <td>{{$data['transactionList'][$transaction_code]['total_price']}}</td>
        </tr>
        <tr>
            <td>Order Type: </td>
            <td>{{$data['transactionList'][$transaction_code]['order_type']}}</td>
        </tr>
        <tr>
            <td colspan="2">
                @if($data['transactionList'][$transaction_code]['status'] == config('constants.TRANSACTION_STATUS_PAID'))
                    <button type="button" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Ready to serve</button>
                @elseif($data['transactionList'][$transaction_code]['status'] == config('constants.TRANSACTION_STATUS_READY'))
                    <button type="button" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Serve</button>
                @else($data['transactionList'][$transaction_code]['status'] == config('constants.TRANSACTION_STATUS_SERVED'))
                    <button type="button" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Served</button>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
    </form>
    @endif

    <br />
    <br />
    <br />
    <br />

    @endforeach

    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Transaction Code</th>
                    <th style="text-align: center" colspan="2">Product ID</th>
                    <th style="text-align: center">Quantity</th>
                    <th style="text-align: center">Total Price</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
            <tr>
            <tr>
                <td style="text-align: center;" colspan="8"><i>No item available.</i></td>
            </tr>
            </tr>
            </tbody>
        </table>

    @endif

</div>
</body>
</html>
@endsection
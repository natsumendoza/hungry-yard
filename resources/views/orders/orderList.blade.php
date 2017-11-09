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
<div class="container">
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

    {{--IF PAID--}}
    <table class="table" style="width:30%; float: right;">
        <thead>
        <tr>
            <th colspan="2">Information (Note: For <i>approved</i> orders only.)</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>Transaction code: </td>
            <td><b>Edit this.</b></td>
        </tr>
        <tr>
            <td>Preparation time: </td>
            <td>Total prep(change this) mins (Hours equivalent)</td>
        </tr>
        <tr>
            <td>Pickup time: </td>
            <td>Pickup time</td>
        </tr>
        <tr>
            <td>Total price: </td>
            <td>Total price from tasaction db</td>
        </tr>
        <tr>
            <td>Order Type: </td>
            <td>From transaction db</td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="button" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Serve</button>
            </td>
        </tr>
        </tbody>
    </table>

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
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
    @foreach($data['transactions'] as $transaction_code)
        @foreach($data['stalls'] as $stall_id => $stall_name)
        @php
            $totalPrice = 0;
            $totalPrepTime  = 0;
        @endphp
        <table class="table table-striped">
            <thead>
            <tr>
                <th colspan="7">Transaction code: <i>{{$transaction_code}} - {{$stall_name}}</i></th>
            </tr>
            <tr style="background-color: #D2D4DC">
                <th style="text-align: center">ID</th>
                <th style="text-align: center" colspan="2">Product</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Price</th>
                <th style="text-align: center">Preparation Time</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data['orderList'] as $order)
                @if($transaction_code == $order['transaction_code'] AND $stall_id == $order['stall_id'])
                @php
                    $color = '';

                    // TIME COMPUTATIONS
                    $prepTime   = $order['preparation_time'] * $order['quantity'];

                    if($order['status'] == config('constants.ORDER_STATUS_APPROVED')):
                        $totalPrice += $order['price'];
                        $totalPrepTime += $prepTime;
                        $color = '#7FBF7F';
                    elseif ($order['status'] == config('constants.ORDER_STATUS_CANCELLED')):
                        $color = '#ff7f7f';
                    endif;

                @endphp
                <tr style="background-color: {{$color}}">
                    <td>{{$order['id']}}</td>
                    <td>{{$order['name']}}</td>
                    <td><a href="{{asset('images/menu/'.$order['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="30" width="40" src="{{asset('images/menu/'.$order['image'])}}"></a></td>
                    <td style="text-align: center;">{{$order['quantity']}}</td>
                    <td style="text-align: right;">{{number_format($order['quantity'] * $order['price'], 2)}}</td>
                    <td style="text-align: right;">{{$prepTime}} mins.</td>
                    <td style="text-align: center;">{{$order['status']}}</td>
                    <td style="text-align: center;">
                        @if($order['status'] == config('constants.ORDER_STATUS_PENDING') OR $order['status'] == config('constants.ORDER_STATUS_CANCELLED'))
                        <form action="{{action('OrderController@destroy', base64_encode($order['id']))}}" method="post">
                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-small btn-danger" type="submit">Delete</button>
                        </form>
                        @else
                            <i>No action available</i>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{url('transactions')}}">
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
                    <td>{{$totalPrepTime}} mins (Hours equivalent)</td>
                </tr>
                <tr>
                    {{--IF STATUS IS PAID GET VALUE ELSE INPUT--}}
                    <td>Pickup time: </td>
                    <td><input type="time" id="pickup" name="pickup" class="form-control"></td>
                </tr>
                <tr>
                    <td>Total price: </td>
                    <td>{{number_format($totalPrice, 2)}}</td>
                </tr>
                <tr>
                    <td>Order Type: </td>
                    <td>
                        <label class="radio-inline"><input checked type="radio" name="type_<?php echo $stall_name ?>" value="{{config('constants.ORDER_TYPE_DI')}}">{{config('constants.ORDER_TYPE_DI')}}</label>
                        <label class="radio-inline"><input type="radio" name="type_<?php echo $stall_name ?>" value="{{config('constants.ORDER_TYPE_TO')}}">{{config('constants.ORDER_TYPE_TO')}}</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="button" id="pickup" class="btn btn-primary" style="width:175px; float: right;">Pay Order (via PayMaya)</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

        <br />
        <br />
        <br />
        <br />

        @endforeach
    @endforeach
    @else
    <table class="table table-striped">
        <thead>
        <tr style="background-color: #D2D4DC">
            <th style="text-align: center">ID</th>
            <th style="text-align: center" colspan="2">Product ID(change to image and name)</th>
            <th style="text-align: center">Quantity</th>
            <th style="text-align: center">Price</th>
            <th style="text-align: center">Preparation Time</th>
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
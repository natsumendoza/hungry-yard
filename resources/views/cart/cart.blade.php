<!-- orderListByTransactionCode.blade.php -->
@extends('layouts.layout')
@section('content')
@php
    $transactionCode = "";
    if(isset($cartItems) and !empty($cartItems))
    {
        $cartItem = reset($cartItems);
        $transactionCode = base64_encode($cartItem['transaction_code']);
    }
    $totalPrice = 0.00;

@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="container container-height">
    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif



    @if(!empty($cartItems))

    @endif

    <table class="table table-striped">
        <thead>
        <tr>
            <th style="text-align: center" width="5%">ID</th>
            <th style="text-align: center" width="15%">Transaction Code</th>
            <th style="text-align: center" width="25%" colspan="2">Product</th>
            <th style="text-align: center" width="5%">Quantity</th>
            <th style="text-align: center" width="10%">Price</th>
            <th style="text-align: center" width="25%">Comment</th>
            <th style="text-align: center" width="15%">Action</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($cartItems))
        @foreach($cartItems as $item)
            <tr>
                <form action="{{action('CartController@update', base64_encode($item['id']))}}" method="post">
                {{csrf_field()}}
                <input name="_method" type="hidden" value="PATCH">
                <td style="text-align: center;">{{$item['id']}}</td>
                <td>{{$item['transaction_code']}}</td>
                <td style="text-align: center;">{{$item['name']}}</td>
                <td style="text-align: center;"><a href="{{asset('images/menu/'.$item['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="70" width="100" src="{{asset('images/menu/'.$item['image'])}}"></a></td>
                <td style="text-align: center;"><input style="width: 50px;" type="number" id="quantity" name="quantity" value="{{$item['quantity']}}" size="4" pattern="[0-9]*" step="1" required autofocus></td>
                <td style="text-align: right;">{{number_format($item['quantity'] * $item['price'], 2)}}</td>
                <td style="text-align: right;"><textarea id="comment" name="comment" style="resize:none" cols="30" rows="2">{{$item['comment']}}</textarea></td>
                <td style="text-align: center;">
                    <button class="btn btn-small btn-warning" type="submit"  data-toggle="tooltip" title="Update Item">Update</button>
                </form>
                    <form action="{{action('CartController@destroy', base64_encode($item['id']))}}" method="POST">
                        {{csrf_field()}}
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-danger" type="submit" data-toggle="tooltip" title="Remove Item">Remove</button>
                    </form>
                </td>

            </tr>
        @endforeach
        <tr>
            <td style="text-align: left;" colspan="2">
                <a href="{{url('')}}" class="btn btn-primary" style="width: 150px;">Add more to cart</a>
            </td>
            <td style="text-align: left;" colspan="2">
                <form action="{{action('OrderController@updateByTransactionCode', $transactionCode)}}" method="POST">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PATCH">
                    <button type="submit" class="btn btn-info" style="width: 130px;">Place your order</button>
                </form>
            </td>
            <td style="text-align: right;" colspan="4">
                <form action="{{action('OrderController@destroyByTransactionCode', $transactionCode)}}" method="POST">
                    {{csrf_field()}}
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit">Empty Cart</button>
                </form>
            </td>
        </tr>
        @else
        <tr>
            <td colspan="8" style="text-align: center">Cart is empty.</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: left;">
                <a href="{{url('')}}" class="btn btn-primary" style="width: 150px;">Continue Order</a>
            </td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
</body>
</html>
@endsection
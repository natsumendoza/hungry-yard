<!-- orderList.blade.php -->
@extends('layouts.layout')
@section('content')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="container container-height">
    @php
        $arrPrepTotalTime = array();
        $arrCreatedAt = array();
        $productIds = array();
        $quantities = array();
    @endphp

    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif

    @if(count($data['transactions'])>0)
    @foreach($data['transactions'] as $transactionCode)
        @foreach($data['stalls'] as $stallId => $stallName)
        @if(ISSET($data['orderList'][$transactionCode][$stallId]) AND !EMPTY($data['orderList'][$transactionCode][$stallId]))
        @php
            $totalPrice = 0;
            $totalPrepTime  = 0;
            $showPaymaya = FALSE;
            $buttonLabel = "Pay Order (via PayMaya)";
        @endphp
        <table class="table table-striped">
            <thead>
            <tr>
                <th colspan="9">Transaction code: <i>{{$transactionCode}} - {{$stallName}}</i></th>
            </tr>
            <tr style="background-color: #D2D4DC">
                <th style="text-align: center">ID</th>
                <th style="text-align: center" colspan="2">Product</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Price</th>
                <th style="text-align: center">Preparation Time</th>
                <th style="text-align: center">Comment</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data['orderList'][$transactionCode][$stallId] as $order)
                @php
                    $color = '';

                    // TIME COMPUTATIONS
                    $prepTime   = $order['preparation_time'] * $order['quantity'];
                    if($order['status'] == config('constants.ORDER_STATUS_APPROVED')):
                        $totalPrice += ($order['price'] * $order['quantity']);
                        $totalPrepTime += $prepTime;
                        $color = '#7FBF7F';
                        $showPaymaya = TRUE;

                        $productIds[] = $order['product_id'];
                        $quantities[] = $order['quantity'];
                    elseif ($order['status'] == config('constants.ORDER_STATUS_CANCELLED')):
                        $color = '#ff7f7f';
                    endif;

                    @endphp

                <tr style="background-color: {{$color}}">
                    <td>{{$order['id']}}</td>
                    <td>{{$order['name']}}</td>
                    <td><a href="{{asset('images/menu/'.$order['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="30" width="40" src="{{asset('images/menu/'.$order['image'])}}"></a></td>
                    <td style="text-align: center;">{{$order['quantity']}}</td>
                    <td style="text-align: right;">{{number_format($order['price'] * $order['quantity'], 2)}}</td>
                    <td style="text-align: right;">{{$prepTime}} mins.</td>
                    <td style="text-align: center; max-width: 300px;">{{$order['comment']}}</td>
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
            @endforeach
            </tbody>
        </table>


        @php
            //TIME MINS TO HOURS
            $converted_hrs = floor($totalPrepTime / 60);
            $converted_mins = $totalPrepTime % 60;
            $action = url('transactions');
            $dineIn = "checked";
            $takeOut = "";
            $pickupDateTime = "";
            $pickupDate = "";
            $pickupTime = "";

            $update = FALSE;



            if(ISSET($data['transactionList'][$transactionCode][$stallId]) AND !EMPTY($data['transactionList'][$transactionCode][$stallId]))
            {

                $action = action('TransactionController@update', base64_encode($data['transactionList'][$transactionCode][$stallId]['id']));
                //$action = action('PaymayaController@index');
                $buttonLabel = "Update Transaction";
                $update = TRUE;

                if(ISSET($data['transactionList'][$transactionCode][$stallId]['order_type']) AND !EMPTY($data['transactionList'][$transactionCode][$stallId]['order_type']))
                {
                    if($data['transactionList'][$transactionCode][$stallId]['order_type'] == config('constants.ORDER_TYPE_DI'))
                    {
                        $dineIn = "checked";
                        $takeOut = "";
                    }
                    else
                    {
                        $dineIn = "";
                        $takeOut = "checked";
                    }
                }

                if(ISSET($data['transactionList'][$transactionCode][$stallId]['pickup_time']) AND !EMPTY($data['transactionList'][$transactionCode][$stallId]['pickup_time']))
                {
                    // SET PICKUP DATETIME
                    $pickupDateTime = strtotime($data['transactionList'][$transactionCode][$stallId]['pickup_time']);
                    $pickupDate = date("m/d/Y", $pickupDateTime);
                    $pickupTime = date("H:i", $pickupDateTime);
                }

                if(ISSET($data['transactionList'][$transactionCode][$stallId]['preparation_time']) AND !EMPTY($data['transactionList'][$transactionCode][$stallId]['preparation_time']))
                {
                    $totalPrepTime = $data['transactionList'][$transactionCode][$stallId]['preparation_time'];
                }
            }

        @endphp
        @if($showPaymaya)
       <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{$action}}">
        {{--<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{url('paymaya')}}">--}}
            {{csrf_field()}}
        <input type="hidden" name="transaction_code" id="transaction_code" value="{{base64_encode($transactionCode)}}">
        <input type="hidden" name="stall_id" id="stall_id" value="{{base64_encode($stallId)}}">
        <input type="hidden" name="total_price" id="total_price" value="{{base64_encode($totalPrice)}}">
        <input type="hidden" name="preparation_time" id="preparation_time" value="{{base64_encode($totalPrepTime)}}">
        <input type="hidden" name="productIds" value="{{implode(',', $productIds)}}">
        <input type="hidden" name="quantities" value="{{implode(',', $quantities)}}">

        <table class="table" style="width:40%; float: right;">
            <thead>
                <tr>
                    <th colspan="2">Information (Note: For <i>approved</i> orders only.)</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width: 350px;">Transaction code: </td>
                    <td style="width: 350px;"><b>{{$transactionCode}}</b></td>
                </tr>
                <tr>
                    <td>Stall: </td>
                    <td><b>{{$stallName}}</b></td>
                </tr>
                <tr>
                    <td>Preparation Time: </td>
                    <td>{{$totalPrepTime . " min/s (" . $converted_hrs . "hr " . $converted_mins ."min )"}}<br><b>Note: This value can be change the stall owner.</b></td>
                </tr>

                <tr>
                    <td>Pickup Date: </td>
                    <td><span id="pd_{{$transactionCode."_".$stallId}}">{{$pickupDate}}</span> <br><b>Note: This value will change the depends on <i>Pickup Time</i> input upon Paying.</b></td>
                </tr>
                <tr>
                    <td>Recommended Pickup Time: </td>
                    <td id="recom_{{$transactionCode."_".$stallId}}"></td>
                </tr>

                <tr>
                    @php
                        $hasError = FALSE;
                        $errFontColor = "black";
                        $fontWeight = "";

                        if($errors->has('pickup_time'))
                        {
                            $hasError = TRUE;
                            $errFontColor = "#a94442";
                            $fontWeight = "font-weight: bold;";
                        }
                    @endphp
                    <td style="color: {{$errFontColor}}; {{$fontWeight}}">Pickup Time: </td>
                    <td>
                        <input type="time" class="time_{{$transactionCode."_".$stallId}}" id="pickup_time" name="pickup_time" class="form-control" value="{{$pickupTime}}" min="16:00" max="24:00" required autofocus>
                        @if ($hasError)
                            <br>
                            <span class="help-blocker">
                                <strong style="color: {{$errFontColor}};">{{ str_replace("date", "time", $errors->first('pickup_time')) }}</strong>
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Total Price: </td>
                    <td>&#8369; {{number_format($totalPrice, 2)}}</td>
                </tr>
                <tr>
                    <td>Order Type: </td>
                    <td>
                        <label class="radio-inline"><input {{$dineIn}} type="radio" name="order_type_{{$transactionCode."_".$stallId}}" value="{{config('constants.ORDER_TYPE_DI')}}">{{config('constants.ORDER_TYPE_DI')}}</label>
                        <label class="radio-inline"><input {{$takeOut}} type="radio" name="order_type_{{$transactionCode."_".$stallId}}" value="{{config('constants.ORDER_TYPE_TO')}}">{{config('constants.ORDER_TYPE_TO')}}</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        @if($update)
                            <input name="_method" type="hidden" value="PATCH">
                            <a href="{{url('receipt/'.base64_encode($transactionCode).'/'.base64_encode($stallId))}}">Download Receipt</a>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width:190px; float: right;">{{$buttonLabel}}</button>
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
        @php
            $arrPrepTotalTime[$transactionCode][$stallId] = $totalPrepTime;
            if(ISSET($data['transactionList'][$transactionCode][$stallId]['created_at']) AND !EMPTY($data['transactionList'][$transactionCode][$stallId]['created_at']))
            {
                $arrCreatedAt[$transactionCode][$stallId] = $data['transactionList'][$transactionCode][$stallId]['created_at'];
            }
            else
            {
                $arrCreatedAt[$transactionCode][$stallId] = "";
            }
        @endphp
        @endif
        @endforeach

    @endforeach
    @else
    <table class="table table-striped">
        <thead>
        <tr style="background-color: #D2D4DC">
            <th style="text-align: center">ID</th>
            <th style="text-align: center" colspan="2">Product</th>
            <th style="text-align: center">Quantity</th>
            <th style="text-align: center">Price</th>
            <th style="text-align: center">Preparation Time</th>
            <th style="text-align: center">Comment</th>
            <th style="text-align: center">Status</th>
            <th style="text-align: center">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <tr>
            <td style="text-align: center;" colspan="9"><i>No item available.</i></td>
        </tr>
        </tr>
        </tbody>
    </table>

    @endif
</div>
</body>
<script>
    $(function() {
        var transactions = <?php echo json_encode(@$data['transactions']);?>;
        var stalls       = <?php echo json_encode(@$data['stalls']);?>;
        var arrPrepTotalTime = <?php echo json_encode($arrPrepTotalTime);?>;
        var arrCreatedAt = <?php echo json_encode($arrCreatedAt);?>;

        var pd = new Date();

        $.each( transactions, function( tran_id, tran_val ) {

            $.each( stalls, function( stall_id, stall_name ) {
                $('#pd_' + tran_id + '_' + stall_id).text((pd.getMonth()+1)+"/"+pd.getDate()+"/"+pd.getFullYear());
            });
        });

        setInterval(function() {

            $.each( transactions, function( tran_id, tran_val ) {

                $.each( stalls, function( stall_id, stall_name ) {
                    if(typeof arrPrepTotalTime[tran_id][stall_id] !== undefined)
                    {
                        var date = new Date();
                        if(arrCreatedAt[tran_id][stall_id] !== "")
                        {
                            date = new Date(arrCreatedAt[tran_id][stall_id]);
                        }

                        if(date.getHours() < 16)
                        {
                            date.setHours(16);
                            date.setMinutes(0);

                        }

                        var prep_time = parseInt(arrPrepTotalTime[tran_id][stall_id]) + parseInt(date.getMinutes());
                        var total_hour = (prep_time / 60) + date.getHours();
                        var total_mins = prep_time % 60;

                        date.setHours(total_hour);
                        date.setMinutes(total_mins+5);

                        var time = date.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});

                        $('#recom_' + tran_id + '_' + stall_id).text(time);

                    }
                });
            });

        }, 1);
    });
</script>

</html>
@endsection
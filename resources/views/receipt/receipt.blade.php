<!DOCTYPE html>

<html>

<head>
    <title>Receipt</title>
    <style>
        .border
        {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .border-top
        {
            border-top: 1px solid black;
            border-collapse: collapse;
        }
        .border-bot
        {
            border-bottom: 1px solid black;
            border-collapse: collapse;
        }

        .watermark
        {
            bottom:5px;
            right:5px;
            opacity:0.5;
            z-index:99;
            color:white;
        }
    </style>
</head>

<body>
<table style="width: 100%">
    <thead>
        <tr>
            <th style="text-align: right;" colspan="2"><img class="watermark" width="70px" height="70px" src="{{ public_path() }}/images/stall/{{$data['stallImage']}}" />
            </th>
        </tr>
        <tr>
            <th style="text-align: center;" colspan="2"><p style="font-size: 20px;">HUNGRY YARD</p></th>
        </tr>
    </thead>
</table>
<table class="border" style="width: 100%;">
    <thead>
        <tr>
            <th style="text-align: center;" class="border-bot" colspan="2"><span style="font-size: 20px;">Receipt</span></th>
        </tr>
    </thead>
    @if(count($data['receipt'])>0 AND $data['receipt'][0]['transaction_code'])

    @php
        $pickupDateTime = strtotime($data['receipt'][0]['pickup_time']);
        $pickupDate = date("m/d/Y", $pickupDateTime);
        $pickupTime = date("h:i A", $pickupDateTime);

        $createdDate = date("m/d/Y", strtotime($data['receipt'][0]['created_at']));
    @endphp
    <tbody>
        <tr>
            <td style="font-weight: bold;" class="border-bot">Stall: </td>
            <td class="border-bot" style="width:50%;">{{$data['stallName']}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Transaction Code: </td>
            <td class="border-bot">{{$data['receipt'][0]['transaction_code']}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Paymaya Receipt No: </td>
            <td class="border-bot">{{$data['receipt'][0]['paymaya_receipt_number']}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Paymaya Transaction Ref: </td>
            <td class="border-bot">{{$data['receipt'][0]['paymaya_transaction_reference_number']}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Created Date: </td>
            <td class="border-bot">{{$createdDate}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Preparation Time (min):</td>
            <td class="border-bot">{{$data['receipt'][0]['preparation_time']}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Orders:</td>
            <td style="font-weight: bold;">Quantity:</td>
        </tr>
        @foreach($data['receipt'] as $receipt)

            <tr>
                <td>{{$receipt['name']}}</td>
                <td style="text-align: center;">{{$receipt['quantity']}}</td>
            </tr>

        @endforeach
        <tr>
            <td style="font-weight: bold;" class="border-top border-bot">Pickup Time</td>
            <td class="border-top border-bot">{{$pickupTime}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Order Type</td>
            <td class="border-bot">{{$data['receipt'][0]['order_type']}}</td>
        </tr>
        <tr class="border-bot">
            <td style="font-weight: bold;" class="border-bot">Total Price</td>
            <td class="border-bot">Php {{number_format($data['receipt'][0]['total_price'], 2)}}</td>
        </tr>
    </tbody>
    @else
    <tbody>
        <tr>
            <td colspan="2"><i>No item available.</i></td>
        </tr>
    </tbody>
    @endif
</table>

</body>

</html>
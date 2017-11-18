@extends('layouts.layout')
@section('content')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Notifications</title>
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
    <table class="table table-striped" width="100%">
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Notifications</th>
        </tr>
        <tr>
            <th style="text-align: center" width="40%">Action</th>
            <th style="text-align: center" width="20%">Date and Time</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($notificationList))
            @foreach($notificationList as $notification)
                @php
                    $dateTime = date("m-d-Y H:i A", strtotime($notification['created_at']));
                @endphp
                <tr>
                    <td>{{$notification['action']}}</td>
                    <td style="text-align: center;">{{$dateTime}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2" style="text-align: center"><i>No item available.</i></td>
            </tr>
        @endif
        </tbody>
    </table>

</div>
</body>
</html>
@endsection
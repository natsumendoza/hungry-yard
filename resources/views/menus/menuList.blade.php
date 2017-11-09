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
<div class="container">
    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <div>
        <a href="{{action('MenuController@create')}}" class="btn btn-success" style="float: right;"><i class="glyphicon glyphicon-plus"></i>Add Item</a>
    </div>
    <table class="table table-striped" width="100%">
        <thead>
        <tr>
            <th style="text-align: center" width="20%">Name</th>
            <th style="text-align: center" width="25%">Item Image</th>
            <th style="text-align: center" width="15%">Price</th>
            <th style="text-align: center" width="20%">Preparation Time</th>
            <th style="text-align: center" width="20%">Action</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($menuList))
            @foreach($menuList as $menu)
                <tr>
                    <td>{{$menu['name']}}</td>
                    <td style="text-align: center;"><a href="{{asset('images/menu/'.$menu['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="80" width="120" src="{{asset('images/menu/'.$menu['image'])}}"></a></td>
                    <td style="text-align: right;">{{number_format($menu['price'], 2)}}</td>
                    <td style="text-align: center;">{{$menu['preparation_time']}} mins</td>
                    <td style="text-align: center;">
                        <a href="{{action('MenuController@edit', base64_encode($menu['id']))}}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                        <form action="{{action('MenuController@destroy', base64_encode($menu['id']))}}" method="post">
                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-small btn-danger" type="submit"><i class="glyphicon glyphicon-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" style="text-align: center"><i>No item available.</i></td>
            </tr>
        @endif
        </tbody>
    </table>

</div>
</body>
</html>
@endsection
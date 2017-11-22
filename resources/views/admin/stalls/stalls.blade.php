@extends('layouts.layout')

@section('content')


    <div id="about">

        <div class="container">
            <br />
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div><br />
            @endif
            <div>
                <a href="{{action('StallController@create')}}" class="btn btn-success" style="float: right; width:150px;"><i class="glyphicon glyphicon-plus"></i>Add New Stall</a>
            </div>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th style="text-align: center">Stall Name</th>
                    <th style="text-align: center">Email</th>
                    <th style="text-align: center">Stall Image</th>
                    <th style="text-align: center" width="20px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($users)>0)
                    @foreach($users as $user)
                        <tr>
                            <td style="text-align: center;">{{$user['name']}}</td>
                            <td style="text-align: center;">{{$user['email']}}</td>
                            <td style="text-align: center;"><img width="50" height="50" src="{{asset('images/stall/'.$user['image_path'])}}"></td>
                            {{--<td style="text-align: center;">--}}
                                {{--<form action="{{action('ProductController@destroy', $product['id'])}}" method="post">--}}
                                    {{--{{csrf_field()}}--}}
                                    {{--<input name="_method" type="hidden" value="DELETE">--}}
                                    {{--<button class="btn btn-danger" type="submit">Delete</button>--}}
                                {{--</form>--}}
                            {{--</td>--}}
                            <td style="text-align: center;">
                                <a href="{{action('StallController@edit', base64_encode($user['id']))}}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                                <form action="{{action('StallController@destroy', base64_encode($user['id']))}}" method="post">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-small btn-danger" type="submit"><i class="glyphicon glyphicon-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: center;" colspan="10">
                            There is no stall.
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="text-align: right;" colspan="10">
                        <a href="{{url('')}}" class="btn btn-default">Close</a>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>

@endsection
@extends('layouts.layout')

@section('content')


    <div id="about">

        <div class="container container-height">
            <br />
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div><br />
            @endif
            <div>
                <a href="{{action('EventController@create')}}" class="btn btn-success" style="float: right; width: 170px;"><i class="glyphicon glyphicon-plus"></i>Add New Event</a>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="text-align: center">Name</th>
                    <th style="text-align: center">Description</th>
                    <th style="text-align: center">Date</th>
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(count($eventList)>0)
                    @foreach($eventList as $event)
                        <tr>
                            <td style="text-align: center;">{{$event['name']}}</td>
                            <td style="text-align: center;">{{$event['description']}}</td>
                            <td style="text-align: center;">{{$event['date']}}</td>
                            <td style="text-align: center;"><img width="100" height="80" src="{{asset('images/event/'.$event['image_path'])}}"></td>
                            <td style="text-align: center;">
                                <a href="{{action('EventController@edit', base64_encode($event['id']))}}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                                <form action="{{action('EventController@destroy', base64_encode($event['id']))}}" method="post">
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
                            There is no event.
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
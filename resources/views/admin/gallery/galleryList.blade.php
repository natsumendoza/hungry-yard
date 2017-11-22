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
                <a href="{{action('GalleryController@create')}}" class="btn btn-success" style="float: right; width: 170px;"><i class="glyphicon glyphicon-plus"></i>Add New Image</a>
            </div>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th style="text-align: center" width="40%">Name</th>
                    <th style="text-align: center" width="40%">Image</th>
                    <th style="text-align: center" width="20%">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(count($galleryList)>0)
                    @foreach($galleryList as $gallery)
                        <tr>
                            <td style="text-align: center;">{{$gallery['name']}}</td>
                            <td style="text-align: center;"><img width="100" height="80" src="{{asset('images/gallery/'.$gallery['image_path'])}}"></td>
                            <td style="text-align: center;">
                                <a href="{{action('GalleryController@edit', base64_encode($gallery['id']))}}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                                <form action="{{action('GalleryController@destroy', base64_encode($gallery['id']))}}" method="post">
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
                            There is no image.
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
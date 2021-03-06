@extends('layouts.layout2')

@section('content')
    <div class="container container-height">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Image</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{action('GalleryController@update', base64_encode($gallery['id']))}}">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{$gallery['name']}}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label for="image" class="col-md-4 control-label">Item Image</label>

                                <div class="col-md-6">
                                    <input id="image" type="file" class="form-control" name="image" value="{{$gallery['image']}}" autofocus>

                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Current Image</label>

                                    <div class="col-md-6">
                                        <a href="{{asset('images/gallery/'.$gallery['image_path'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="80" width="120" src="{{asset('images/gallery/'.$gallery['image_path'])}}"></a>
                                    </div>
                                </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4" style="float: right;">
                                    <a href="{{action('GalleryController@index')}}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-success" style="margin-left:15px">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

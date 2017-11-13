@extends('layouts.layout2')

@section('content')
    @php
        $header = "Add New Item";
        $button_label = "Add Item";
    @endphp

    @isset($menu['id'])
        @php
            $header = "Edit Item";
            $button_label = "Update Item";
        @endphp
    @endisset
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$header}}</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{(isset($menu['id'])) ? action('MenuController@update', base64_encode($id)) : url('menu')}}">
                            {{ csrf_field() }}
                            @if(isset($menu['id']))
                                <input name="_method" type="hidden" value="PATCH">
                            @endif
                            <input name="stallId" type="hidden" value="{{Auth::user()->id}}"  />
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{@$menu['name']}}" required autofocus>

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
                                    <input id="image" type="file" class="form-control" name="image" {{(isset($menu['id'])) ? "" : "required"}} autofocus>

                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if(isset($menu['id']))
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Current Image</label>

                                    <div class="col-md-6">
                                        <a href="{{asset('images/menu/'.$menu['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="80" width="120" src="{{asset('images/menu/'.$menu['image'])}}"></a>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label for="price" class="col-md-4 control-label">Price</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" step="0.01" class="form-control" name="price" value="{{@$menu['price']}}" required autofocus>

                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('preparationTime') ? ' has-error' : '' }}">
                                <label for="preparationTime" class="col-md-4 control-label">Preparation Time (mins)</label>

                                <div class="col-md-6">
                                    <input id="preparationTime" type="number" class="form-control" name="preparationTime" value="{{@$menu['preparation_time']}}" required autofocus max="30">

                                    @if ($errors->has('preparationTime'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('preparationTime') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{action('MenuController@index')}}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-success" style="margin-left:15px">{{$button_label}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.layout2')

@section('content')
    <div class="container container-height">

        <div class="row">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">User Account</div>


                    <div class="panel-body">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('success') }}</p>
                            </div><br />
                        @endif
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{action('AccountController@update', base64_encode($user['id']))}}">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{$user['name']}}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('paymayaAccount') ? ' has-error' : '' }}">
                                <label for="paymayaAccount" class="col-md-4 control-label">Paymaya Account Number</label>

                                <div class="col-md-6">
                                    <input id="paymayaAccount" type="text" class="form-control" name="paymayaAccount" value="{{ old('paymayaAccount') }}" required autofocus>

                                    @if ($errors->has('paymayaAccount'))
                                        <span class="help-block">
                                                    <strong>{{ $errors->first('paymayaAccount') }}</strong>
                                                </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user['email'] }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                    @endif
                                </div>
                            </div>

                            @if(Auth::user()->isOwner())
                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label for="image" class="col-md-4 control-label">Stall Image</label>

                                    <div class="col-md-6">
                                        <input id="image" type="file" class="form-control" name="image" value="{{@$stallImage['image_path']}}" autofocus>

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
                                        <a href="{{asset('images/stall/'.$stallImage['image_path'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="80" width="100" src="{{asset('images/stall/'.$stallImage['image_path'])}}"></a>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4" style="float: right;">
                                    <a href="{{url('')}}" class="btn btn-default">Cancel</a>
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

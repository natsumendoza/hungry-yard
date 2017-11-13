@extends('layouts.layout')

@section('content')


    <div id="about">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="about-heading">
                        {{--<h2>hungry yard</h2>--}}
                        {{--<h3>Tarlac's first food park</h3>--}}
                    </div>
                </div>
            </div>
        </div>

        <!--about wrapper left-->
        <div class="container" style="margin-top: 30px;">

            <div class="row">
                <div class="col-md-12 text-center">
                    <p>{{$stall['name']}}</p>
                    <img height="100" width="100" class="img-circle" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/stall/'.$stallImage['image_path'])}}">
                </div>
            </div>

            <div class="row" style="text-align: center">
            @foreach($menus as $menu)


                <div class="col-md-4">

                    <form method="POST" action="{{url('orders')}}">
                        {{csrf_field()}}
                        <input id="stallId" name="stallId" type="hidden" class="hidden" value="{{$menu['stall_id']}}">
                        <input id="productId" name="productId" type="hidden" class="hidden" value="{{$menu['id']}}">

                        <img height="100" width="100" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/menu/' . $menu['image'])}}">
                        <p>Name: {{$menu['name']}}</p>
                        <p>Price: {{$menu['price']}}</p>
                        <p>Preparation Time: {{$menu['preparation_time']}}</p>

                        @auth
                            <p><input name="quantity" type="number" /><button class="btn-success">Add to cart</button></p>
                        @endauth

                    </form>
                </div>

            @endforeach
            </div>

        </div>
    </div>

@endsection
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
                <img height="100" width="100" class="img-circle" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/stall/'.$stallImage['image_path'])}}">
            </div>

            <div class="row" style="text-align: center">
            @foreach($menus as $menu)


                <div class="col-md-4">
                    <img height="100" width="100" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/menu/' . $menu['image'])}}">
                    <p>Name: {{$menu['name']}}</p>
                    <p>Price: {{$menu['price']}}</p>
                    <p>Preparation Time: {{$menu['preparation_time']}}</p>
                    <p><input type="number" /><button class="btn">Add to cart</button></p>
                </div>

            @endforeach
            </div>

        </div>
    </div>

@endsection
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
                <img class="img-circle" style="cursor:pointer;" class="img-polaroid" src="data:image/jpeg;base64,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">

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
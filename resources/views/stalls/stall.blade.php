@extends('layouts.layout')

@section('content')


    <div id="about">

        <div class="container " >
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
        <div class="container"  style="margin-top: 30px; text-align: center;">

            <div class="row">
                <p style="font-size: 30px;"><b>{{$stallImage['stall_name']}}</b></p>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <img height="150" width="150" class="img-circle" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/stall/'.$stallImage['image_path'])}}">
                </div>
            </div>

            <div style="height: 50px;"></div>

            <div class="row" style="text-align: center">

            @if(count($menus)>0)
            @php($ctr=0)
            @foreach($menus as $menu)
                @php(++$ctr)

                <div class="col-md-4">

                    <form method="POST" action="{{url('orders')}}">
                        {{csrf_field()}}
                        <input id="stallId" name="stallId" type="hidden" class="hidden" value="{{$menu['stall_id']}}">
                        <input id="productId" name="productId" type="hidden" class="hidden" value="{{$menu['id']}}">
                        <a href="{{asset('images/menu/'.$menu['image'])}}" target="_blank" data-toggle="tooltip" title="Click image"><img height="200" width="270" src="{{asset('images/menu/'.$menu['image'])}}"></a>
                        <p style="font-size: 20px;"><b>{{$menu['name']}}</b></p>
                        <p style="color: forestgreen;">&#8369; {{$menu['price']}}</p>
                        <p><b>Preparation Time: {{$menu['preparation_time']}} mins.</b></p>

                        @if(Auth::guest() || Auth::user()->isCustomer())
                            <p><button class="btn">Add to cart</button></p>
                        @endif

                    </form>
                </div>

                @if($ctr == 3)
                    @php($ctr=0)
                    <div style="height: 50px;" class="col-md-12"></div>
                @endif

            @endforeach
            @else
                <div style="height: 50px;" class="col-md-12"><i>No item available.</i></div>
            @endif
            </div>

        </div>
    </div>

@endsection
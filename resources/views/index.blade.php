@extends('layouts.layout')

@section('content')

    <div id="slider" class="flexslider">

        <ul class="slides">
            <li>
                <img src="{{URL::asset('images/slider/slider1.jpg')}}">

                {{--<div class="caption">--}}
                {{--<h2><span>an awesome website</span></h2>--}}
                {{--<h2><span>html theme</span></h2>--}}
                {{--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                {{--<button class="btn">Read More</button>--}}
                {{--</div>--}}

            </li>
            <li>
                <img src="{{URL::asset('images/slider/slider2.jpg')}}">

                {{--<div class="caption">--}}
                {{--<h2><span>yet another slide</span></h2>--}}
                {{--<h2><span>html theme</span></h2>--}}
                {{--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                {{--<button class="btn">Read More</button>--}}
                {{--</div>--}}

            </li>
            <li>
                <img src="{{URL::asset('images/slider/slider3.jpg')}}">

                {{--<div class="caption">--}}
                {{--<h2><span>one more slide</span></h2>--}}
                {{--<h2><span>html theme</span></h2>--}}
                {{--<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                {{--<button class="btn">Read More</button>--}}
                {{--</div>--}}

            </li>
        </ul>

    </div>




            <div id="about">

                @if(isset(Auth::user()->id))

                    @if(Auth::user()->isCustomer())

                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                            <div class="about-heading">
                                <h2>hungry yard</h2>
                                <h3>Tarlac's first food park</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!--about wrapper left-->
                <div class="container" style="margin-top: 30px;">

                    <div class="row" style="text-align: center;">
                        @foreach($stalls as $stall)
                            <a href="{{url('/stalls/' . $stall['id'])}}">
                                <img width="100" height="100" class="img-circle" style="cursor:pointer;" class="img-polaroid" src="{{asset('images/stall/'.$stall['image_path'])}}">
                            </a>
                        @endforeach
                    </div>

                </div>

                    @endif

                @endif

            </div>


@endsection
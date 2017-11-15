@extends('layouts.layout')

@section('content')
    <div id="slider" class="flexslider">

        <ul class="slides">

            @foreach($gallery as $item)
                <li>
                    <img src="{{asset('images/gallery/'.$item['image_path'])}}">

                </li>
            @endforeach
        </ul>

    </div>




            <div>
                <div id="about">


                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="text-align: center;">
                                    <h2 style="font-family:'Scrap Food Regular'; font-size: 100px;">HUNGRY YARD</h2>
                                    <h3>Tarlac's first food park</h3>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

            </div>

    <hr>

    <div id="about">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="about-heading">
                        <h1 style="font-family: 'Tastysushi Line'; font-size: 80px;">Choose a stall</h1>
                    </div>
                </div>
            </div>
        </div>

        <!--about wrapper left-->
        <div class="container">

            <div class="row" style="text-align: center;">
                @foreach($stalls as $stall)
                    <div class="col-md-4" style="margin-bottom: 35px;">
                        <a href="{{url('/stalls/' .  base64_encode($stall['id']))}}" style=" color: #333; font-size: 20px;">
                            {{$stall['name']}}<br>
                            <img width="150" height="150" class="img-circle" style="cursor:pointer;" src="{{asset('images/stall/'.$stall['image_path'])}}">
                        </a>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
    <hr>

    <div id="about">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div style="text-align: center;">
                        <h2><span style="font-family: 'Tastysushi Line'; font-size: 120px;">What<span style="font-family:'Courier New';font-size: 50px;"><i>'</i></span>s new?</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <!--about wrapper left-->
        <div class="container" style="margin-top: 30px;">

            <div class="row" style="text-align: center;">
                @foreach($events as $event)
                    <h2>{{$event['name']}}</h2>
                    <h4>{{$event['description']}}</h4>
                    <h4>{{$event['date']}}</h4>
                    <img class="" style="max-width: 50%;" class="img-polaroid" src="{{asset('images/event/'.$event['image_path'])}}">
                @endforeach
            </div>

        </div>

    </div>
    </div>



@endsection
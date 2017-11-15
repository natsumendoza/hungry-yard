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
                            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                                <div class="about-heading">
                                    <h2>hungry yard</h2>
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
                        <h3>Choose a stall</h3>
                    </div>
                </div>
            </div>
        </div>

        <!--about wrapper left-->
        <div class="container" style="margin-top: 30px;">

            <div class="row" style="text-align: center;">
                @foreach($stalls as $stall)
                    <div class="col-md-4" style="margin-top: 35px;">
                        <a href="{{url('/stalls/' .  base64_encode($stall['id']))}}">
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
                    <div class="about-heading">
                        <h2>What's new?</h2>
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
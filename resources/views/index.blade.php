@extends('layouts.layout')

@section('content')
    <div id="slider" class="flexslider">

        <ul class="slides">
            <li>
                <img src="{{URL::asset('images/slider/slider1.jpg')}}">

                <div class="caption">
                    <h2><span>an awesome website</span></h2>
                    <h2><span>html theme</span></h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="btn">Read More</button>
                </div>

            </li>
            <li>
                <img src="{{URL::asset('images/slider/slider2.jpg')}}">

                <div class="caption">
                    <h2><span>yet another slide</span></h2>
                    <h2><span>html theme</span></h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="btn">Read More</button>
                </div>

            </li>
            <li>
                <img src="{{URL::asset('images/slider/slider3.jpg')}}">

                <div class="caption">
                    <h2><span>one more slide</span></h2>
                    <h2><span>html theme</span></h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="btn">Read More</button>
                </div>

            </li>
        </ul>

    </div>

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

        <!--about wrapper left-->
        <div class="container">

            <div class="row">
                <div class="col-xs-12 hidden-sm col-md-5">

                    <div class="about-left">
                        <img src="images/about/about1.jpg" alt="">
                    </div>

                </div>

                <!--about wrapper right-->
                <div class="col-xs-12 col-md-7">
                    <div class="about-right">
                        <div class="about-right-heading">
                            <h1>about our consulting</h1>
                        </div>

                        <div class="about-right-boot">
                            <div class="about-right-wrapper">
                                <a href="#"><h3>Boost Your Business</h3></a>
                                <p>Michael Knight a young loner on a crusa champion the cause of the innocent. The helpless. powerless in a world of operate above the law.</p>
                            </div>
                        </div>

                        <div class="about-right-best">
                            <div class="about-right-wrapper">
                                <a href="#"><h3>Best Business Statitics</h3></a>
                                <p>Michael Knight a young loner on a crusa champion the cause of the innocent. The helpless. powerless in a world of operate above the law.</p>
                            </div>
                        </div>

                        <div class="about-right-support">
                            <div class="about-right-wrapper">
                                <a href="#"><h3>24/7 Online Support</h3></a>
                                <p>Michael Knight a young loner on a crusa champion the cause of the innocent. The helpless. powerless in a world of operate above the law.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
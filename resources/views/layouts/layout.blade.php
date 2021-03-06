<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Hungry Yard</title>
    <meta name="description" content="Free Bootstrap Theme by BootstrapMade.com">
    <meta name="keywords" content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Open+Sans|Raleway" rel="stylesheet">
    {{--<link type="text/css" rel="stylesheet" href="{{ env('APP_URL') == 'http://localhost' ? asset('css/flexslider.css') : secure_asset('css/flexslider.css') }}" />--}}
    {{--<link type="text/css" rel="stylesheet" href="{{ env('APP_URL') == 'http://localhost' ? asset('css/bootstrap.min.css') : secure_asset('css/bootstrap.min.css') }}" />--}}
    {{--<link type="text/css" rel="stylesheet" href="{{ env('APP_URL') == 'http://localhost' ? asset('css/font-awesome.min.css') : secure_asset('css/font-awesome.min.css') }}" />--}}
    {{--<link type="text/css" rel="stylesheet" href="{{ env('APP_URL') == 'http://localhost' ? asset('css/style.css') : secure_asset('css/style.css') }}" />--}}

    <link type="text/css" rel="stylesheet" href="{{ asset('css/flexslider.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}

    <!-- =======================================================
    Theme Name: MyBiz
    Theme URL: https://bootstrapmade.com/mybiz-free-business-bootstrap-theme/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->
    <style>
        .panel {
            margin-top:20px;
        }
        @media only screen and (max-width: 768px) {
            /* For mobile phones: */
            /*.navbar-header {*/
            /*width: 100% !important;*/
            /*}*/
            /*.container-fluid>.navbar-collapse, .container-fluid>.navbar-header, .container>.navbar-collapse, .container>.navbar-header {*/
            /*margin-left: 0 !important;*/
            /*}*/
            #about {
                margin-top: 30px;
            }
        }
        @font-face {
            font-family: Scrap Food Regular;
            src: url('{{ asset('fonts/scrap-food-regular.ttf')}}');
        }
        @font-face {
            font-family: Tastysushi Line;
            src: url('{{ asset('fonts/tastysushi-line.ttf')}}');
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body id="top" data-spy="scroll">
<!--top header-->

<header id="home">

    <section class="top-nav hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="top-left">

                        <ul>
                            <li>
                                <a href="https://www.facebook.com/hungryyard/">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/Hungry_Yard">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/hungryyard/">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>



            </div>
        </div>
    </section>

    <!--main-nav-->

    <div id="main-nav" class="affix">

        <nav class="navbar">
            <div class="container">
                {{--style="width: 152.39px; height: 50px;"--}}
                <div class="navbar-header" >
                    <a href="{{url('/')}}" class="navbar-brand logo-image">
                        {{--<img src="{{URL::asset('images/Hungry-Yard.jpg')}}" />--}}

                    </a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ftheme">
                        <span class="sr-only">Toggle</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="navbar-collapse collapse" id="ftheme">

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{url('/')}}">Home</a></li>
                        @auth

                            @if(Auth::user()->isCustomer())
                                @php($cartSize = 0)
                                @if (\Session::has('cartSize'))
                                    @php($cartSize = \Session::get('cartSize'))
                                @endif


                                <li><a href="{{ url('/cart/'.\base64_encode(Session::get('transactionCode'))) }}"><i class="glyphicon glyphicon-shopping-cart fa-lg"></i><span class="w3-badge w3-red">@if($cartSize>0){{$cartSize}}@endif</span></a></li>
                            @endif
                            @if(!Auth::guest())
                                <?php
                                $newNotifs = 0;
                                $newNotifs = \Session::get('newNotifs');
                                ?>


                                @if(Auth::user()->isOwner() OR Auth::user()->isCustomer())
                                    <li class="dropdown" id="notif_li"> <a id="notif_btn" data-toggle="dropdown"><i class="glyphicon glyphicon-bell fa-lg"></i>@if($newNotifs>0)<span class="w3-badge w3-red">{{$newNotifs}}</span>@endif</a>
                                        <ul class="dropdown-menu" id="notif_menu">
                                            @foreach(\Session::get('notifications') as $notif)
                                                @if($notif['read_flag'] == config('constants.ENUM_NO'))
                                                    <li style="background-color: lightgrey;"><a href="#" style="font-size: 12px;">{{$notif['action']}}</a></li>
                                                @else
                                                    <li style="background-color: white;"><a href="#" style="font-size: 12px;">{{$notif['action']}}</a></li>
                                                @endif
                                            @endforeach
                                            <li class="divider"></li>
                                            <li><a href="{{url('/notifications')}}" style="color: #2aabd2; font-size: 12px;" >See all notifications...</a></li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                            @if(Auth::user()->isCustomer())
                                <li style="cursor: pointer;"><a data-toggle="dropdown" id="navigation">{{Auth::user()->name}}
                                        <i class="glyphicon glyphicon glyphicon-menu-down fa-lg"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{url('orders')}}">Orders</a></li>
                                        <li><a href="{{url('useraccount')}}">Account</a></li>
                                    </ul>
                                </li>

                            @endif
                            @if(Auth::user()->isAdmin())

                                <li style="cursor: pointer;"><a data-toggle="dropdown" id="navigation">{{Auth::user()->name}}
                                        <i class="glyphicon glyphicon glyphicon-menu-down fa-lg"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{url('stall')}}">Stalls</a></li>
                                        <li><a href="{{url('customer')}}">Customers</a></li>
                                        <li><a href="{{url('event')}}">Events</a></li>
                                        <li><a href="{{url('gallery')}}">Gallery</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->isOwner())

                                <li style="cursor: pointer;">
                                    <a data-toggle="dropdown" id="dropdown_a" id="navigation">{{Auth::user()->name}}
                                        <i class="glyphicon glyphicon glyphicon-menu-down fa-lg"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{url('menu')}}">Menus</a></li>
                                        <li><a href="{{url('orders')}}">Orders</a></li>
                                        <li><a href="{{url('useraccount')}}">Account</a></li>
                                    </ul>
                                </li>


                            @endif
                            <li>
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{--<i class="fa fa-fw fa-sign-out"></i>--}}
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @else
                                <li><a href="{{url('/login')}}">login</a></li>
                                <li><a href="{{url('/register')}}">register</a></li>

                                @endauth
                                {{--<li><a href="#about">about</a></li>--}}
                                {{--<li><a href="#service">services</a></li>--}}
                                {{--<li><a href="#portfolio">portfolio</a></li>--}}
                                {{--<li><a href="#contact">contact</a></li>--}}
                                {{-- <li class="hidden-sm hidden-xs">
                                     <a href="#" id="ss"><i class="glyphicon glyphicon-search"></i></a>
                                 </li>--}}
                    </ul>

                </div>

                <div class="search-form">
                    <form>
                        <input type="text" id="s" size="40" placeholder="Search..." />
                    </form>
                </div>

            </div>
        </nav>
    </div>

</header>

<!--slider-->




@yield('content')

<!--bottom footer-->
<div id="bottom-footer" class="">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-left">
                </div>
            </div>

            <div class="col-md-8">
                <div class="location">
                    <div id="contact-us" class="pull-right">
                        <h3>Location (<a href="{{url('/location')}}">show map</a>)</h3>
                        <p>Hungry Yard at San Vicente,</p>
                        <p>Diversion Road Tarlac City, </p>
                        <p>Tarlac (at the back of SM Tarlac)</p>
                        <p>Open from 4PM – 12AM</p>
                    </div>
                    <div id="contact-us" class="pull-right" style="margin-right: 10%">
                        <h3>Contact Us</h3>
                        <p>Phone: 09098079363</p>
                        <p>Email: <a href="mailto:hungryyardtrlc@gmail.com">hungryyardtrlc@gmail.com</a></p>
                    </div>

                    {{--<ul class="list-unstyled list-inline pull-right">--}}
                    {{--<li><a href="#contact">Contact Us</a></li>--}}
                    {{--<li><a href="#home">Location</a></li>--}}
                    {{--</ul>--}}
                </div>
            </div>
        </div>
    </div>
</div>



<!-- jQuery -->
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.flexslider.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.inview.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/contactform/contactform.js') }}"></script>
<script>
    $(document).ready(function() {
        var notifIsActive = false;
        var position = 70;
        $('#notif_li').on('shown.bs.dropdown', function(){
            notifIsActive = true;
            $("body, html").scrollTop(80);
        });
        $('#notif_li').on('hidden.bs.dropdown', function(){
            notifIsActive = false;
        });
        $(window).scroll(function( e ){
            var scroll = $(window).scrollTop();
            if (scroll < position)
            {
                if(notifIsActive == true)
                {
                    $('.dropdown-menu').dropdown('toggle');
                }
            }
        });

        $('#notif_btn').on('click', function () {
            $.ajax({
                type:'GET',
                url:'/notifications/read',
                data:null,
                success:function(data){
                }
            });

        });
        setTimeout(function() {
            location.reload();
        }, 300000);
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXOwkYXufIFwPbsZMMbYUwezjezHUYoHY&callback=initMap"
        async defer></script>

<script>
    var map;
    function initMap() {

        var myLatLng = {lat: 15.4748009, lng: 120.5904892};
        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 17
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            label: 'Hungry Yard Tarlac',
            map: map,
            title: 'Hungry Yard Tarlac'
        });
    }

    // initMap();
</script>



</body>

</html>
@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
            width: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #back{
            display: none;
            position: absolute;
            top: 10px;
            left:10px;
            z-index: 100000000;
        }
        .load {
            height: 100%;
            width: 100%;
            background-color: #1a1a1a;
            color: #e5e5e5;
            position: absolute;
            top:0px;
            left:0px;
            right:0px;
            bottom:0px;
            opacity: .9;
            text-align: center;
        }
        .load h1{
            margin-top: 25%;
        }

    </style>

    <div class="load">
        <h1>Loading Please Wait</h1>
    </div>
    <div class="container" id="onlineUsers">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Online People
                    <span id="online"></span>
                </h3>
            </div>
            <div class="panel-body">
                <ol class="people" id="output" data-room="room1">

                </ol>
            </div>
        </div>
    </div>


    <a href="#" onclick="backToList()" id="back" class="btn btn-info">Back To online List</a>

    <div id="map"></div>

@endsection

@section('script')
    <script src="https://use.fontawesome.com/8a45ad20e8.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.googlemap/1.5/jquery.googlemap.min.js"></script>
    <script src="{{ url('/') }}/StreamLab/StreamLab.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3fDaCFZ5eyWiksGv4K619g0O85EseuGk&callback=init"></script>
    @include('geo.newScript')

@endsection
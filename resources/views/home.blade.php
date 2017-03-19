@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ url('/') }}/css/reset.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/style-rtl.css">
@endsection

@section('content')

    <div class="wrapper">
        <div class="container">
            <div class="left">
                <div class="top">

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <a href="{{ url('/logout') }}" class="back" title="logout"
                       onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off" style="margin-top: 6px;margin-left: 6px;"></i>
                    </a>
                    <a href="javascript:;" class="back" data-chat="people" original-title="العودة للغرف" id="back"><i class="fa fa-arrow-circle-right fa-2x" aria-hidden="true"></i></a>
                </div>
                <ul class="people" id="people" data-room="room1">

                </ul>

                <ul class="rooms" id="rooms">

                </ul>
            </div>
            <div class="right">
                <div class="top"><span>  <span class="name roomName">

                        </span> (<span id="onlineCount"></span>) </span></div>
                <div class="chat active-chat" id="messages" data-chat="person1"></div>
                <div class="write">
                    <a href="javascript:;" class="write-link attach"></a>
                    <input type="text"  id="messageText"  />
                    <a href="javascript:;" class="write-link smiley"></a>
                    <a href="javascript:;" class="write-link send"></a>
                    <input type="hidden" id="channel_name_id">
                </div>
            </div>
        </div>

    </div>


@endsection

@section('script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="{{ url('/') }}/js/index.js"></script>
    <script src="https://use.fontawesome.com/8a45ad20e8.js"></script>
    <script src="{{ url('/') }}/js/tooltip.js" charset="utf-8"></script>
    <script src="{{ url('/') }}/StreamLab/StreamLab.js"></script>
    @include('script')
@endsection
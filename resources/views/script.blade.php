<script>
    var ChannelConnection = "public";
    var slh = new StreamLabHtml();
    var slu = new StreamLabUser();
    var sln = new StreamLabNotification();
    var sls ;
    var messageData;
    var messageShow;
    var key;
    var data, channel, channelI;
    var findRoom,personName,message,OnlineOnRoom,activeChannel,classMessage,scroll;
    /***
     * first time login
     */
    $('.people').show();
    $('.rooms').hide();
    $('.back').show();

    /***
     * send messages
     */
    slh.getById('messageText').onkeypress = function(e){
        key = e.which || e.keyCode;
        if (key === 13) {
            if(slh.getVal('messageText') != ""){
                messageData = JSON.stringify({
                    message:slh.getVal('messageText'),
                    username:"{{ $user->name }}",
                    date:new Date()
                });
                data = {
                    _token:"{{ csrf_token() }}",
                    message:messageData,
                    channelName:slh.getVal('channel_name_id'),
                    eventName:"messages"
                };
                sls.sendMessage("{{ url('streamLab/post/message') }}",data,function(){
                    slh.setVal('messageText' , ' ');
                });
                return false;
            }
            return false;
        }
        return true;
    };



   function logic(channel){
       sls = new StreamLabSocket({
           appId:"{{ config('stream_lab.app_id') }}",
           channelName:channel,
           event:"*",
           user_id:'{{ $user->id }}',
           user_secret:'{{ md5( $user->id.$user->email.$user->name)  }}'
       });
       //console.log(sls.socket.readyState);
       if(sls){
           /***
            * get All rooms
            */
           getAllChannels();
           /***
            * set channel name
            */
           $('.roomName').html(channel);
           /***
            * set channel to input channel
            */
           slh.setVal('channel_name_id' , channel);
           /***
            * connect to streamLab socket
            */

           /***
            * get All online depend on channel
            * you are online on it
            */
           slu.getAllUser("{{ url('streamLab/app/user') }}" ,function(online){
               OnlineOnRoom = slh.json(online).data;
               var e = '';
               $.each(OnlineOnRoom, function(indexOnline,dataOnline) {
                   e += '@include("online")';
               });
               $('#people').html(e);
           }, 10 ,0  , channel);
           /****
            * handel when the socket get messages
            */
           sls.socket.onmessage = function(res){
               /***
                * set data to the class
                */
               slh.setData(res);
               /**
                * update online list when user login or
                * log out change this list
                */
               slh.updateUserList(function(id){
                   slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOnline){
                       if(dataOnline.status){
                           dataOnline = slh.json(dataOnline).data;
                           var e = '@include("online")';
                           $('#people').append(e);
                           sln.makeNotification("User login" , dataOnline.data.name + ' Login');
                       }
                   })
               } , function(id){
                   slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOffline){
                       if(dataOffline.status){
                           dataOffline = slh.json(dataOffline).data;
                           $('#'+id).remove();
                           sln.makeNotification("User Logout" , dataOffline.data.name +' Logout');
                       }
                   });
               });
               /***
                * show online count and
                * show messages
                */
               slh.setOnline('onlineCount');
               /**
                * use browser notification when user
                * send message
                */
               if(slh.getSource() == 'messages'){
                   messageShow = JSON.parse(slh.getMessage());
                   classMessage = messageShow.username != '{{ Auth::user()->name }}' ? 'me' : 'you';
                   sln.makeNotification("Message From Stream lab" , messageShow.message);
                   message = @include('message')
               $('#messages').append(message);
                   scroll = slh.getById('messages');
                   scroll.scrollTop = scroll.scrollHeight;
               }
           }
       }else{
           alert('error on connection')
       }

   }
    /*
    * connect to the channel public as default
    */
    logic(ChannelConnection);
    /***
     * change channel when user login to another channel
     */
    function subscribeToChannel(e){
        if ($(e).hasClass('.active')) {
            return false;
        } else {
        $(e).addClass('active');
        $('.rooms').hide();
        $('.people').show();
        $('.back').show();
        $('#search').css('width','125px');
        /**
         * connect to another channel now
         */
        logic(e.getAttribute('data-room-name'));
        }
    }

    $(".back").click(function(){
        $('.room').removeClass('active');
        /**
         * close the previous socket
         */
        sls.socket.close();
        /*
         * clear the online list
         */
        slh.html('people' , '');
        /*
         * clear messages sections
         */
        slh.html('messages' , '');
        $('.roomName').html('');
        $('#onlineCount').html('');
        getAllChannels();
        $('.people').hide();
        $('.rooms').show();
        $('.back').hide();
        $('#search').css('width','175px')
    });


    /**
     * get All Channels
     */

    function getAllChannels(){
        slh.getAllChannel('rooms' , function(rooms){
            channel = slh.json(rooms).data;
            var e = '';
            $.each(channel, function(indexChannel,dataChannel) {
                activeChannel =  dataChannel.name == 'public' ? 'active' : '';
                e += '@include("channel")';
            });
            $('#rooms').html(e);
        });
    }

</script>
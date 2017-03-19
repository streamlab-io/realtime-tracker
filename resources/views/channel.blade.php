<li class="room '+activeChannel+'" data-room="'+dataChannel.id+'" onclick="subscribeToChannel(this)" data-room-name="'+dataChannel.name+'">'+
    '<img src="{{url('/')}}/img/room.png" alt="" />'+
    '<span class="name">'+dataChannel.name+'</span>'+
    '<span class="time">'+dataChannel.online+'</span>'+
    '<span class="preview"></span>'+
    '</li>
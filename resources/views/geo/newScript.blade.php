<script>
    var sls, trackId, options, id;
    var slu = new StreamLabUser();
    var slh = new StreamLabHtml();

    /**
     * start function
     * */
    function init(){
        /***
         * open socket
         */
        sls = openSocket('geo');
        setTimeout(function(){
            /**
             * update user location
             */
            if(updateUserLocation()){
                /***
                 * get who is online
                 */
                getOnline();
                /**
                 * remove user from list and add user to list
                 */
                updateLocationWhenUserLoginOrLogout();
                /**
                 * watch user location
                 */
                id = navigator.geolocation.watchPosition(success, error, options);
            }
            $('.load').hide();
        } , 5000);

    }


    /**
     * open socket And Connect
     */
    function openSocket(channel){
        sls = new StreamLabSocket({
            appId:"{{ config('stream_lab.app_id') }}",
            channelName:channel,
            event:"*",
            user_id:'{{ $user->id }}',
            user_secret:'{{ md5($user->id.$user->email.$user->name)  }}'
        });
        return sls;
    }

    /**
     * get online users
     */

    function getOnline(){
        slu.getAllUser("{{ url('streamLab/app/user') }}" ,function(online){
            OnlineOnRoom = slh.json(online).data;
            var e = '';
            $.each(OnlineOnRoom, function(indexOnline,dataOnline) {
                e += '@include("geo.component.online")';
            });
            $('#output').html(e);
        }, 10 ,0  , 'geo');
    }

    /**
     * update user location when login
     */
    function updateUserLocation(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position){
                updateUser(position.coords.latitude , position.coords.longitude);
            });
            return true;
        }else{
            return false;
        }
    }

    /**
     * update user info
     * @param lat
     * @param lng
     */
    function updateUser(lat , lng){
        var data = {
            id:'{{ $user->id  }}',
            secret:'{{  $user->id.$user->email.$user->name  }}',
            name:'{{ $user->name }}',
            _token:"{{ csrf_token() }}",
            bio:"{{ $user->bio }}",
            lng:lng ,
            lat:lat
        };
        slu.updateUser("{{ url('streamLab/update/user') }}" , data , function(response){
            return true;
        });
    }

    /***
     * remove user from list add user to list
     * get locations from user and show it on map
     */
    function updateLocationWhenUserLoginOrLogout(){
        sls.socket.onmessage = function(res){
            slh.setData(res);
            if(slh.getSource() == 'messages'){
                /**
                 * receive new location
                 */
                d = slh.json(slh.getMessage());
                if(trackId == d.id ){
                    console.log(d.id+' move Now to'+d.pos.lat+' | '+d.pos.lng);
                    $("#map").removeMarker(d.id);
                    $("#map").addMarker({
                        coords: [d.pos.lat,d.pos.lng],
                        title:d.name,
                        text:d.bio,
                        id:d.id
                    });
                }
            }
            slh.updateUserList(function(id){
                /**
                 * user get online add him to the list
                 * if current user track this user update the map
                 */
                slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOnline){
                    if(dataOnline.status){
                        dataOnline = slh.json(dataOnline).data;
                        console.log("User "+ dataOnline.id + ' login' + dataOnline.data.name);
                        if(trackId == dataOnline.id){
                            $("#map").removeMarker(dataOnline.id);
                            $("#map").addMarker({
                                coords: [dataOnline.data.lat,dataOnline.data.lng],
                                title:dataOnline.data.name,
                                text:dataOnline.data.bio,
                                id:dataOnline.id
                            });
                        }
                        $('#output').append('@include("geo.component.online")');
                    }
                })
            } , function(id){
                /**
                 * user get offline remove him to the list
                 * if current user track this user go back to user list
                 */
                slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOffline){
                    if(dataOffline.status){
                        dataOffline = slh.json(dataOffline).data;
                        console.log("User "+ dataOffline.id + ' logout ' + dataOffline.data.name);
                        if(trackId == dataOffline.id){
                            $("#map").removeMarker(dataOffline.id);
                            backToList();
                        }
                        $('#'+dataOffline.id).remove();
                    }
                });
            });
        }
    }

    /**
     * track user
     * @param id
     */

    function trackByID(id){
        this.preventDefault;
        $('.load').show();
        trackId = id;
        $('#onlineUsers').hide();
        $('#map').show();
        $('#back').show();
        slu.userExist("{{ url('streamLab/app/checkuser') }}", id, function (dataOnline) {
            if (dataOnline.status) {
                dataOnline = slh.json(dataOnline).data;
                console.log(slh.json(dataOnline));
                    if(dataOnline.online && dataOnline.data.lng != '' && dataOnline.data.lat != '' ){
                        $("#map").googleMap();
                        $("#map").removeMarker(dataOnline.id);
                        $("#map").addMarker({
                            coords: [dataOnline.data.lat, dataOnline.data.lng],
                            title: dataOnline.data.name,
                            text: dataOnline.data.bio,
                            id: dataOnline.id
                        });
                    }else{
                        alert('We Can not located this user now May be he not allow to get his location ..')
                    }
            }
            $('.load').hide();
        });
    }

    /**
     * click on back btn
     */

    function backToList(){
        this.preventDefault;
        $('#map').hide();
        $('#onlineUsers').show();
        $('#back').hide();
        stopTracker();
    }

    /**
     * send position to message
     */

    function sendPositionInMessage(pos){
        messageData = JSON.stringify({
            pos:{lat:pos.lat , lng:pos.lng},
            id:"{{ $user->id }}",
            bio:"{{ $user->bio }}",
            name:"{{ $user->name }}"
        });
        dataSend = {
            _token:"{{ csrf_token() }}",
            message:messageData,
            channelName:'geo',
            eventName:"pos"
        };
        sls.sendMessage("{{ url('streamLab/post/message') }}",dataSend,function(){});
    }


    /**
     * watch position success
     * @param pos
     */

    function success(pos) {
        crd = {lng:pos.coords.longitude , lat:pos.coords.latitude};
        console.log(crd);
        updateUser(crd.lat, crd.lng);
        sendPositionInMessage(crd);
    }

    /**
     * user watch erorr
     * @param err
     */

    function error(err) {
        console.log('ERROR(' + err.code + '): ' + err.message);
    }

    /**
     * stop watch this id
     */

    function stopTracker(){
        navigator.geolocation.clearWatch(id);
    }

    /**
     * watch option
     * @type {{enableHighAccuracy: boolean, timeout: number, maximumAge: number}}
    */

    options = {
        enableHighAccuracy: false,
        timeout: 10000,
        maximumAge: 0
    };


</script>
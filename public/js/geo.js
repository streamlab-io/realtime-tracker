var myLatLng = {};
var id, target, options, map, marker = null;

function getLocation(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setPosition);
        }
}

function setPosition(position){
    myLatLng = {
        lng:position.coords.longitude,
        lat:position.coords.latitude
    };
    initMap();
}

function initMap() {
     map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: myLatLng
    });
    addMarker("Your position now here .");
}

function addMarker( title , pos){
    pos = pos === undefined ? myLatLng : pos;
    if(marker == null){
        alert('here');
        marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: title
        });
    }else{
        alert('move');
        marker.setPosition( new google.maps.LatLng( pos.lng,pos.lat  ) );
        map.panTo( new google.maps.LatLng(  pos.lng,pos.lat  ) );
    }

}


function success(pos) {
    var crd = {lng:pos.coords.longitude , lat:pos.coords.latitude};
    addMarker("you move now" , crd);
}

function error(err) {
    console.log('ERROR(' + err.code + '): ' + err.message);
}

function stopTracker(){
    navigator.geolocation.clearWatch(id);
}

options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

id = navigator.geolocation.watchPosition(success, error, options);



<!DOCTYPE html>
<html>
  <head>
    <title>Bakso Di malang</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */

      #map {
        height: 100%;
        width: 70%;
      }
      .samping{
        width: 30%;
        height: 100%;
        overflow: auto;
      }
      .samping div{
        width: 100%;
        padding: 10px;
        overflow: auto;
        box-sizing: border-box;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
      }
      .checked {
        color: orange;
      }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <h1 align="center">Lokasi Bakso Di Kota Malang </h1>
    <p align="center">Marker, directions, panorama &amp; distance matrix.</p>
    <div id="map">  </div>
    <div id="panel">

    </div>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5aYmmVrGx3NIH97yyucQ5tJ7mtV-G1a8"></script>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script>
      var lokasiSaya = "sttar malang";
      var map;
      var lokasi = [];
      var rute = [];
      var iconBase = "https://maps.google.com/kml/shapes/";
      var icons = {
        parking:{
          icon: iconBase + 'parking_lot_maps.png'
        }
      };

      function initialize() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {
            // lat: -0.789275,
            // lng: 113.921327},
            lat:  -7.983908,
            lng: 112.621391},
          zoom: 13,
          zoomControlOptions:{
            position: google.maps.ControlPosition.RIGHT_TOP
          }
        });
      }
      google.maps.event.addDomListener(window,'load',initialize);

      function findLokasi(){
        $.ajax({
          type : "GET",
          url: "lokasi.json",
          dataType : "json",
          success : function(data){

            var d = new google.maps.InfoWindow();
            var e;

            $.each(data,function(i,b){
              e = new google.maps.Marker({
                  position : new google.maps.LatLng(b.lat, b.lng),
                  // icon : icons[b.type].icon,
                  map:map
              });
              lokasi.push(e);
              google.maps.event.addListener(e, 'click',(function(a, i){
                return function(){
                  d.setContent(  '<div>'
                                  + '<img src="'+ b.img +'" height="100" width="100" class="center">'
                                  + '<h3>'+ b.nama+ '</h3>'
                                  + '<p>'
                                  + 'Alamat : '+ b.alamat
                                  + '<br>'
                                  + 'jam buka : ' + b.hours
                                  + '<br>'
                                  + b.rating
                                  + '</p>'
                                  + '<br>'
                                  + '<button class="rute" data-alamat="'+ b.alamat+'">Jarak</button>'
                                  + '</div>'
                                );

                  d.open(map,a);
                }
              })(e, i))
            });
          }
        });
      }

      function hapusRute(){
        for (var i = 0; i < rute.length; i++) {
          rute[i].setMap(null);
        }
      }

      function jarak(alamat){

        var a = new google.maps.DirectionsService();
        var b = new google.maps.DirectionsRenderer();

        b.setMap(map);
        b.setPanel(document.getElementById('panel'));

        rute.push(b);

        var request = {
          origin : lokasiSaya,
          destination : alamat,
          travelMode : google.maps.DirectionsTravelMode.DRIVING
        };

        a.route(request,function(response,status){
          if (status == google.maps.DirectionsStatus.OK) {
            b.setDirections(response);
          }
        });

      }

      $(function(){
        findLokasi();
        $("body").on('click','.rute',function(){
            $('panel').empty();
            hapusRute();
            jarak($(this).data('alamat'));
        });
      });

    </script>

  </body>
</html>

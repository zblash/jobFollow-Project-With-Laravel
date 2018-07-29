@extends('layouts.app')
@section('pageTitle', 'Müşteri Ekle')
@section('css')
<style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
    </style>
@endsection
@section('content')
<section class="content-header">
      <h1>
        Müşteri Ekle
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Müşteri İşlemleri</a></li>
        <li class="active">Müşteri Ekle</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
        <div class="col-md-12">

          <div class="box box-info">
            
           <form id="postcontent">
              {{ csrf_field() }}
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Müşteri Adı Soyadı </label> <span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>

              <div class="form-group">
                <label>Telefon Numarası</label> <span style="color: red;font-size: 16px">*</span>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" name="phone" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>

              <div class="form-group">
                <label>T.C Kimlik Numarası</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bookmark"></i>
                  </div>
                  <input type="text" name="tc_no" class="form-control" data-inputmask='"mask": "99999999999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>
              <div class="form-group">
                  <label>Adresi</label> <span style="color: red;font-size: 16px">*</span>
                  <textarea name="address" class="form-control" rows="3"></textarea>
                </div>
                 <div class="form-group">
                  <label>Bölgeyi Haritada Seçin : </label>
                 <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                 <div id="map" style="height: 400px;width:100%;"></div>
                <input type="hidden" name="mapscoordinates" id="mapscoordinates">
              </div>
                <div class="form-group">
                  <label>Adres Tarifi</label>
                  <textarea name="address_direction" class="form-control" rows="3"></textarea>
                </div>
              <div class="form-group">
                  <label>Fatura Bilgileri (Kurumsal Müşteri İçin Doldurulması</label> <span style="color: red;font-size: 16px">Zorunludur)</span>
                  <textarea name="billing" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label>Müşteri Tipi : </label> <span style="color: red;font-size: 16px">*</span>
                <label>
                  <input type="radio" name="customer_level" value="kurumsal" class="minimal" checked>
                  Kurumsal
                </label>
                <label>
                  <input type="radio" name="customer_level" value="bireysel" class="minimal">
                  Bireysel
                </label>
               
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="box-footer">
                <button type="submit" class="btn btn-primary">Gönder</button>
              </div>
            </form>
        </div>
      </div>
      </section>
      @endsection
      @section('js')
<script src="{{ asset('public/theme/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
      <script>    
   
        $(document).ready(function() {
        $("#postcontent").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('customeradd') }}',
            data: $("#postcontent").serialize(),
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
                  setTimeout(function(){ window.location = '{{ route('customersPage') }}'; }, 2000);
            },
            error: function(jqXHR, status, error) {
                var obj = $("#ajax-modal-body").text(jqXHR.responseJSON.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
},
            statusCode: {
             500: function() {
          var obj = $("#ajax-modal-body").text("Beklenmeyen Bir Hata Oluştu.");
                $('#modal-ajax').modal('show');
        }
      }
        });
        e.preventDefault();
    });
 });
  $(function () {
    $('[data-mask]').inputmask()

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
  })
</script>     
<script>
     function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 38.4175917, lng: 26.939632},
          zoom: 10,
          mapTypeId: 'roadmap'
        });
    var marker;

    function placeMarker(location) {
      if ( marker ) {
        marker.setPosition(location);
      } else {
        marker = new google.maps.Marker({
          position: location,
          map: map
        });
      }
      document.getElementById("mapscoordinates").value = location;
    }

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
        google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng);
    });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCSD6Mo1Atj3qmBQ3kVZ4z648XFQXqQiNI&libraries=places&callback=initAutocomplete"
         async defer></script>
      @endsection
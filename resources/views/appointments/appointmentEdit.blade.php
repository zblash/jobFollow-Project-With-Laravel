@extends('layouts.app')
@section('pageTitle', 'Randevu Düzenle')
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
        Randevu Düzenle
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Randevu İşlemleri</a></li>
        <li class="active">Randevu Düzenle</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
        <div class="col-md-12">

          <div class="box box-info">
            <form id="postcontent">
              {{ csrf_field() }}
            <div class="box-body">
  
                            <div class="form-group">
                  <label for="exampleInputEmail1">Varolan Müşterilerden Seçin </label><span style="color: red;font-size: 16px">*</span>
                
                  <select name="customer_id" id="customer_id" class="form-control" style="width: 100%;">
                  <option selected="selected" value="-1">--------------</option>
                  @foreach($customers as $customer)
                    @if($customer->id == $appointment->customer->id)
                  <option value="{{ $customer->id }}" selected="selected">{{ $customer->name }}</option>
                    @else
                  <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endif
                  @endforeach
                </select>
                 
              </div>

            <div class="form-group">
                  <label for="service_id">Hizmeti Seçin </label><span style="color: red;font-size: 16px">*</span>
                <select id='pre-selected-optionss' multiple='multiple' name="service_id[]">

                      @foreach($services as $service)
                      <?php echo '<option value="'. $service->id .'"'; ?>
                      @foreach($appointment->services as $aservice)
                      @if($service->id == $aservice->id)
                      <?php echo 'selected="selected"' ?> 
                      @endif
                      @endforeach
                      <?php echo '>'.$service->name .'</option>'; ?>
                      
                      @endforeach
                    </select>
                 
              </div>
               <div class="form-group">
                  <label for="service_pay">Hizmet Tutarı </label><span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="service_pay" value="{{ $appointment->service_pay }}" class="form-control" id="service_pay">
                  </div>
              </div>
              <div class="form-group">
                  <label for="service_pay">Ödeme Türü </label><span style="color: red;font-size: 16px">*</span>
                  <select name="payment_type" id="payment_type" class="form-control" style="width: 100%;">
                  <option value="">--------------</option>
                  @if($appointment->payment_type == "nakit")
                  <option value="nakit" selected="selected">Nakit</option>
                  <option value="kredi_karti">Kredi Kartı</option>
                  <option value="ucretsiz_hizmet">Ücretsiz Hizmet</option>
                  @elseif($appointment->payment_type == "kredi_karti")
                  <option value="nakit">Nakit</option>
                  <option value="kredi_karti" selected="selected">Kredi Kartı</option>
                  <option value="ucretsiz_hizmet">Ücretsiz Hizmet</option>
                  @elseif($appointment->payment_type == "ucretsiz_hizmet")
                  <option value="nakit">Nakit</option>
                  <option value="kredi_karti">Kredi Kartı</option>
                  <option value="ucretsiz_hizmet" selected="selected">Ücretsiz Hizmet</option>
                  @endif
                </select>
              </div>
                  <div class="form-group">
                  <label for="employee_pay">Personele Ödenecek Tutar </label><span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="employee_pay" value="{{ $appointment->employee_pay }}" class="form-control" id="employee_pay">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Personel Seçin 

                  </label>
                    <select id='pre-selected-options' multiple='multiple' name="employee_id[]">

                      @foreach($employees as $employee)
                      <?php echo '<option value="'. $employee->id .'"'; ?>
                      @foreach($appointment->employees as $aemployee)
                      @if($employee->id == $aemployee->id)
                      <?php echo 'selected="selected"' ?> 
                      @endif
                      @endforeach
                      <?php echo '>'.$employee->name .'</option>'; ?>
                      
                      @endforeach
                    </select>
                 <div class="employeesdiv">
                   
                 </div>
              </div>
                      <div class="form-group">
                  <label for="driver_id">Şoför Seçin </label><span style="color: red;font-size: 16px">*</span>
                
                  <select name="driver_id" id="driver_id" class="form-control" style="width: 100%;">
                  <option value="">--------------</option>
                  @foreach($drivers as $driver)
                  @if($driver->id == $appointment->driver->id)
                  <option value="{{ $driver->id }}" selected="selected">{{ $driver->name }}</option>
                  @else
                  <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                  @endif
                  @endforeach
                </select>
                 
              </div>
                            <div class="form-group">
                <label>Tarih Ve Saat Seçin</label><span style="color: red;font-size: 16px">*</span>
                <div style="width: 100%;height: 34px;">
                <div class="input-group date col-md-6 col-sm-6" style="float: left;">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="appointment_date" value="{{ $appointment_date }}" class="form-control pull-right" id="datepicker">
                </div>
                  <div class="input-group bootstrap-timepicker col-md-5 col-sm-5" style="float: right;">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" name="appointment_time" value="{{ $appointment_time }}" class="form-control timepicker">
                  </div>
                 
                  </div>
                <!-- /.input group -->
              </div>
                              <div class="form-group">
                  <label>Kimlere SMS Gitsin : </label>
               <label>
                  <input type="checkbox" class="minimal" id="sms" name="sms[]" value="smscustomer" checked>
                  Müşteri
                </label>
                <label>
                  <input type="checkbox" class="minimal" id="sms" name="sms[]" value="smsemployee" checked>
                  Personel
                </label>
                <label>
                  <input type="checkbox" class="minimal" id="sms" name="sms[]" value="smsdriver" checked>
                  Şoför
                </label>
               
              </div>
<div class="form-group">
                  <label>Bölgeyi Haritada Seçin : </label>
                 <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                 <div id="map" style="height: 400px;width:100%;"></div>
                <input type="hidden" name="mapscoordinates" id="mapscoordinates">
              </div>
            </div>
            
          <!-- /.box -->
          <div class="box-footer">
                <button id="formbutton" type="submit" class="btn btn-primary">Gönder</button>
              </div>
            </form>
        </div>
      </div>
      </section>

      @endsection
      @section('js')

<!-- Select2 -->
<script src="{{ asset('public/theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('public/theme/plugins/input-mask/jquery.inputmask.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('public/theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('public/theme/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('public/theme/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('public/theme/plugins/selector/js/jquery.multi-select.js') }}"></script>
<script type="text/javascript">

  $(document).ready(function() {
 $("#postcontent").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('appointmentEdit',$id) }}',
            data: $("#postcontent").serialize(),
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
                setTimeout(function(){ window.location = '{{ route('appointmentsPage') }}'; }, 2000);
            },
            error: function(jqXHR, status, error) {
                var obj = $("#ajax-modal-body").text(jqXHR.responseJSON.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
},
            statusCode: {
             500: function(data) {
          var obj = $("#ajax-modal-body").text(data.messages);
                $('#modal-ajax').modal('show');
                
        }
      }
        });
        e.preventDefault();
    });
 });
  $(function () {
        
  $('#pre-selected-options').multiSelect();
$('#pre-selected-optionss').multiSelect();
   
    //Initialize Select2 Elements
    $('.select2').select2()
    $('[data-mask]').inputmask()


    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false
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
        @if($mapstat == 1)
    var marker = new google.maps.Marker({
          position: { lat: {{ $mapslat }} , lng: {{ $mapslng }} },
          map: map
        });
        @else
        var marker;
        @endif
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
    <script src="https://maps.googleapis.com/maps/api/js?key=your-api-key&libraries=places&callback=initAutocomplete"
         async defer></script>

      @endsection
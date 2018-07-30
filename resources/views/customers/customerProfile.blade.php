@extends('layouts.app')
@section('pageTitle', 'Müşteri Ayrıntıları')
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
        Müşteri Ayrıntıları
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="#">Müşteri İşlemleri</a></li>
        <li class="active">Müşteri Ayrıntıları</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
       
        <div class="col-md-12">
          <div class="nav-tabs-custom">
                       <div id="tabs">
  <ul>
    <li><a href="#pservices">Geçmiş Hizmetler</a></li>
    <li><a href="#details">Ayrıntılar</a></li>
    <li><a href="#edit">Düzenle</a></li>
    <li><a href="#appointment">Planlı Randevu Ekle</a></li>
  </ul>
  <div id="pservices">
 <table id="example1" class="table table-bordered table-striped" style="width: 100% !important">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Verilen Hizmet</th>
                  <th>Hizmet Tutarı</th>
                  <th>Personel(ler)</th>
                  <th>Hizmet Tarihi</th>
                </tr>
                </thead>
   
              </table>
  </div>
  <div id="details">
              <dl class="dl-horizontal">
                <dt>Adı Soyadı:</dt>
                <dd>{{ $customer->name }}</dd>
                <dt>Telefon No:</dt>
                <dd>{{ $customer->phone }}</dd>
                <dt>T.C Kimlik No:</dt>
                <dd>{{ $customer->tc_no }}</dd>
                <dt>Adresi:</dt>
                <dd>{{ $customer->address }}</dd>
                <dt>Adres Tarifi:</dt>
                <dd>{{ $customer->address_direction }}</dd>
                <dt>Fatura Bilgisi:</dt>
                <dd>{{ $customer->billing }}</dd>
                <dt>Müşteri Tipi:</dt>
                <dd>{{ $customer->customer_level }}</dd>
              </dl>
  </div>
  <div id="edit">
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
                  <input type="text" name="name" value="{{ $customer->name }}" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>

              <div class="form-group">
                <label>Telefon Numarası</label> <span style="color: red;font-size: 16px">*</span>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>

              <div class="form-group">
                <label>T.C Kimlik Numarası</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bookmark"></i>
                  </div>
                  <input type="text" name="tc_no" value="{{ $customer->tc_no }}" class="form-control" data-inputmask='"mask": "99999999999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>
              <div class="form-group">
                  <label>Adresi</label> <span style="color: red;font-size: 16px">*</span>
                  <textarea name="address" class="form-control" rows="3">{{ $customer->address }}</textarea>
                </div>
                    <div class="form-group">
                  <label>Bölgeyi Haritada Seçin : </label>
             <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                 <div id="map" style="height: 400px;width:100%;"></div>
                <input type="hidden" name="mapscoordinates" id="mapscoordinates" value="{{ $customer->address_coordinates }}">
        </div>
                 <div class="form-group">
                  <label>Adres Tarifi</label>
                  <textarea name="address_direction" class="form-control" rows="3">{{ $customer->address_direction }}</textarea>
                </div>
              
              <div class="form-group">
                  <label>Fatura Bilgileri</label> <label>Fatura Bilgileri (Kurumsal Müşteri İçin Doldurulması</label> <span style="color: red;font-size: 16px">Zorunludur)</span>
                  <textarea name="billing" class="form-control" rows="3">{{ $customer->billing }}</textarea>
                </div>
                <div class="form-group">
                  <label>Müşteri Tipi : </label> <span style="color: red;font-size: 16px">*</span>
                  @if($customer->customer_level == "kurumsal")
                <label>
                  <input type="radio" name="customer_level" value="kurumsal" class="minimal" checked>
                  Kurumsal
                </label>
                <label>
                  <input type="radio" name="customer_level" value="bireysel" class="minimal">
                  Bireysel
                </label>
               @else
               <label>
                  <input type="radio" name="customer_level" value="kurumsal" class="minimal">
                  Kurumsal
                </label>
                <label>
                  <input type="radio" name="customer_level" value="bireysel" class="minimal" checked>
                  Bireysel
                </label>
               @endif
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="box-footer">
                <button type="submit" class="btn btn-primary">Gönder</button>
              </div>
            </form>
  </div>
  <div id="appointment">
                <form id="postcontentt">
              {{ csrf_field() }}
            <div class="box-body">
            <input type="hidden" value="{{ $customer->id }}" name="customer_id">

                <input type="hidden" name="mapscoordinates" id="mapscoordinates" value="{{ $customer->address_coordinates }}">
        
            <div class="form-group">
                  <label for="service_id">Hizmeti Seçin </label> <span style="color: red;font-size: 16px">*</span>
                
                  <select id='pre-selected-optionss' multiple='multiple' name="service_id[]">
                  @foreach($services as $service)
                  <option value="{{ $service->id }}">{{ $service->name }}</option>
                  @endforeach
                </select>
                 
              </div>
               <div class="form-group">
                  <label for="service_pay">Hizmet Tutarı </label> <span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="service_pay" class="form-control" id="service_pay">
                  </div>
              </div>
              <div class="form-group">
                  <label for="service_pay">Ödeme Türü </label> <span style="color: red;font-size: 16px">*</span>
                  <select name="payment_type" id="payment_type" class="form-control" style="width: 100%;">
                  <option value="">--------------</option>
                  <option value="nakit">Nakit</option>
                  <option value="kredi_karti">Kredi Kartı</option>
                  <option value="ucretsiz_hizmet">Ücretsiz Hizmet</option>
                  
                </select>
              </div>
                  <div class="form-group">
                  <label for="employee_pay">Personele Ödenecek Tutar </label> <span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="employee_pay" class="form-control" id="employee_pay">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Personel Seçin 
                  </label> <span style="color: red;font-size: 16px">*</span>
                    <select id='pre-selected-options' multiple='multiple' name="employee_id[]">
                      @foreach($employees as $employee)
                      <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                      @endforeach
                    </select>
                 <div class="employeesdiv">
                   
                 </div>
              </div>
                      <div class="form-group">
                  <label for="driver_id">Şoför Seçin </label> <span style="color: red;font-size: 16px">*</span>
                
                  <select name="driver_id" id="driver_id" class="form-control" style="width: 100%;">
                  <option value="">--------------</option>
                  @foreach($drivers as $driver)
                  <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                  @endforeach
                </select>
                 
              </div>
                            <div class="form-group">
                <label>Tarih Ve Saat Seçin</label> <span style="color: red;font-size: 16px">*</span>
                <div style="width: 100%;height: 34px;">
                <div class="input-group date col-md-6 col-sm-6" style="float: left;">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="appointment_date" class="form-control pull-right" id="datepicker">
                </div>
                  <div class="input-group bootstrap-timepicker col-md-5 col-sm-5" style="float: right;">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" name="appointment_time" class="form-control timepicker">
                  </div>
                 
                  </div>
                <!-- /.input group -->
              </div>
                       <div class="form-group">
                  <label for="service_id">Randevu Sıklığı </label> <span style="color: red;font-size: 16px">*</span>
                
                  <select name="appointment_range" id="appointment_range" class="form-control" style="width: 100%;">
                  <option value="">--------------</option>
                  <option value="1">Günlük</option>
                  <option value="5">Beş Günde Bir</option>
                  <option value="7">Haftalık</option>
                  <option value="14">İki Haftada Bir</option>
                  <option value="30">Aylık</option>
                </select>
                 
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
        
            </div>
          <!-- /.box -->
          <div class="box-footer">
                <button id="formbutton" type="submit" class="btn btn-primary">Gönder</button>
              </div>
            </form>
  </div>
</div>
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection
@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('public/theme/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('public/theme/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('public/theme/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('public/theme/plugins/selector/js/jquery.multi-select.js') }}"></script>
<script>
      
  $(function () {
     $('#pre-selected-options').multiSelect();
     $('#pre-selected-optionss').multiSelect();
            $("#postcontent").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('customerEdit',$id) }}',
            data: $("#postcontent").serialize(),
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
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
 $("#postcontentt").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('newplannedappointmentPost') }}',
            data: $("#postcontentt").serialize(),
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
                setTimeout(function(){ window.location = '{{ route('plannedappointmentsPage') }}'; }, 2000);
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
    $( "#tabs" ).tabs();
    $('#example1').DataTable( {
    	 processing: true,
            serverSide: true,
            ajax: '{{ route('customerajax',$id) }}',
            columns: [
            { data: 'id', name: 'id' },
            { data: 'service', name: 'service' },
            { data: 'service_pay', name: 'service_pay' , orderable: false},
            { data: 'employees', name: 'employees' },
            { data: 'appointment_date', name: 'appointment_date' },
           

        ],
        "language": {
            "sDecimal":        ",",
    "sEmptyTable":     "Tabloda herhangi bir veri mevcut değil",
    "sInfo":           "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
    "sInfoEmpty":      "Kayıt yok",
    "sInfoFiltered":   "(_MAX_ kayıt içerisinden bulunan)",
    "sInfoPostFix":    "",
    "sInfoThousands":  ".",
    "sLengthMenu":     "Sayfada _MENU_ kayıt göster",
    "sLoadingRecords": "Yükleniyor...",
    "sProcessing":     "İşleniyor...",
    "sSearch":         "Ara:",
    "sZeroRecords":    "Eşleşen kayıt bulunamadı",
    "oPaginate": {
        "sFirst":    "İlk",
        "sLast":     "Son",
        "sNext":     "Sonraki",
        "sPrevious": "Önceki"
    },
    "oAria": {
        "sSortAscending":  ": artan sütun sıralamasını aktifleştir",
        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
    },
    "select": {
        "rows": {
            "_": "%d kayıt seçildi",
            "0": "",
            "1": "1 kayıt seçildi"
        }
    }
        }
    })

  })
   $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    $('[data-mask]').inputmask()


    
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
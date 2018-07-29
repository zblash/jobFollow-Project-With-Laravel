@extends('layouts.app')
@section('pageTitle', 'Anasayfa')
@section('css')
  <style type="text/css">
    .uyarilar {
  width: 100%;
  height: auto;
  border-radius: 3px;
  font-size: 16px;
  color: #ecf0f5;
  padding: 5px;
  margin-bottom: 5px;
  -webkit-animation: warninganimation 1.5s infinite;  
  -moz-animation: warninganimation 1.5s infinite;  
  -o-animation: warninganimation 1.5s infinite; 
  animation: warninganimation 1.5s infinite; 
}

@-webkit-keyframes warninganimation {
  0%, 49% {
    background-color: #f56954;
  }
  50%, 100% {
    background-color: #ecf0f5;
  }
}
  </style>
@endsection
@section('content')
    <section class="content-header">
      <h1>
        Anasayfa
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Anasayfa</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $weeklyprofit }}</h3>

              <p>Haftalık Genel Kazanç</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('profitsPage') }}" class="small-box-footer">Detaylar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $monthlyprofit }}</h3>

              <p>Aylık Genel Kazanç</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('profitsPage') }}" class="small-box-footer">Detaylar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $customers }}</h3>

              <p>Toplam Müşteri Sayısı</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="{{ route('customersPage') }}" class="small-box-footer">Ayrıntılar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $employees }}</h3>

              <p>Toplam Aktif Personel</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="{{ route('employeesPage') }}" class="small-box-footer">Ayrıntılar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-lg-12 col-xs-12 col-md-12" id="appointmentwarning">
        
      </div>
               <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">SON 10 RANDEVU</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>ID</th>
                  <th>Müşteri Adı</th>
                  <th>Müşteri Telefon No</th>
                  <th>Verilen Hizmet</th>
                  <th>Hizmet Tutarı</th>
                  <th>Hizmet Tarihi</th>
                </tr>
                @foreach($appointments as $appointment)
                <tr>
                  <td>{{ $appointment->id }}</td>
                  <td>{{ $appointment->customer->name }}</td>
                  <td>{{ $appointment->customer->phone }}</td>
                  <td>
                    @foreach($appointment->services as $service)
                      {{ $service->name }} ,
                    @endforeach
                  </td>
                  <td>{{ $appointment->service_pay }}</td>
                  <td>{{ $appointment->appointment_time }}</td>
                </tr>
               @endforeach
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
                <div class="col-xs-12">
  
          <!-- /.box -->

          <div class="box"> <div class="box-header">
              <h3 class="box-title">Planlı Randevular</h3>

            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped" style="width: 100% !important">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Müşteri Adı</th>
                  <th>Müşteri Telefon No</th>
                  <th>Verilen Hizmet</th>
                  <th>Hizmet Tutarı</th>
                  <th>Personel(ler)</th>
                  <th>Randevu Tarihi</th>
                  <th>Gelecek Randevu Tarihi</th>
                </tr>
                </thead>
   
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row (main row) -->

    </section>
@endsection
@section('js')
<script src="{{ asset('public/theme/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script type="text/javascript">
     $(function () {

     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        $.ajax({
            type: 'POST',
            url:'{{ route('checkappointments') }}',
            data: {method: '_POST', submit: true},
            success:function(data){
                $('#appointmentwarning').append(data.messages);
            }
        });
      $('#example1').DataTable( {
            processing: true,
            serverSide: true,
            ajax: '{{ route('plannedappointmentsajax') }}',
            columns: [
            { data: 'id', name: 'id' },
            { data: 'customer', name: 'customer' },
            { data: 'customerphone', name: 'customerphone' },
            { data: 'service', name: 'service' },
            { data: 'service_pay', name: 'service_pay' },
            { data: 'employees', name: 'employees' },
            { data: 'appointment_date', name: 'appointment_date' },
            {data: 'action', name: 'action'}


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
  </script>
@endsection
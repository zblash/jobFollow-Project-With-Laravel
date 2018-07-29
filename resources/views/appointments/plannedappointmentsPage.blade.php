@extends('layouts.app')
@section('pageTitle', 'Planlı Randevular')
@section('content')
<section class="content-header">
      <h1>
        Planlı Randevular
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Randevu İşlemleri</a></li>
        <li class="active">Planlı Randevular</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  
          <!-- /.box -->

          <div class="box">
            <!-- /.box-header -->
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
                  <th>İşlemler</th>
                </tr>
                </thead>
   
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@endsection
@section('js')
<script src="{{ asset('public/theme/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script>
  $(function () {
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
            $('#example1').on('click', '.btn-warning[data-remote]', function (e) { 
    e.preventDefault();

    var url = $(this).data('remote');
    // confirm then
    if (confirm('Randevu İptal Edilsin mi?')) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {method: '_POST'},
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
                setTimeout(function(){ window.location = '{{ route('cancelledappointments') }}'; }, 2000);
            },
            statusCode: {
             500: function() {
          var obj = $("#ajax-modal-body").text("Beklenmeyen Bir Hata Oluştu.");
                $('#modal-ajax').modal('show');
        }
      }
        });
    }
});
  $('#example1').on('click', '.btn-delete[data-remote]', function (e) { 
    e.preventDefault();
   
    var url = $(this).data('remote');
    if (confirm('Randevu silinsin mi?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true}
        }).always(function (data) {
            $('#example1').DataTable().draw(false);
        });
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
            { data: 'next_appointment_date', name: 'next_appointment_date' },
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
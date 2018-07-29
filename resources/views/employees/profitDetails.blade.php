@extends('layouts.app')
@section('pageTitle', 'Ödeme Detayları')
@section('content')
<section class="content-header">
      <h1>
        Ödeme Detayları
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Personel İşlemleri</a></li>
        <li class="active">Ödeme Detayları</li>
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
                  <th>Hizmet</th>
                  <th>Randevu Tarihi</th>
                  <th>Personel Ücreti</th>
                </tr>
                </thead>
            <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">Toplam:</th>
                <th></th>
            </tr>
        </tfoot>
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

   $('#example1').DataTable( {

    	 processing: true,
            serverSide: true,
            ajax: '{{ route('employeeprofitAjax',$id) }}',
            columns: [
            { data: 'id', name: 'id' },
            { data: 'customer', name: 'customer' },
            { data: 'service', name: 'service'},
            { data: 'appointment_d', name: 'appointment_d' },
            { data: 'employee_pay', name: 'employee_pay' }

        ],"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            pageTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            $( api.column( 4 ).footer() ).html(
                pageTotal+' TL'
            );
        },
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
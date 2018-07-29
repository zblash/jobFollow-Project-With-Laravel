@extends('layouts.app')
@section('pageTitle', 'Kazançlar')
@section('content')
<section class="content-header">
      <h1>
        Kazançlar
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Kazançlar</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  
          <!-- /.box -->

          <div class="box">
           
                <div class="box-body">
               <div class="input-group">
                <label>Filtrele : </label>
                <input type="text" name="date" id="daterange-btn" class="form-control" />
                </div>
              </div>
                <div class="box-body">
              <table id="example1" class="table table-bordered table-striped" style="width: 100% !important">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Hizmet Adı</th>
                  <th>Hizmet Ücreti(TL)</th>
                  <th>Hizmet Tarihi</th>
                  <th>Kalan Kazanç(TL)</th>
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

<script src="{{ asset('public/theme/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/theme/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script>
  $(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   var profitstable = $('#example1').DataTable( {
       processing: true,
            serverSide: true,
            ajax: {
              url: '{{ route('profitsAjax') }}',
              data: function (d) {
                d.date = $('input[name=date]').val();
              }
            },
            columns: [
            { data: 'id', name: 'id' },
            { data: 'service', name: 'service' },
            { data: 'service_pay', name: 'service_pay' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'action', name: 'action'}

        ],"fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        $(api.column(4).footer()).html(json.sumprofit+' TL');
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

       $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Bugün'       : [moment(), moment()],
          'Dün'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Geçen Hafta' : [moment().subtract(6, 'days'), moment()],
          'Geçen Ay': [moment().subtract(29, 'days'), moment()],
          'Bu Ay'  : [moment().startOf('month'), moment().endOf('month')],
          'Son Ay'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
          "locale": {
        "format": "YYYY-MM-DD",
        "separator": " / ",
        "applyLabel": "Onay",
        "cancelLabel": "İptal",
        "fromLabel": "Den",
        "toLabel": "dene",
        "customRangeLabel": "Özel",
        "weekLabel": "W",
        "daysOfWeek": [
            "Pa",
            "Pt",
            "Sa",
            "Ça",
            "Pe",
            "Cu",
            "Ct"
        ],
        "monthNames": [
            "Ocak",
            "Şubat",
            "Mart",
            "Nisan",
            "Mayıs",
            "Haziran",
            "Temmuz",
            "Ağustos",
            "Eylül",
            "Ekim",
            "Kasım",
            "Aralık"
        ],
        "firstDay": 1
    },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment(),
        autoUpdateInput: false
      }
      
    );
    $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' / ' + picker.endDate.format('YYYY-MM-DD'));
      profitstable.draw();
  });

  $('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
       
  })
</script>
@endsection
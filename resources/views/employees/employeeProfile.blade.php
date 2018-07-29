@extends('layouts.app')
@section('pageTitle', 'Personel Ayrıntıları')
@section('content')
    <section class="content-header">
      <h1>
       Personel Ayrıntıları
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="#">Personel İşlemleri</a></li>
        <li class="active">Personel Ayrıntıları</li>
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
  </ul>
  <div id="pservices">
 <table id="example1" class="table table-bordered table-striped" style="width: 100% !important">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Verilen Hizmet</th>
                  <th>Hizmet Verilen Müşteri</th>
                  <th>Hizmet Tutarı</th>
                  <th>Hizmet Tarihi</th>
                </tr>
                </thead>
   
              </table>
  </div>
  <div id="details"><center>
    <img src="{{ asset('public/pictures/'.$employee->tc_pic) }}" class="img-responsive" />
    </center>
    <dl class="dl-horizontal">
                <dt>Adı Soyadı:</dt>
                <dd>{{ $employee->name }}</dd>
                <dt>Telefon No:</dt>
                <dd>{{ $employee->phone }}</dd>
                <dt>T.C Kimlik No:</dt>
                <dd>{{ $employee->tc_no }}</dd>
                <dt>Adresi:</dt>
                <dd>{{ $employee->address }}</dd>
                <dt>Banka Bilgileri:</dt>
                <dd>{{ $employee->bank }}</dd>
                <dt>Hesap Sahibi:</dt>
                <dd>{{ $employee->bank_account_owner }}</dd>
                <dt>IBAN:</dt>
                <dd>{{ $employee->iban }}</dd>
                <dt>Müşteri Tipi:</dt>
                <dd>@if($employee->confirmed) Aktif @else Pasif @endif</dd>
              </dl>
  </div>
  <div id="edit">
<form method="post" action="{{ route('employeeEdit',$id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Personel Adı Soyadı </label> <span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="name" value="{{ $employee->name }}" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>

              <div class="form-group">
                <label>Telefon Numarası</label> <span style="color: red;font-size: 16px">*</span>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" name="phone" value="{{ $employee->phone }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>

              <div class="form-group">
                <label>T.C Kimlik Numarası</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bookmark"></i>
                  </div>
                  <input type="text" name="tc_no" value="{{ $employee->tc_no }}" class="form-control" data-inputmask='"mask": "99999999999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>

<div class="form-group">
                  <label>Adresi</label>
                  <textarea name="address" class="form-control" id="address" rows="3" placeholder="Enter ..."> {{ $employee->address }}</textarea>
                </div>
 <div class="form-group">
                  <label for="exampleInputEmail1">Banka Adı </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" value="{{ $employee->bank }}" name="bank" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Banka Hesap Sahibi </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" value="{{ $employee->bank_account_owner }}" name="bankaccountowner" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">IBAN No </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" value="{{ $employee->iban }}" name="iban" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
                <div class="form-group">
                  <label>Personel Durumu : </label> <span style="color: red;font-size: 16px">*</span>
                  @if($employee->confirmed)
                <label>
                  <input type="radio" name="confirmed" value="1" class="minimal" checked>
                  Aktif
                </label>
                <label>
                  <input type="radio" name="confirmed" value="0" class="minimal">
                  Pasif
                </label>
               @else
               <label>
                  <input type="radio" name="confirmed" value="1" class="minimal">
                  Aktif
                </label>
                <label>
                  <input type="radio" name="confirmed" value="0" class="minimal" checked>
                  Pasif
                </label>
               @endif
              </div>

              <div class="form-group">
                <label>Kimlik Fotokopisi</label>

                <input type="file" name="tc_pic" accept="image/*" onchange="loadFile(event)">
            <img id="output" />
            <script>
              var loadFile = function(event) {
                var reader = new FileReader();
                reader.onload = function(){

                  var output = document.getElementById('output');
                  output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
              };
            </script>
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
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
 
    $( "#tabs" ).tabs();
    $('#example1').DataTable( {
    	 processing: true,
            serverSide: true,
            ajax: '{{ route('employeeajax',$id) }}',
            columns: [
            { data: 'id', name: 'id' },
            { data: 'service', name: 'service' },
            { data: 'customer', name: 'customer' },
            { data: 'employee_pay', name: 'employee_pay'},
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

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
  })
</script>
@endsection
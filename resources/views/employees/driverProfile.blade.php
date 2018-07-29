@extends('layouts.app')
@section('pageTitle', 'Şoför Ayrıntıları')
@section('content')
    <section class="content-header">
      <h1>
        Şoför Ayrıntıları
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="#">Personel İşlemleri</a></li>
        <li class="active">Şoför Ayrıntıları</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
       
        <div class="col-md-12">
          <div class="nav-tabs-custom">
                       <div id="tabs">
  <ul>
    <li><a href="#details">Ayrıntılar</a></li>
    <li><a href="#edit">Düzenle</a></li>
  </ul>

  <div id="details">
   <dl class="dl-horizontal">
                <dt>Adı Soyadı:</dt>
                <dd>{{ $driver->name }}</dd>
                <dt>Telefon No:</dt>
                <dd>{{ $driver->phone }}</dd>
              </dl>
  </div>
  <div id="edit">
<form method="post" action="{{ route('driverEdit',$id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Şoför Adı Soyadı </label> <span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="name" value="{{ $driver->name }}" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>

              <div class="form-group">
                <label>Telefon Numarası</label> <span style="color: red;font-size: 16px">*</span>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" name="phone" value="{{ $driver->phone }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
                <!-- /.input group -->
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
<script src="{{ asset('public/theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('public/theme/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
  $(function () {
   $( "#tabs" ).tabs();
    $('[data-mask]').inputmask()

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    })
</script>
@endsection
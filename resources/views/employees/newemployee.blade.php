@extends('layouts.app')
@section('pageTitle', 'Personel Ekle')
@section('content')
<section class="content-header">
      <h1>
        Personel Ekle
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Personel İşlemleri</a></li>
        <li class="active">Personel Ekle</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
        <div class="col-md-12">

          <div class="box box-info">
            
           <form method="post" action="{{ route('newemployeePost') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Personel Adı Soyadı </label> <span style="color: red;font-size: 16px">*</span>
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
                  <label>Adresi</label>
                  <textarea name="address" class="form-control" id="address" rows="3" placeholder="Enter ..."></textarea>
                </div>
 <div class="form-group">
                  <label for="exampleInputEmail1">Banka Adı </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="bank" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Banka Hesap Sahibi </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="bankaccountowner" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">IBAN No </label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                  </div>
                  <input type="text" name="iban" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
                <div class="form-group">
                  <label>Personel Durumu : </label> <span style="color: red;font-size: 16px">*</span>
                <label>
                  <input type="radio" name="confirmed" value="1" class="minimal" checked>
                  Aktif
                </label>
                <label>
                  <input type="radio" name="confirmed" value="0" class="minimal">
                  Pasif
                </label>
               
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
      </section>
      @endsection
      @section('js')
<script src="{{ asset('public/theme/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('public/theme/plugins/iCheck/icheck.min.js') }}"></script>
      <script> 

  $(function () {
    $('[data-mask]').inputmask()

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
  })
</script>
      @endsection
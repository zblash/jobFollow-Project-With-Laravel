@extends('layouts.app')
@section('pageTitle', 'SMS Gönder')
@section('content')
<section class="content-header">
      <h1>
        SMS Gönder
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">SMS Gönder</a></li>
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
                  <label>Mesaj</label><span style="color: red;font-size: 16px">*</span>
                  <textarea name="message" class="form-control" id="message" rows="3" placeholder="Enter ..."></textarea>
                </div>

               <div class="form-group">
                  <label>Kimlere SMS Gitsin : </label><span style="color: red;font-size: 16px">*</span>
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
            url:'{{ route('sendSms') }}',
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
         $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
 });

</script>
      @endsection
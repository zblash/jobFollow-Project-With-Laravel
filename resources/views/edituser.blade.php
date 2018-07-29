@extends('layouts.app')
@section('pageTitle', 'Yetkili Düzenle')
@section('content')
<section class="content-header">
      <h1>
        Yetkili Düzenle
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Yetkili İşlemleri</a></li>
        <li class="active">Yetkili Düzenle</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
        <div class="col-md-12">

          <div class="box box-info">
            
           <form id="postcontent">
              {{ csrf_field() }}
            <div class="box-body">
              <!-- Date dd/mm/yyyy -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Yetkili İsmi </label><span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">E-mail Adresi </label><span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="email" value="{{ $user->email }}" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
              </div>
            <div class="form-group">
                  <label for="exampleInputEmail1">Yetkili Şifresi </label><span style="color: red;font-size: 16px">*</span>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input type="text" name="password" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
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
      <script> 
  $(document).ready(function() {
 $("#postcontent").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('edituserPost',$id) }}',
            data: $("#postcontent").serialize(),
            success:function(data){
                var obj = $("#ajax-modal-body").text(data.messages);
                obj.html(obj.html().replace(/{nt}/g,'<br/>'));
                obj.html(obj.html().replace(/,/g,''));
                $('#modal-ajax').modal('show');
                 setTimeout(function(){ window.location = '{{ route('users') }}'; }, 2000);
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
 });

</script>
      @endsection
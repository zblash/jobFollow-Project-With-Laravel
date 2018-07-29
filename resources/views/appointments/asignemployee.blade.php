@extends('layouts.app')
@section('pageTitle', 'Personel Ata')
@section('content')

<section class="content-header">
      <h1>
        Personel Ata
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Anasayfa</a></li>
        <li><a href="">Müşteri İşlemleri</a></li>
        <li class="active">Personel Ata</li>
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
                  <label for="exampleInputEmail1">Personel Seçin 
                  </label> <span style="color: red;font-size: 16px">*</span>
                    <select id='pre-selected-options' multiple='multiple' name="employee_id[]">
                   @foreach($employees as $employee)
                      <?php echo '<option value="'. $employee->id .'"'; ?>
                      @foreach($appointment->employees as $aemployee)
                      @if($employee->id == $aemployee->id)
                      <?php echo 'selected="selected"' ?> 
                      @endif
                      @endforeach
                      <?php echo '>'.$employee->name .'</option>'; ?>
                      
                      @endforeach
                    </select>
                 <div class="employeesdiv">
                   
                 </div>
              </div>
             

            </div>
          <!-- /.box -->
          <div class="box-footer">
                <button id="formbutton" type="submit" class="btn btn-primary">Gönder</button>
              </div>
            </form>
        </div>
      </div>
      </section>

      @endsection
      @section('js')

<!-- Select2 -->
<script src="{{ asset('public/theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/theme/plugins/selector/js/jquery.multi-select.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
 $("#postcontent").submit(function(e) {
          e.preventDefault();
        $.ajax({
            type:'POST',
            url:'{{ route('plannedappointmentasignEmployeePost',$id) }}',
            data: $("#postcontent").serialize(),
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
 });
  $(function () {



           
        
  $('#pre-selected-options').multiSelect();

   
    //Initialize Select2 Elements
    $('.select2').select2()
    
  })
</script>
      @endsection
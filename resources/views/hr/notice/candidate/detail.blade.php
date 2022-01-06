{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="container">
<!--Begin::App-->
  <div class="d-flex flex-row">
    <!--Begin:: App Aside-->
    @include('hr.candidate.left')

    <!--End:: App Aside-->

    <!--Begin:: App Content-->
      @yield('inside')
    <!--End:: App Content-->
  </div>
</div>

<!--End::App-->
@endsection

@section('scripts')
<script src="/js/simple-rating.js" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/form-repeater.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $('.rating').rating();
    $("body").on('click', '.teklif-guncelle-2', function() {
      var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
      if (r == true) {
          var id = $(this).attr('data-id');
          var value = $(this).attr('data-value');
          $.ajax({
              url: "/insan-kaynaklari/teklif/update-state",
              data: "id=" + id + "&value=" + value,
              dataType: 'json',
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              error: function() {},
              beforeSubmit: function() {},
              success: function(data) {
                  location.reload();
              }
          });
      }
    });

    $("body").on('click', '.aday-guncelle', function() {
      var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
      if (r == true) {
          var id = $(this).attr('data-id');
          var value = $(this).attr('data-value');
          $.ajax({
              url: "/insan-kaynaklari/adaylar/update-state",
              data: "id=" + id + "&value=" + value,
              dataType: 'json',
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              error: function() {},
              beforeSubmit: function() {},
              success: function(data) {
                  location.reload();
              }
          });
      }
    });
  
  $(".touchspin").TouchSpin({
      buttondown_class: "btn btn-secondary",
      buttonup_class: "btn btn-secondary",
      min: 0,
      max: 10,
      step: 1,
      decimals: 0,
      boostat: 5,
      maxboostedstep: 10
  });
  $('.pick-time').timepicker({
      minuteStep: 15,
      defaultTime: '09:00',           
      showSeconds: false,
      showMeridian: false,
      snapToStep: true
  }).on('changeTime.timepicker', function(e) {
    var name = $(e.target).attr('name');

    if(name == 'start_time'){
      $("[name=end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }

    if(name == 'real_start_time'){
      $("[name=real_end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }
  });
  $("body").on('change', '.file_side input[type="file"]', function(event){
    var thi = $(this);
    var a = $(this).val();
    $(this).prev().addClass("active").html(a);

      var files = event.target.files;
      
    var data = new FormData();
    $.each(files, function(key, value)
    {
      data.append(key, value);
    });

    $.ajax({
      type: "POST",
      url: "/insan-kaynaklari/adaylar/upload",
      dataType: 'json',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: data,
      cache: false,
      dataType: 'json',
      processData: false, // Don't process the files
      contentType: false, // Set content type to false as jQuery will tell the server its a query string request
      error: function(){
      },
      beforeSubmit: function(){
      },
      success: function(item){
        thi.closest(".file_side").find(".file_input").val(item.file);
        thi.prev().html(item.file);
      }
    });

  });
  $('.pick-date').datepicker({
      todayHighlight: true,
      autoclose: true,
      format: 'dd-mm-yyyy'
  });
  $(".date-format").inputmask("99-99-9999");

});
</script>
<script>
function ok(){
  $('.new-form').ajaxSubmit({ 
      beforeSubmit:  function(){
          $(".formprogress").show();
      },
      error: function(){
        swal.fire({
          "title": "",
          "text": "Kaydedilemedi",
          "type": "warning",
          "confirmButtonClass": "btn btn-secondary"
        });

      },
      dataType:  'json', 
      success:   function(item){
          if(item.status){
            location.reload();
          }else{
              swal.fire({
                  "title": "Dikkat",
                  "type": "warning",
                  "html": item.message,
                  "confirmButtonClass": "btn btn-secondary"
              });
          }
      }
  }); 
}

</script>
@stop

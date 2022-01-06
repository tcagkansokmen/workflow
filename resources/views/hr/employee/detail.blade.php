{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="container">
<!--Begin::App-->
  <div class="d-flex flex-row">
    <!--Begin:: App Aside-->
    @include('hr.employee.left')

    <!--End:: App Aside-->

    <!--Begin:: App Content-->
      @yield('inside')
    <!--End:: App Content-->
  </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
  $("body").on('click', '.sorgula', function(e){
      e.preventDefault();
      var thi = $(this);
      var href = $(this).attr('href');
      swal.fire({
          title: "Emin misiniz?",
          text: "Bu işlemi yapmak istediğinizi onaylayın.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Evet!",
          cancelButtonText: "Hayır!",
          reverseButtons: true
      }).then(function(result) {
          if (result.value) {
              $.ajax({
                  url: href,
                  dataType: 'json',
                  type: 'get',
                  success: function(data){
                      if(data.status){
                          location.reload()
                      }else{
                          swal.fire(
                              "Dikkat",
                              data.message,
                              "error"
                          )
                      }
                  }
              });
          } else if (result.dismiss === "cancel") {

          }
      });
  });
  
$("body").on('click', '.izin-guncelle', function(){
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
      $.ajax({
        url: "{{ route('update-permision') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
    }
});

$("body").on('click', '.avans-guncelle', function(){
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
      $.ajax({
        url: "{{ route('update-earnest') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
    }
});

$("body").on('click', '.egitim-guncelle', function(){
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
      $.ajax({
        url: "{{ route('update-education') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
    }
});

$("body").on('click', '.vize-guncelle', function(){
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
      $.ajax({
        url: "{{ route('update-visa') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
    }
});

		

  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
  </script>
<script>
$(document).ready(function(){
$("body").on('click', '.custom-file-upload', function(){
    $(this).next().trigger('click');
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
        url: "{{ route('upload-employee') }}",
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

$('.repeater').repeater({
  initEmpty: false,
  defaultValues: {
      'text-input': 'foo'
  },
  show: function () {
      $(this).slideDown();
      formatlar()
  },
  hide: function (deleteElement) {
      $(this).slideUp(deleteElement);
  }
});

  $('body').on('click', '.sign-bordro', function(){
    var id = $(this).attr('data-id');
    $.ajax({
      type: "POST",
      url: "{{ route('bordro-sign') }}",
      dataType: 'json',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: 'id=' + id,
      dataType: 'json',
      success: function(item){

        toastr.success('Başarılı. Yenileniyor...');
        setTimeout(function(){
          location.reload();
        }, 750);
      }
    });
  });

  $('[name=kt_user_add_user_avatar]').change(function () {
    var file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
      $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
    }
  });

  $('.pick-date').datepicker({
      todayHighlight: true,
      autoclose: true,
      format: 'dd-mm-yyyy'
  });
  $(".date-format").inputmask("99-99-9999");

  $("body").on('change', '.change-payment', function(){
    var id = $(this).attr('data-user');
    var month = $(this).attr('data-month');
    var year = $(this).attr('data-year');
    var val = $(this).val();
      $.ajax({
        url: "{{ route('update-wage') }}",
        data: "id=" + id + "&month=" + month + "&year="+year + "&val="+val,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          if(data.status){
							toastr.success(data.message);
          }else{
            swal.fire({
              "title": "",
              "text": data.message,
              "type": "warning",
              "confirmButtonClass": "btn btn-secondary"
            });
          }
        }
      });
  });
});

</script>

@endsection

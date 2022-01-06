{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="container">
<!--Begin::App-->
  <div class="d-flex flex-row">
    <!--Begin:: App Aside-->
    @include('management.users.components.left')

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
  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "order": [[ 2, "desc" ]],
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
  
$('[name=profile_avatar]').change(function () {
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
$(document).ready(function(){
  $('.firm-form').ajaxForm({ 
    beforeSubmit:  function(formData, jqForm, options){
					var val = null;
        $(".formprogress").show();
        $( ".required", jqForm ).each(function( index ) {
          if(!$(this).val()){
            val = 1;
            $(this).addClass('is-invalid').addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
            $(this).closest('.form-group').find('.invalid-feedback').html("Bu alan zorunludur.");
            $(this).closest('.form-group').addClass('invalid-select');
          }else{
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').removeClass('invalid-select');
            $(this).closest('.form-group').find('.invalid-feedback').html(".");
          }
        });
        if(val){
          KTUtil.scrollTop();
        }
    },
    error: function(){
          swal.fire({
            text: "Dikkat! Sistemsel bir hata nedeniyle kaydedilemedi!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Tamam",
            customClass: {
              confirmButton: "btn font-weight-bold btn-light-primary"
            }
          }).then(function() {
            KTUtil.scrollTop();
          });
						$(".formprogress").hide();
    },
    dataType:  'json', 
    success:   function(item){
      $(".formprogress").hide();
      if(item.status){
          swal.fire({
          html: item.message,
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "Tamam",
          customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
          }
        }).then(function() {
          window.location.href = item.redirect;
        });
      }else{
        $('.is-invalid').removeClass('is-invalid').closest('.form-group').find('.invalid-feedback').hide();
        $.each(item.errors, function(key, value) {
          $("[name="+key+"]").addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
          $.each(value, function(k, v) {
            $("[name="+key+"]").closest('.form-group').find('.invalid-feedback').append(v + "<br>");
          });
        });

        swal.fire({
          html: item.message,
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Tamam",
          customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
          }
        }).then(function() {
          KTUtil.scrollTop();
        });
      }
    }
	}); 
}); 
</script>
@endsection

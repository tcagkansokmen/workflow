{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="card card-custom card-stretch">
			<!--begin::Header-->
			<div class="card-header py-3">
				<div class="card-title align-items-start flex-column">
					<h3 class="card-label font-weight-bolder text-dark">Kişisel Bilgilerim</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">Parola veya isminizi düzenleyebilirsiniz.</span>
                </div>
			</div>
			<!--end::Header-->

			<!--begin::Form-->
        <form class="form firm-form" method="post" action="/user/kaydet">
        @csrf
				<!--begin::Body-->
				<div class="card-body">
					<div class="row">
						<label class="col-xl-3"></label>
						<div class="col-lg-9 col-xl-6">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label text-right">Fotoğraf</label>
						<div class="col-lg-9 col-xl-6">
                <div class="image-input image-input-outline" id="kt_image_4" style="margin:10px 0px 30px;">
                @isset(Auth::user()->avatar)
                  <div class="image-input-wrapper" style="background-image: url({{ Storage::url('uploads/users/') }}{{ Auth::user()->avatar }})"></div>
                @else 
                  <div class="image-input-wrapper" style="background-image: url(/users/100_1.jpg)"></div>
                @endisset

                  <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                    <i class="fa fa-pen icon-sm text-muted"></i>
                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
                    <input type="hidden" name="profile_avatar_remove"/>
                  </label>

                  <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                  </span>

                  <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                  </span>
                </div>
                </div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label text-right">İsim</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control form-control-lg form-control-solid" name="name" type="text" value="{{ Auth::user()->name }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label text-right">Soyisim</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control form-control-lg form-control-solid" type="text" name="surname" value="{{ Auth::user()->surname }}">
						</div>
          </div>
          <hr>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label text-right">Yeni Parola</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control form-control-lg form-control-solid" type="password" name="password">
						</div>
          </div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label text-right">Yeni Parola< (Tekrar)</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control form-control-lg form-control-solid" type="password" name="password_repeat">
						</div>
          </div>
				</div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
            <a href="{{ $redirect }}" class="btn btn-secondary">Vazgeç</a>
          </div>
				<!--end::Body-->
			</form>
			<!--end::Form-->
		</div>

<!-- end:: Content -->

@endsection

{{-- Styles Section --}}
@section('styles')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script>
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

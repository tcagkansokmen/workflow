{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
.select2{
  width:100% !important;
}
table td{
  vertical-align:middle !important;
}
</style>
<style>
.file_side {
position: relative;
overflow: hidden;
display: inline-block;
cursor:pointer;
}

.file_side input[type=file] {
font-size: 100px;
position: absolute;
left: 0;
top: 0;
opacity: 0;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap pt-6 pb-0">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>

  <form class="form general-form form--label-right" method="POST" action="{{ route('save-candidate') }}" >
  @csrf
    <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
    <input type="hidden" name="demand_id" value="{{ $demand->id ?? '' }}">
    <div class="card-body">
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Fotoğraf</label>
        <div class="col-lg-9 col-xl-6">
        <div class="image-input image-input-outline" id="kt_image_4" style="margin:10px 0px 30px;">
          @isset($detail->photo)
            <div class="image-input-wrapper" style="background-image: url({{ Storage::url('uploads/candidate') }}/{{ $detail->photo ?? 'avatar.png' }}); background-size:contain; background-position:50% 50%;"></div>
          @else 
            <div class="image-input-wrapper"></div>
          @endisset
            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Logo">
              <i class="fa fa-pen icon-sm text-muted"></i>
              <input type="file" name="photo" accept=".png, .jpg, .jpeg"/>
              <input type="hidden" name="profile_avatar_remove"/>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* İsim/Soyisim</label>
        <div class="col-lg-3">
          <input type="text" name="name" class="form-control" value="{{ $detail->name ?? '' }}">
        </div>
        <div class="col-lg-3">
          <input type="text" name="surname" class="form-control" value="{{ $detail->surname ?? '' }}">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Doğum Tarihi</label>
        <div class="col-lg-6">
          <input type="text" name="birthdate" class="form-control date-format" value="{{ isset($detail->birthdate) ? date('d-m-Y', strtotime($detail->birthdate)) : '' }}">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Telefon</label>
        <div class="col-lg-3">
          <input type="text" name="phone" placeholder="Telefon" class="form-control phone" value="{{ $detail->phone ?? '' }}">
        </div>
        <div class="col-lg-3">
          <input type="text" name="phone_2" placeholder="Telefon 2" class="form-control phone" value="{{ $detail->phone_2 ?? '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* E-posta</label>
        <div class="col-lg-6">
          <input type="text" name="email" placeholder="E-posta" class="form-control" value="{{ $detail->email ?? '' }}">
        </div>
      </div>

      <div class="city-wrapper">
        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* İl/İlçe</label>
          <div class="col-lg-4">
            <div class="form-group">
              @component('components.forms.select', [
                  'required' => true,
                  'name' => 'city',
                  'value' => $detail->city ?? '',
                  'values' => $iller ?? array(),
                  'class' => 'select2-standard city'
              ])
              @endcomponent
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              @component('components.forms.select', [
                  'required' => true,
                  'name' => 'state',
                  'value' => $detail->state ?? '',
                  'values' => $ilceler ?? array(),
                  'class' => 'select2-standard ilce'
              ])
              @endcomponent
            </div>
          </div>
        </div>
      </div>

      <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">Referans Adı/Soyadı</label>
          <div class="col-lg-3">
            <input type="text" name="reference" placeholder="Referans" class="form-control" value="{{ $detail->reference ?? '' }}">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Özgeçmiş</label>
          <div class="col-lg-3">
            <div class="custom-file">
						  	<input type="file" name="cv" accept=".pdf" class="custom-file-input" id="customFile">
						  	<label class="custom-file-label" style="text-align:left !important;" for="customFile">PDF Dosyası seçiniz</label>
						</div>
            @isset($detail->cv)
            <a href='{{ Storage::url("uploads/candidate") }}/{{ $detail->cv }}' target="_blank" class="btn btn- btn-light-primary mt-10"><i class="flaticon2-sheet icon-md text-primary"></i> Görüntüle</a>
            @endisset
          </div>
        </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-9 col-xl-9 offset-lg-3">
            <div class="d-flex">
            <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </form>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
  <script>
  $('[name=photo]').change(function () {
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
    $('.summernote').summernote({
    height: 150
    });
  })
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
                          qrform.ajax.reload();
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
  </script>
@endsection

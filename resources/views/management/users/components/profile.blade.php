@extends('management.users.detail')

@section('inside')
<div class="card card-custom card-stretch" style="width:100%;">
  <!--begin::Header-->
  <div class="card-header py-3">
    <div class="card-title align-items-start flex-column">
      <h3 class="card-label font-weight-bolder text-dark">Kişisel Bilgiler</h3>
      <span class="text-muted font-weight-bold font-size-sm mt-1">Personelin bilgileri</span>
  </div>
  </div>
  <!--end::Header-->

  <!--begin::Form-->
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
          @isset($detail->avatar)
            <div class="image-input-wrapper" style="background-image: url({{ Storage::url('uploads/users/') }}{{ $detail->avatar }})"></div>
          @else 
            <div class="image-input-wrapper" style="background-image: url(/users/100_1.jpg)"></div>
          @endisset

            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
              <i class="ki ki-bold-close icon-xs text-muted"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">İsim</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" name="name" disabled type="text" value="{{ $detail->name }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Soyisim</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="text" disabled name="surname" value="{{ $detail->surname }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Adres</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="text" disabled name="address" value="{{ $detail->address }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Telefon</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid phone" type="text" disabled name="phone" value="{{ $detail->phone }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Kan Grubu</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="text" name="blood" disabled value="{{ $detail->blood }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Acil Durumda Aranacak</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid phone" type="text" disabled name="emergency" value="{{ $detail->emergency }}">
        </div>
      </div>
    </div>
    <!--end::Body-->
  <!--end::Form-->
</div>

<!-- end:: Content -->

@endsection
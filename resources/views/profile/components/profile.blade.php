@extends('profile.detail')

@section('inside')
<div class="card card-custom card-stretch" style="width:100%;">
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

            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Fotoğrafı Değiştir">
              <i class="fa fa-pen icon-sm text-muted"></i>
              <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
              <input type="hidden" name="profile_avatar_remove"/>
            </label>

            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
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
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Adres</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="text" name="address" value="{{ Auth::user()->address }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Telefon</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid phone" type="text" name="phone" value="{{ Auth::user()->phone }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Kan Grubu</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="text" name="blood" value="{{ Auth::user()->blood }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Acil Durumda Aranacak</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid phone" type="text" name="emergency" value="{{ Auth::user()->emergency }}">
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
        <label class="col-xl-3 col-lg-3 col-form-label text-right">Yeni Parolaz (Tekrar)</label>
        <div class="col-lg-9 col-xl-6">
          <input class="form-control form-control-lg form-control-solid" type="password" name="password_repeat">
        </div>
      </div>
    </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
      </div>
    <!--end::Body-->
  </form>
  <!--end::Form-->
</div>

<!-- end:: Content -->

@endsection
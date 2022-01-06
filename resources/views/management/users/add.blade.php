{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="d-flex flex-row">
  <!--begin::Content-->
  <div class="flex-row-fluid ml-lg-8">
    <div class="card card-custom card-stretch">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-dark">{{ $page_title }}</h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1">{{ $page_description }}</span>
        </div>
        <div class="card-toolbar">
          <a href="{{ route('users') }}" class="btn btn-success">Geri</a>
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
        <!--begin::Body-->
        <div class="card-body">
          <form action="{{ route('user-save') }}" class="general-form" method="POST">
            <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
            @csrf
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Yetki</label>
            <div class="col-lg-9 col-xl-6">
              @component('components.forms.select', [
                'required' => true,
                'name' => 'group_id',
                'value' => $detail->group_id ?? '',
                'values' => $groups ?? array(),
                'class' => 'select2-standard change-group'
                ])
              @endcomponent
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Unvan</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="title" value="{{ $detail->title ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">İsim</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="name" value="{{ $detail->name ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Soyisim</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="surname" value="{{ $detail->surname ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">E-mail</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="email" value="{{ $detail->email ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Adres</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="address" value="{{ $detail->address ?? null }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Telefon</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid phone" type="text" name="phone" value="{{ $detail->phone ?? null }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Kan Grubu</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="blood" value="{{ $detail->blood ?? null }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Acil Durumda Aranacak</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid phone" type="text" name="emergency" value="{{ $detail->emergency ?? null }}">
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-right">Parola</label>
            <div class="col-lg-9 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="password" name="password">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3 offset-sm-3">
              <button class="btn btn-success" type="submit">Kaydet</button>
            </div>
          </div>
          </form>
        </div>
        <!--end::Body-->
      <!--end::Form-->
    </div>
  </div>
  <!--end::Content-->
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
$(document).ready(function(){
  $('body').on('change', '.change-group', function(){
    if($(this).val()=='8'){
      $('.fleets').removeClass('d-none');
    }else{
      $('.fleets').addClass('d-none');
    }
  });
});
</script>
@endsection

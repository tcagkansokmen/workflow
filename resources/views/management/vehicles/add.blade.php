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
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
        <!--begin::Body-->
        <div class="card-body">
          <form action="{{ route('vehicle-save') }}" class="general-form" method="POST">
            <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
            @csrf
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Marka</label>
            <div class="col-lg-6 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="brand" value="{{ $detail->brand ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Model</label>
            <div class="col-lg-6 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="model" value="{{ $detail->model ?? '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Plaka</label>
            <div class="col-lg-6 col-xl-6">
              <input class="form-control form-control-lg form-control-solid" type="text" name="plate" value="{{ $detail->plate ?? '' }}">
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Kiralık mı?</label>
            <div class="col-lg-6 col-xl-6">
              <label class="checkbox">
                  <input type="checkbox" name="is_loan" value="1" {{ isset($detail->is_loan)&&$detail->is_loan==1 ? 'checked' : ''  }} />
                  <span></span>
              </label>
            </div>
          </div>
          <div class="form-group row if-loan {{ isset($detail->is_loan)&&$detail->is_loan==1 ? '' : 'd-none'  }}">
            <label class="col-xl-3 col-lg-3 col-form-label">Kiralık Bitiş</label>
            <div class="col-lg-6 col-xl-6">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="loan_end" value="{{ isset($detail->is_loan) ? date_formatter($detail->loan_end) : '' }}">
            </div>
          </div>

          <div class="form-group row not-loan {{ isset($detail->is_loan)&&$detail->is_loan==1 ? 'd-none' : ''  }}">
            <label class="col-xl-3 col-lg-3 col-form-label">Kasko Başlangıç/Bitiş</label>
            <div class="col-lg-3 col-xl-3">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="kasko_start" value="{{ isset($detail->kasko_start) ? date_formatter($detail->kasko_start) : '' }}">
            </div>
            <div class="col-lg-3 col-xl-3">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="kasko_end" value="{{ isset($detail->kasko_end) ? date_formatter($detail->kasko_end) : '' }}">
            </div>
          </div>

          <div class="form-group row not-loan {{ isset($detail->is_loan)&&$detail->is_loan==1 ? 'd-none' : ''  }}">
            <label class="col-xl-3 col-lg-3 col-form-label">Sigorta Başlangıç/Bitiş</label>
            <div class="col-lg-3 col-xl-3">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="insurance_start" value="{{ isset($detail->insurance_start) ? date_formatter($detail->insurance_start) : '' }}">
            </div>
            <div class="col-lg-3 col-xl-3">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="insurance_end" value="{{ isset($detail->insurance_end) ? date_formatter($detail->insurance_end) : '' }}">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Sıradaki Bakım</label>
            <div class="col-lg-6 col-xl-6">
              <input class="form-control form-control-lg date-format form-control-solid" type="text" name="care_date" value="{{ isset($detail->care_date) ? date_formatter($detail->care_date) : '' }}">
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-sm-3 offset-sk-9">
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
  $('body').on('change', '[name=is_loan]', function(){
    if($(this).is(':checked')){
      $('.if-loan').removeClass('d-none');
      $('.not-loan').addClass('d-none');
    }else{
      $('.if-loan').addClass('d-none');
      $('.not-loan').removeClass('d-none');
    }
  });
});
</script>
@endsection

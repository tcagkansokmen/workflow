{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="container">
  <!--begin::Profile Overview-->
  <div class="d-flex flex-row">
    <!--begin::Aside-->
      @include('hr.notice.candidate.left')
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
      <!--begin::Advance Table: Widget 7-->
      <div class="card card-custom">
        <div class="card-header flex-wrap pt-2 pb-2">
            <div class="card-title">
                <h3 class="card-label">Teklifler
                </h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <form class="kt-form general-form" method="POST" action="{{ route('save-candidate-offer') }}" >
            @csrf
            <input type="hidden" name="id" value="{{ $offer->id ?? '' }}">
            <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
               <div class="card-body">
                <div class="kt-section kt-section--first">
                  <div class="kt-section__body">
                    <div class="form-group row" style="margin-top:55px;">
                      <label class="col-xl-3 col-lg-3 col-form-label">* Teklif Tarihi</label>
                      <div class="col-lg-9">
                          <input class="form-control date-format" type="text" required name="offer_date" value="{{ isset($offer->offer_date) ? date('d-m-Y', strtotime($offer->offer_date)) : '' }}" placeholder="Tariih seçiniz">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xl-3 col-lg-3 col-form-label">* Brüt Ücret</label>
                      <div class="col-lg-9">
                          <input type="text" value="{{ isset($offer->price) ? number_format($offer['price'], 2, ",", ".") : '' }}" name="price" class="form-control money-format">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-xl-3 col-lg-3 col-form-label">* Notlar</label>
                      <div class="col-lg-9">
                          <textarea class="form-control summernote" rows="5" type="text" required name="notes"  placeholder="Açıklama">{{ $offer->notes ?? '' }}</textarea>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
              <div class="row">
                <div class="col-lg-3 col-xl-3">
                </div>
                <div class="col-lg-3 col-xl-3" style="display:flex; justify-content:space-between">
                  <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet</button>
                </div>
              </div>
            </form>
        </div>
        <!--end::Body-->
      </div>
      <!--end::Advance Table Widget 7-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Profile Overview-->
  </div>

  @endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
  <script>
  
  $("body").on('click', '.teklif-guncelle-2', function() {
      var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
      if (r == true) {
          var id = $(this).attr('data-id');
          var value = $(this).attr('data-value');
          $.ajax({
              url: "{{ route('update-candidate-offer-status') }}",
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
    </script>
@endsection

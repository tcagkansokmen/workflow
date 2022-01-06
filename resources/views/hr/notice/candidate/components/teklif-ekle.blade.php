@extends('hr.candidate.detail')

@include('common.subheader', 
  [
    'title' => 'Başlık', 
    'subtitle' => 'Altbaşlık', 
    'url' => route('aday-teklif', ['candidate_id' => $detail->id])
  ]
)

@section('inside')
<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">Aday Bilgileri <small>personele ait hesap bilgilerini görütnüleyebilirsiniz.</small></h3>
          </div>
          <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
            </div>
          </div>
        </div>
           <div class="card-body">
            <form class="kt-form general-form" method="POST" action="{{ route('aday-teklif-kaydet') }}" >
            @csrf
            <input type="hidden" name="id" value="{{ $offer->id ?? '' }}">
            <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
               <div class="card-body">
                <div class="kt-section kt-section--first">
                  <div class="kt-section__body">
                    <div class="form-group row" style="margin-top:55px;">
                      <label class="col-xl-3 col-lg-3 col-form-label">* Teklif Tarihi</label>
                      <div class="col-lg-9">
                          <input class="form-control pick-date" type="text" required name="offer_date" value="{{ isset($offer->offer_date) ? date('d-m-Y', strtotime($offer->offer_date)) : '' }}" placeholder="Tariih seçiniz">
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
              </div>
              <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                  <div class="row">
                    <div class="col-lg-3 col-xl-3">
                    </div>
                    <div class="col-lg-3 col-xl-3" style="display:flex; justify-content:space-between">
                      <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="kt-portlet__foot">
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
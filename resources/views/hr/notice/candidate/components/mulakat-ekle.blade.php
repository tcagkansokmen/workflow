@extends('hr.candidate.detail')

@include('common.subheader', 
  [
    'title' => 'Başlık', 
    'subtitle' => 'Altbaşlık', 
    'url' => route('mulakat', ['candidate_id' => $detail->id])
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
          <form class="kt-form general-form" method="POST" action="{{ route('mulakat-kaydet') }}" >
          @csrf
          <input type="hidden" name="id" value="{{ $interview->id ?? '' }}">
          <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
          <input type="hidden" name="type" value="rating">
           <div class="card-body">
            <div class="form-group row" style="margin-top:55px;">
              <label class="col-xl-3 col-lg-3 col-form-label">* Ölçülecek Yetkinlikler</label>
              <div class="col-lg-9">
                  <select name="perfections[]" id="" multiple class="select2">
                    <option value="">Seçiniz</option>
                    @foreach($perfections as $p)
                      <option value="{{ $p->id }}"
                      @isset($perfection_interviews) 
                        @if(in_array($p->id, json_decode($perfection_interviews, true)))
                          selected
                        @endif
                      @endisset
                      >{{ $p->name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">* İnsan Kaynakları</label>
              <div class="col-lg-9">
                  <select name="hr_id" id="" class="select2">
                    <option value="">Seçiniz</option>
                    @foreach($users as $u)
                      <option value="{{ $u->id }}"
                      @isset($interview->hr_id) 
                        @if($interview->hr_id == $u->id)
                          selected
                        @endif
                      @endisset
                      >{{ $u->name }} {{ $u->surname }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">* Başlangıç/Bitiş</label>
              <div class="col-lg-3">
                  <input class="form-control pick-date" type="text" required name="start_at" value="{{ isset($interview->start_at) ? date('d-m-Y', strtotime($interview->start_at)) : '' }}" placeholder="Başlangıç">
              </div>
              <div class="col-lg-3">
                <div class="input-group timepicker">
                  <input 
                    class="form-control pick-time" 
                    readonly 
                    placeholder="Başlangıç Saati" 
                    name="start_time" 
                    type="text" 
                    value="{{ isset($interview->start_at) ? date('H:i', strtotime($interview->start_at)) : '09:00' }}" />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="la la-clock-o"></i>
                      </span>
                    </div>
                  </div>
              </div>
              <div class="col-lg-3">
                <div class="input-group timepicker">
                  <input 
                    class="form-control pick-time" 
                    readonly 
                    placeholder="Başlangıç Saati" 
                    name="end_time" 
                    type="text" 
                    value="{{ isset($interview->end_at) ? date('H:i', strtotime($interview->end_at)) : '09:00' }}" />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="la la-clock-o"></i>
                      </span>
                    </div>
                  </div>
              </div>
            </div>

          </div>
          <div class="kt-portlet__foot">
          <button class="btn btn-success" type="submit">Kaydet</button>
          </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
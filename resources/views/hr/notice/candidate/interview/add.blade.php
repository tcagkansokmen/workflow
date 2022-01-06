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
                <h3 class="card-label">{{ $page_title ?? null }}
                  <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
                </h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
          <form class="kt-form general-form" method="POST" action="{{ route('save-interview') }}" >
          @csrf
          <input type="hidden" name="id" value="{{ $interview->id ?? '' }}">
          <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
          <input type="hidden" name="type" value="rating">
           <div class="card-body">
            <div class="form-group row" style="margin-top:55px;">
              <label class="col-xl-3 col-lg-3 col-form-label">* Ölçülecek Yetkinlikler</label>
              <div class="col-lg-9">
                  <select name="perfections[]" id="" multiple class="select2-standard form-control">
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
                  <select name="hr_id" id="" class="select2-standard form-control">
                    <option value="">Seçiniz</option>
                    @foreach($users as $u)
                      <option value="{{ $u->id }}"
                      @isset($interview->hr_id) 
                        @if($interview->hr_id==$u->id)
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
                  <input class="form-control date-format" type="text" required name="start_at" value="{{ isset($interview->start_at) ? date('d-m-Y', strtotime($interview->start_at)) : '' }}" placeholder="Başlangıç">
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
  $('.pick-time').timepicker({
      minuteStep: 15,
      defaultTime: '09:00',           
      showSeconds: false,
      showMeridian: false,
      snapToStep: true
  }).on('changeTime.timepicker', function(e) {
    var name = $(e.target).attr('name');

    if(name == 'start_time'){
      $("[name=end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }

    if(name == 'real_start_time'){
      $("[name=real_end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }
  });
</script>
@endsection

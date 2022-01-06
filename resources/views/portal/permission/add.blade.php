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
<!-- begin:: Content -->
<div class="container">
  <div class="card">
    <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">İzin Talebi</h3>
      </div>
      <div class="card-toolbar">
      </div>
    </div>
     <div class="card-body">
      <form class="kt-form general-form kt-form--label-right" method="POST" action="{{ route('personel-izin-kaydet') }}" >
      @csrf
      <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* İzin Türü</label>
            <div class="col-lg-6">
              <select name="type" id="" class="select2-standard form-control">
                @foreach($types as $t)
                  <option 
                  @isset($detail->type)
                  @if($detail->type == $t->id)
                    selected
                  @endif
                  @endisset
                  value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Başlangıç Tarihi</label>
            <div class="col-lg-3">
                <input class="form-control pick-date" type="text" required name="start_at" value="{{ isset($detail->start_at) ? date('d-m-Y', strtotime($detail->start_at)) : '' }}" placeholder="Başlangıç">
            </div>
            <div class="col-lg-3">
              <div class="input-group timepicker">
                <input 
                  class="form-control pick-time" 
                  readonly 
                  placeholder="Başlangıç Saati" 
                  name="start_time" 
                  type="text" 
                  value="{{ isset($detail->start_at) ? date('H:i', strtotime($detail->start_at)) : '09:00' }}" />
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="la la-clock-o"></i>
                    </span>
                  </div>
                </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Bitiş Tarihi</label>
            <div class="col-lg-3">
                <input class="form-control pick-date" type="text" required name="end_at" value="{{ isset($detail->end_at) ? date('d-m-Y', strtotime($detail->end_at)) : '' }}" placeholder="Bitiş">
            </div>
            <div class="col-lg-3">
              <div class="input-group timepicker">
                <input 
                  class="form-control pick-time" 
                  readonly 
                  placeholder="Bitiş Saati" 
                  name="end_time" 
                  type="text" 
                  value="{{ isset($detail->end_at) ? date('H:i', strtotime($detail->end_at)) : '18:00' }}" />
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <i class="la la-clock-o"></i>
                    </span>
                  </div>
                </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Açıklama</label>
            <div class="col-lg-6">
              <textarea name="description" id="" cols="30" rows="10" class="form-control">{{ $detail->description ?? '' }}</textarea>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-3 col-xl-3"></div>
            <div class="col-lg-9 col-xl-9">
              <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="/assets/js/pages/crud/forms/editors/summernote.js"></script>
<script>
$(document).ready(function(){
var datesToDisable = $('.pick-date').data("datesDisabled");
if(datesToDisable){
    datesToDisable = datesToDisable.split(',');
}else{
    datesToDisable = false;
}

$(".pick-date").inputmask("99-99-9999");
$('.pick-date').datepicker({
    orientation: "bottom right",
    allowInputToggle: true,
    todayHighlight: true,
    autoclose: true,
    format: 'dd-mm-yyyy',
    language: 'tr'
}).on("show", function(event) {
        if(datesToDisable){
            $(".day").each(function(index, element) {
                var el = $(element);
                var dat = $(this).attr('data-date');
                var date = new Date(parseInt(dat));
                var month = (date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1);
                var year = date.getFullYear();
                
                var hideMonth = $.grep( datesToDisable, function( n, i ) {
                    if(n.substr(3, 4) == year && n.substr(0, 2) == month){
                        el.addClass('disabled');
                    }
                });
            });
        }
    });
  $('.pick-time').timepicker({
      minuteStep: 15,
      defaultTime: '09:00',           
      showSeconds: false,
      showMeridian: false,
      snapToStep: true
  });
});
</script>
@endsection

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
          <h3 class="card-label">Seyahat Talebi</h3>
      </div>
      <div class="card-toolbar">
      </div>
    </div>
     <div class="card-body">
     <form class="form general-form form--label-right" method="POST" action="{{ route('personel-vize-kaydet') }}" >
      @csrf
      <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Seyahat Türü</label>
            <div class="col-lg-6">
              <select name="type" id="" class="select2-standard seyahat-type">
                <option value="">Seçiniz</option>
                <option value="Proje Çalışması"
                @isset($detail->type)
                  @if($detail->type == 'Proje Çalışması')
                    selected
                  @endif
                @endisset
                >Proje Çalışması</option>
                <option value="İş Geliştirme"
                @isset($detail->type)
                  @if($detail->type == 'İş Geliştirme')
                    selected
                  @endif
                @endisset
                >İş Geliştirme</option>
                <option value="Eğitim"
                @isset($detail->type)
                  @if($detail->type == 'Eğitim')
                    selected
                  @endif
                @endisset
                >Eğitim</option>
                <option value="Özel"
                @isset($detail->type)
                  @if($detail->type == 'Özel')
                    selected
                  @endif
                @endisset
                >Özel</option>
                <option value="Konferans"
                @isset($detail->type)
                  @if($detail->type == 'Konferans')
                    selected
                  @endif
                @endisset
                >Konferans</option>
              </select>
            </div>
          </div>
          <div class="form-group row ">
            <label class="col-xl-3 col-lg-3 col-form-label">* Yer</label>
            <div class="col-lg-4">
              <div class="radio-inline">
                <label class="radio radio--bold radio--success">
                  <input type="radio" name="place" value="Yurt İçi"
                  @if(isset($detail['place']))
                    @if($detail['place']=='Yurt İçi')
                      checked
                    @endif
                  @endif>
                  <span></span> Yurt İçi
                </label>
                <label class="radio radio--bold radio--success">
                  <input type="radio" name="place" value="Yurt Dışı"
                  @if(isset($detail['place']))
                    @if(!$detail['place']=='Yurt Dışı')
                      checked
                    @endif
                  @endif>
                  <span></span> Yurt Dışı
                </label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Talep Edilen</label>
            <div class="col-lg-6">
              <select name="demand" id="" class="select2-standard">
                <option value="">Seçiniz</option>
                <option value="Vize Evrakları"
                @isset($detail->demand)
                  @if($detail->demand == 'Vize Evrakları')
                    selected
                  @endif
                @endisset
                >Vize Evrakları</option>
                <option value="Uçak Bileti"
                @isset($detail->demand)
                  @if($detail->demand == 'Uçak Bileti')
                    selected
                  @endif
                @endisset
                >Uçak Bileti</option>
                <option value="Otel"
                @isset($detail->demand)
                  @if($detail->demand == 'Otel')
                    selected
                  @endif
                @endisset
                >Otel</option>
                <option value="Transfer"
                @isset($detail->demand)
                  @if($detail->demand == 'Transfer')
                    selected
                  @endif
                @endisset
                >Transfer</option>
              </select>
            </div>
          </div>
          <div class="form-group row d-none project-code">
            <label class="col-xl-3 col-lg-3 col-form-label">* Proje Kodu</label>
            <div class="col-lg-3">
                <input class="form-control" type="text" name="project_code" value="{{ $detail->project_code ?? '' }}" placeholder="">
            </div>
          </div>
          <div class="form-group row d-none firm-code">
            <label class="col-xl-3 col-lg-3 col-form-label">* Firma Kodu</label>
            <div class="col-lg-3">
                <input class="form-control" type="text" name="firm_code" value="{{ $detail->firm_code ?? '' }}" placeholder="">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Seyahat Başlangıç Tarihi</label>
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
            <label class="col-xl-3 col-lg-3 col-form-label">* Seyahat Bitiş Tarihi</label>
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
            <label class="col-xl-3 col-lg-3 col-form-label">* Ülke</label>
            <div class="col-lg-6">
              <input type="text" name="country" value="{{ $detail->country ?? '' }}" class="form-control">
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
  $('body').on('change', '.seyahat-type', function(){
    var val = $(this).val();

    if(val=='İş Geliştirme'){
      $('.firm-code').removeClass('d-none');
    }else{
      $('.firm-code').addClass('d-none');
      $('.firm-code input').val('');
    }

    if(val=='Proje Çalışması'){
      $('.project-code').removeClass('d-none');
    }else{
      $('.project-code').addClass('d-none');
      $('.project-code input').val('');
    }
  });

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

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
          <h3 class="card-label">Masraf Talebi</h3>
      </div>
      <div class="card-toolbar">
      </div>
    </div>
     <div class="card-body">
      <form class="kt-form general-form kt-form--label-right" method="POST" action="{{ route('personel-avans-kaydet') }}" >
      @csrf
      <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
        <div class="card-body">
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Kategori</label>
          <div class="col-lg-6">
            <select name="category" id="" class="select2-standard">
              <option value="">Seçiniz</option>
              <option 
              @isset($detail->category)
                @if($detail->category == 'İş Avansı')
                  selected
                @endif
              @endisset
              value="İş Avansı">İş Avansı</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Masraf Tutarı</label>
          <div class="col-lg-6">
            <input type="text" name="price" class="form-control money-format" value="{{ isset($detail->price) ? number_format($detail->price, 2, ",", ".") : ''  }}">
          </div>
        </div>

        <div class="form-group row d-none">
          <label class="col-xl-3 col-lg-3 col-form-label">* Masraf Taksit Sayısı</label>
          <div class="col-lg-6">
            <select name="installments" id="" class="select2-standard">
              @for($i = 1; $i<=12; $i++)
                <option 
                @isset($detail->installments)
                @if($detail->installments == $i)
                  selected
                @endif
                @endisset
                value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Açıklama</label>
          <div class="col-lg-6">
            <textarea name="reason" id="" cols="30" rows="10" class="form-control">{{ $detail->reason ?? '' }}</textarea>
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

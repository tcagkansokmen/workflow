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
<div class="card card-custom">
    <div class="card-header flex-wrap">
        <div class="card-title">
            <h3 class="card-label">İhtiyaç Talebi
                <div class="text-muted pt-2 font-size-sm"></div>
            </h3>
        </div>
        <div class="card-toolbar">
        </div>
    </div>
     <div class="card-body">
     <form class="form general-form form--label-right" method="POST" action="{{ route('personel-ihtiyac-kaydet') }}" >
      @csrf
      <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Kategori</label>
            <div class="col-lg-6">
              <select name="category" id="" class="select2-standard">
                <option value="">Seçiniz</option>
                <option value="Ekipman"
                @isset($detail->category)
                  @if($detail->category == 'Ekipman')
                    selected
                  @endif
                @endisset
                >Ekipman</option>
                <option value="Kırtasiye"
                @isset($detail->category)
                  @if($detail->category == 'Kırtasiye')
                    selected
                  @endif
                @endisset
                >Kırtasiye</option>
                <option value="Genel"
                @isset($detail->category)
                  @if($detail->category == 'Genel')
                    selected
                  @endif
                @endisset
                >Genel</option>
                <option value="Diğer"
                @isset($detail->category)
                  @if($detail->category == 'Diğer')
                    selected
                  @endif
                @endisset
                >Diğer</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Öncelik</label>
            <div class="col-lg-6">
              <select name="priority" id="" class="select2-standard">
                <option value="">Seçiniz</option>
                <option value="Acil"
                @isset($detail->priority)
                  @if($detail->priority == 'Acil')
                    selected
                  @endif
                @endisset
                >Acil</option>
                <option value="Normal"
                @isset($detail->priority)
                  @if($detail->priority == 'Normal')
                    selected
                  @endif
                @else 
                selected
                @endisset
                >Normal</option>
                <option value="Acil Olmayan"
                @isset($detail->priority)
                  @if($detail->priority == 'Acil Olmayan')
                    selected
                  @endif
                @endisset
                >Acil Olmayan</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* İhtiyaç Tarihi</label>
            <div class="col-lg-6">
              <input type="text" name="deadline" value="{{ isset($detail->deadline) ? date('d-m-Y', strtotime($detail->deadline)) : '' }}" class="pick-date form-control">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">* Açıklama</label>
            <div class="col-lg-6">
              <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $detail->description ?? '' }}</textarea>
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
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="/assets/js/pages/crud/forms/editors/summernote.js"></script>
<script>
$(document).ready(function(){
  $('.summernote').summernote({ height: 300 })
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

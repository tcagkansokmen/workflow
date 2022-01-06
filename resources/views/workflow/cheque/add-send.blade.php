{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ $page_title }}
                    <div class="text-muted pt-2 font-size-sm">{{ $page_description }}</div>
                </h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>

        <form class="form general-form" method="post" action="{{ route('save-cheque') }}">
          <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
          <input type="hidden" name="type" value="send">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                  @include('components.forms.input', [
                      'label' => 'Tedarikçi',
                      'type' => 'text',
                      'placeholder' => '',
                      'help' => '',
                      'required' => true,
                      'name' => 'supplier',
                      'class' => '',
                      'value' => $detail['supplier'] ?? null
                  ])
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                  @include('components.forms.input', [
                      'label' => 'Tutar',
                      'type' => 'text',
                      'placeholder' => '',
                      'help' => '',
                      'required' => true,
                      'name' => 'price',
                      'class' => 'money-format',
                      'value' => isset($detail->id) ? number_format($detail->price, 2, ",", ".") : ''
                  ])
                </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                @include('components.forms.input', [
                    'label' => 'Vade Tarihi',
                    'type' => 'text',
                    'placeholder' => '',
                    'help' => '',
                    'class' => 'date-format',
                    'required' => true,
                    'name' => 'deadline',
                    'value' => isset($detail->deadline) ? date('d-m-Y', strtotime($detail->deadline)) : ''
                ])
              </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                  @include('components.forms.input', [
                      'label' => 'Açıklama',
                      'type' => 'text',
                      'placeholder' => '',
                      'help' => '',
                      'required' => true,
                      'name' => 'description',
                      'class' => '',
                      'value' => $detail['description'] ?? null
                  ])
                </div>
            </div>      
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
          </div>
        </form>
    </div>
@endsection

{{-- Styles Section --}}
@section('styles')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script>
$(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
$('.select2-standard').select2({
  placeholder: "Seçiniz"
});

function select2init(){
  $(".phone").inputmask("(999) 999 99-99");  
  $(".tarih").datepicker({
  format: 'dd-mm-yyyy',
  autoclose:true,
  todayHighlight: !0,
  orientation: "bottom left",
  templates: {
      rightArrow: '<i class="la la-angle-right"></i>',
      leftArrow: '<i class="la la-angle-left"></i>'
  }
  });
  $(".tarih").inputmask("99-99-9999");  
}
</script>
@endsection

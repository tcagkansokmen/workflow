{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
            {{ $page_title }}
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-printing-meta')}}" class="general-form" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
              @csrf
                <div class="row">
                  <div class="col-sm-8">
                    <div class="row">
                      <div class="col-sm-12">
                        @include('components.forms.input', [
                            'label' => 'Özellik Başlığı',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'title',
                            'class' => '',
                            'value' => $detail->value ?? null
                        ])
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label style="display:block;">Türü</label>
                          @component('components.forms.select', [
                            'required' => true,
                            'name' => 'input',
                            'value' => $detail->input ?? '',
                            'values' => $input ?? array(),
                            'class' => 'select2-standard change-input-type'
                            ])
                          @endcomponent
                        </div>
                      </div>
                      <div class="col-sm-12 {{ !isset($detail)||$detail->input!='select' ? 'd-none' : '' }} main-values">
                        @include('components.forms.input', [
                            'label' => 'Alabileceği Değerler (Her yeni değer için araya virgül koyup boşluk bırakın)',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => false,
                            'name' => 'values',
                            'class' => '',
                            'value' => isset($detail) ? $detail->options->pluck('value')->implode(', ') : null,
                            'id' => 'my_tagify'
                        ])
                      </div>
                      <div class=" col-sm-12">
                        <hr>
                      </div>
                      <div class="col-sm-12">
                          <button class="btn btn-primary" type="submit">Kaydet</button>
                      </div>
                    </div>
                  </div>
                </div>

              {{-- Scripts Section --}}
            </form>
        </div>
        <!--end::Form-->
    </div>

@endsection

@section('scripts')
<script>
function formatMoney(amount, decimalCount = 2, decimal = ",", thousands = ".") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;


    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};

$(document).ready(function(){

var toEl = document.getElementById('my_tagify');
var tagifyTo = new Tagify(toEl, {
    delimiters: ",", // add new tags when a comma or a space character is entered
    maxTags: 10,
    keepInvalidTags: true, // do not remove invalid tags (but keep them marked as invalid)
    transformTag: function(e) {
        e.class = "tagify__tag tagify__tag--danger"
    },
    dropdown : {
        classname : "color-blue",
        enabled   : 1,
        maxItems  : 15
    }
});

$('body').on('change', '.change-input-type', function(){
  var val = $(this).val();
  if(val == 'input'){
    $('.main-values').addClass('d-none');
  }else{
    $('.main-values').removeClass('d-none');
  }
});

    $('body').on('click', '.add-new-category', function(e){
        e.preventDefault();
        $("#add-category").modal('show');
    });
    $('#add-category').on('show.bs.modal', function(e) {
        $(".getting-categories").select2("close");
    });
    var flg = 0;
    $('.getting-categories').on("select2:open", function () {
    flg++;
    if (flg == 1) {
        $('.add-new-category').remove();
        $(".select2-results").append("<div class='select2-results__option add-new-category'>\
        <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Kategori Ekle</a>\
        </div>");
    }
    });
});
</script>
<script>
$(document).ready(function(){
  $('.category-form').ajaxForm({
    beforeSubmit:  function(formData, jqForm, options){
        var val = null;
        $(".formprogress").show();
        $( ".required", jqForm ).each(function( index ) {
          if(!$(this).val()){
            val = 1;
            $(this).addClass('is-invalid').addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
            $(this).closest('.form-group').find('.invalid-feedback').html("Bu alan zorunludur.");
            $(this).closest('.form-group').addClass('invalid-select');
          }else{
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').removeClass('invalid-select');
            $(this).closest('.form-group').find('.invalid-feedback').html(".");
          }
        });
        if(val){
          KTUtil.scrollTop();
        }
    },
    error: function(){
          swal.fire({
            text: "Dikkat! Sistemsel bir hata nedeniyle kaydedilemedi!",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Tamam",
            customClass: {
              confirmButton: "btn font-weight-bold btn-light-primary"
            }
          }).then(function() {
            KTUtil.scrollTop();
          });
          $(".formprogress").hide();
    },
    dataType:  'json',
    success:   function(item){
      $(".formprogress").hide();
      if(item.status){
        window.location.href = item.redirect;
      }else{
        $('.is-invalid').removeClass('is-invalid').closest('.form-group').find('.invalid-feedback').hide();
        $.each(item.errors, function(key, value) {
          $("[name="+key+"]").addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
          $.each(value, function(k, v) {
            $("[name="+key+"]").closest('.form-group').find('.invalid-feedback').append(v + "<br>");
          });
        });
        swal.fire({
          html: item.message,
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Tamam",
          customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
          }
        }).then(function() {
          KTUtil.scrollTop();
        });
      }
    }
	});
});
</script>
@endsection

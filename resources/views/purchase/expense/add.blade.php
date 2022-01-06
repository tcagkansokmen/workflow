{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Talep Ekleme/Düzenleme
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-expense') }}" class="general-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
                @csrf
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label style="display:block;">* Tedarikçi</label>
                            @component('components.forms.select', [
                            'required' => true,
                            'name' => 'supplier_id',
                            'value' => $detail->supplier_id ?? '',
                            'values' => $suppliers ?? array(),
                            'class' => 'getting-suppliers',
                            ])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        @include('components.forms.input', [
                            'label' => 'Fatura Numarası',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'bill_no',
                            'class' => '',
                            'value' => isset($detail->bill_no) ? $detail->bill_no : null
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        @include('components.forms.input', [
                            'label' => 'Fatura Tarihi',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'bill_date',
                            'class' => 'date-format',
                            'value' => isset($detail->bill_date) ? money_formatter($detail->bill_date) : null
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="">Açıklama</label>
                            <textarea name="description" id="" cols="30" rows="10" class="form-control summernote">{{ $detail->description ?? null }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        @include('components.forms.input', [
                            'label' => 'Fatura Tutarı',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'price',
                            'class' => 'money-format price',
                            'value' => isset($detail->price) ? money_formatter($detail->price) : null
                        ])
                    </div>
                    <div class="col-sm-3">
                        @include('components.forms.input', [
                            'label' => 'KDV Tutarı',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'vat',
                            'class' => 'money-format vat',
                            'value' => isset($detail->vat) ? money_formatter($detail->vat) : null
                        ])
                    </div>
                    <div class="col-sm-3">
                        @include('components.forms.input', [
                            'label' => 'Toplam Tutar',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'total_price',
                            'class' => 'money-format total_price disabled',
                            'value' => isset($detail->price) ? money_formatter($detail->price+$detail->vat) : null,
                            'attribute' => 'disabled'
                        ])
                    </div>
                </div>
                <div class="form-group row" style="margin-top:25px;">
                <div class="col-lg-9">
                <div class="custom-file">
                    <input type="file" name="contract" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Faturayı Ekle</label>
                    @isset($detail->file)
                        <div class="dropzone-item-static dz-processing " style="">
                        <div class="dropzone-file">
                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                            <span>{{ $detail->file }}</span>
                            <strong>(
                            <span><strong><a href="{{ Storage::url('snap/expense/') }}{{ $b->file }}" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
                            </div>
                            <div class="dropzone-error"></div>
                        </div>
                        <div class="dropzone-progress">
                            <div class="progress" style="opacity: 0;">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="dropzone-toolbar">
                        </div>
                        </div>
                    @endisset
                </div>
                    <span class="form-text text-muted">En büyük dosya boyutu 8mb olabilir.</span>
                </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-primary" type="submit">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Form-->
    </div>


    <div class="modal fade" id="add-supplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Tedarikçi Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-supplier')}}" class="supplier-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('purchase.supplier.add-inside')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Ürün Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-product')}}" class="product-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('purchase.product.add-inside')
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
  $(document).ready(function(){
    $('.summernote').summernote({
    height: 350
    });
  })
$('[name=profile_avatar]').change(function () {
  var file = this.files[0];
  var reader = new FileReader();
  reader.onloadend = function () {
     $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
  }
  if (file) {
      reader.readAsDataURL(file);
  } else {
  }
});
</script>
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
    $('body').on('keyup', '.price, .vat', function(e){
        var price = $('.price').val()
        var vat = $('.vat').val()

        price = price.replace('.', '')
        price = price.replace(',', '.')

        vat = vat.replace('.', '')
        vat = vat.replace(',', '.')

        $('.total_price').val(formatMoney(parseFloat(price)+parseFloat(vat)))
    });
});
</script>
<script>
    $(document).ready(function(){
        $('body').on('click', '.add-new-supplier', function(e){
            e.preventDefault();
            $("#add-supplier").modal('show');
        });
        $('#add-supplier').on('show.bs.modal', function(e) {
            $(".getting-suppliers").select2("close");
        });
        var flg = 0;
        $('.getting-suppliers').select2({
            language: {
                inputTooShort: function () {
                    return "Tedarikçi unvanı ile arayın.";
                },
                searching: function () {
                    return "Aranıyor..."
                },
                noResults: function () {
                    return "Sonuç bulunamadı";
                },
            },
            ajax: {
                url: "{{ route('suppliers-select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page
                    };
                },
            },
            placeholder: 'Tedarikçi aratınız',
            minimumInputLength: 1
        });
        $('.getting-suppliers').on("select2:open", function () {
            flg++;
            if (flg == 1) {
                $('.add-new-supplier').remove();
                $(".select2-results").append("<div class='select2-results__option add-new-supplier'>\
                <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Tedarikçi Ekle</a>\
                </div>");
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('body').on('click', '.add-new-product', function(e){
            e.preventDefault();
            $("#add-product").modal('show');
        });
        $('#add-product').on('show.bs.modal', function(e) {
            $(".getting-suppliers").select2("close");
        });
        var flg = 0;
        $('.getting-products').select2({
            language: {
                inputTooShort: function () {
                    return "Ürün adı ile arayın.";
                },
                searching: function () {
                    return "Aranıyor..."
                },
                noResults: function () {
                    return "Sonuç bulunamadı";
                },
            },
            ajax: {
                url: "{{ route('products-select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page
                    };
                },
            },
            placeholder: 'Ürün adı aratınız',
            minimumInputLength: 1
        });
        $('.getting-products').on("select2:open", function () {
            flg++;
            if (flg == 1) {
                $('.add-new-product').remove();
                $(".select2-results").append("<div class='select2-results__option add-new-product'>\
                <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Ürün Ekle</a>\
                </div>");
            }
        });
    });
</script>
<script>
$(document).ready(function(){
    $('.firm-repeater').repeater({
        initEmpty: false,
        defaultValues: {
            'text-input': 'foo'
        },
        show: function () {
        $(this).slideDown();
        $(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
        $('.select2-container').remove();
        $('.select2-container').css('width','100%');
        $('.select2-standard').select2()
        var flg = 0;
        $('.getting-products').select2({
            language: {
                inputTooShort: function () {
                    return "Ürün adı ile arayın.";
                },
                searching: function () {
                    return "Aranıyor..."
                },
                noResults: function () {
                    return "Sonuç bulunamadı";
                },
            },
            ajax: {
                url: "{{ route('products-select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page
                    };
                },
            },
            placeholder: 'Ürün adı aratınız',
            minimumInputLength: 1
        });
        $('.getting-products').on("select2:open", function () {
            flg++;
            $('.select2-results__option').remove();
                $('.add-new-product').remove();
                $(".select2-results").append("<div class='select2-results__option add-new-product'>\
                <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Ürün Ekle</a>\
                </div>");
        });


        var flg = 0;
        $('.getting-suppliers').select2({
            language: {
                inputTooShort: function () {
                    return "Tedarikçi unvanı ile arayın.";
                },
                searching: function () {
                    return "Aranıyor..."
                },
                noResults: function () {
                    return "Sonuç bulunamadı";
                },
            },
            ajax: {
                url: "{{ route('suppliers-select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page
                    };
                },
            },
            placeholder: 'Tedarikçi aratınız',
            minimumInputLength: 1
        });
        $('.getting-suppliers').on("select2:open", function () {
            flg++;
            $('.select2-results__option').remove();
                $('.add-new-supplier').remove();
                $(".select2-results").append("<div class='select2-results__option add-new-supplier'>\
                <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Tedarikçi Ekle</a>\
                </div>");
        });
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
});
</script>
<script>
    $(document).ready(function(){
        $('.supplier-form').ajaxForm({
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
                    $('.modal').modal('hide')
                    swal.fire({
                        html: item.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        var title = item.data.title;
                        var newState = new Option(title, item.data.id, true, true);
                        $('.modal').modal('hide');
                        $(".getting-suppliers").append(newState).trigger('change');
                    });
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
        $('.product-form').ajaxForm({
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
                    $('.modal').modal('hide')
                    swal.fire({
                        html: item.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        console.log(item)
                        var title = item.data.title;
                        var newState = new Option(title, item.data.id, true, true);
                        $('.modal').modal('hide');
                        $(".getting-products").append(newState).trigger('change');
                    });
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
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
            <form action="{{route('save-purchase') }}" class="general-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <h5>Ürün Kalemleri</h5>
                        <p>Satın alma talebindeki ürün kalemlerini girebilirsiniz..</p>
                        <div class="firm-repeater">
                            <div class="form-group row">
                                <div data-repeater-list="products" class="col-lg-12">
                                @isset($detail)
                                @foreach($detail->items as $b)
                                <div data-repeater-item class="form-group row align-items-center">
                                    <input type="hidden" name="id" value="{{ $b['id'] }}">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="display:block;">* Tedarikçi</label>
                                                    @component('components.forms.select', [
                                                    'required' => true,
                                                    'name' => 'supplier_id',
                                                    'value' => $b->supplier_id ?? '',
                                                    'values' => $suppliers ?? array(),
                                                    'class' => 'getting-suppliers',
                                                    ])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="display:block;">* Ürün</label>
                                                    @component('components.forms.select', [
                                                    'required' => true,
                                                    'name' => 'product_id',
                                                    'value' => $b->product_id ?? '',
                                                    'values' => $products ?? array(),
                                                    'class' => 'getting-products',
                                                    ])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>* Miktar:</label>
                                                <input type="text" class="form-control miktar" name="quantity" required value="{{ $b->quantity ?? null }}" />
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="display:block;">* Birim</label>
                                                    @component('components.forms.select', [
                                                    'required' => true,
                                                    'name' => 'type',
                                                    'value' => $b->type ?? '',
                                                    'values' => $types ?? array(),
                                                    'class' => 'select2-standard',
                                                    ])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>* Birim Fiyat:</label>
                                                <input type="text" class="form-control money-format birim-fiyat" name="per_price" required value="{{ isset($b->price) ? money_formatter($b->price) : '' }}" />
                                            </div>
                                            <div class="col-md-3">
                                                <label>* Toplam Fiyat:</label>
                                                <input type="text" class="form-control money-format toplam-fiyat" name="price" required value="{{ isset($b->price) ? money_formatter($b->price) : '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                            <i class="la la-trash-o"></i>Sil
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="display:block;">* Tedarikçi</label>
                                                        @component('components.forms.select', [
                                                        'required' => true,
                                                        'name' => 'supplier_id',
                                                        'values' => $suppliers ?? array(),
                                                        'class' => 'getting-suppliers',
                                                        ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="display:block;">* Ürün</label>
                                                        @component('components.forms.select', [
                                                        'required' => true,
                                                        'name' => 'product_id',
                                                        'values' => $products ?? array(),
                                                        'class' => 'getting-products',
                                                        ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>* Adet:</label>
                                                    <input type="text" class="form-control miktar" name="quantity" value="1" required />
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label style="display:block;">* Birim</label>
                                                        @component('components.forms.select', [
                                                        'required' => true,
                                                        'name' => 'type',
                                                        'values' => $types ?? array(),
                                                        'class' => 'select2-standard',
                                                        ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>* Birim Fiyat:</label>
                                                    <input type="text" class="form-control money-format birim-fiyat" name="per_price" required />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>* Toplam Fiyat:</label>
                                                    <input type="text" class="form-control money-format toplam-fiyat" name="price" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                <i class="la la-trash-o"></i>Sil
                                            </a>
                                        </div>
                                    </div>
                                @endisset
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
                                        <i class="la la-plus"></i> Yeni Ekle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        @include('components.forms.input', [
                            'label' => 'Talep Tarihi',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => false,
                            'name' => 'start_at',
                            'class' => 'date-format',
                            'value' => isset($detail->start_at) ? date_formatter($detail->start_at) : date('d-m-Y')
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        @include('components.forms.input', [
                            'label' => 'Açıklama',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => false,
                            'name' => 'description',
                            'class' => '',
                            'value' => $detail->description ?? null
                        ])
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
$("body").on('keyup', '.birim-fiyat', function(){
    var val = $(this).val()
    var miktar = $(this).closest('.row').find('.miktar').val()

    val = val.replace('.', '')
    val = val.replace(',', '.')

    var toplam = val*miktar

    $(this).closest('.row').find('.toplam-fiyat').val(formatMoney(toplam))
});

$("body").on('keyup', '.toplam-fiyat', function(){
    var val = $(this).val()
    var miktar = $(this).closest('.row').find('.miktar').val()

    val = val.replace('.', '')
    val = val.replace(',', '.')

    var toplam = val/miktar

    $(this).closest('.row').find('.birim-fiyat').val(formatMoney(toplam))
});

$("body").on('keyup', '.miktar', function(){
    var miktar = $(this).val()
    var birim = $(this).closest('.row').find('.birim-fiyat').val()

    birim = birim.replace('.', '')
    birim = birim.replace(',', '.')

    var toplam = birim*miktar

    $(this).closest('.row').find('.toplam-fiyat').val(formatMoney(toplam))
});

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
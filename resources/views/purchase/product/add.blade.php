{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Ürün
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-product') }}" class="general-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $detail->id ?? null }}">
                @csrf
                <div class="row">
                    <div class="col-sm-8">
                        <div class="typeahead">
                        @include('components.forms.input', [
                            'label' => 'Ürün Adı',
                            'placeholder' => '',
                            'type' => 'text',
                            'help' => '',
                            'required' => true,
                            'name' => 'title',
                            'class' => 'get-products',
                            'value' => isset($detail->title) ? $detail->title : null
                        ])
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label style="display:block;">* Kategori</label>
                            @component('components.forms.select', [
                            'required' => true,
                            'name' => 'category_id',
                            'value' => $detail->category_id ?? '',
                            'values' => $authenticated->categories() ?? array(),
                            'class' => 'getting-categories',
                            ])
                            @endcomponent
                        </div>
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


    <div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Kategori Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-category')}}" class="product-form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-8">
                                @include('components.forms.input', [
                                    'label' => 'Kategori Adı',
                                    'placeholder' => '',
                                    'type' => 'text',
                                    'help' => '',
                                    'required' => false,
                                    'name' => 'title',
                                    'class' => '',
                                    'value' => ''
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <button class="btn btn-success">Kaydet</button>
                            </div>
                        </div>
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
        $('.tax-no').inputmask('999 999 99 99');
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
                            $(".getting-categories").append(newState).trigger('change');
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
    </script>
    <script>
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
            $('body').on('click', '.add-new-product', function(e){
                e.preventDefault();
                $("#add-product").modal('show');
            });
            $('#add-product').on('show.bs.modal', function(e) {
                $(".getting-suppliers").select2("close");
            });
            var flg = 0;
            $('.getting-categories').select2({
                language: {
                    inputTooShort: function () {
                        return "Kategori adı ile arayın.";
                    },
                    searching: function () {
                        return "Aranıyor..."
                    },
                    noResults: function () {
                        return "Sonuç bulunamadı";
                    },
                },
                ajax: {
                    url: "{{ route('categories-select2') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            query: params.term, // search term
                            page: params.page
                        };
                    },
                },
                placeholder: 'Kategori adı aratınız',
                minimumInputLength: 1
            });
            $('.getting-categories').on("select2:open", function () {
                flg++;
                if (flg == 1) {
                    $('.add-new-product').remove();
                    $(".select2-results").append("<div class='select2-results__option add-new-product'>\
                    <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Kategori Ekle</a>\
                    </div>");
                }
            });
        });
    </script>
@endsection
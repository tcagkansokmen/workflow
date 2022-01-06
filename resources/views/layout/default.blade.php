{{--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
 --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }} {{ Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('media/logos/favicon.png') }}" />
        <style>
        @font-face {
            font-family: 'Summernote';
            src: url('/font/summernote.ttf') format('ttf');
            font-weight: 600;
            font-style: normal;
        }

        @font-face {
            font-family: 'Summernote';
            src: url('/font/summernote.woff') format('woff'),
                url('/font/summernote.woff2') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        </style>
        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (Metronic::initThemes() as $theme)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Includable CSS --}}
        @yield('styles')
        <link type="text/css" rel="stylesheet" href="/lightgallery/css/lightgallery.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" integrity="sha512-+EoPw+Fiwh6eSeRK7zwIKG2MA8i3rV/DGa3tdttQGgWyatG/SkncT53KHQaS5Jh9MNOT3dmFL0FjTY08And/Cw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
        .label{
            white-space:nowrap !important;
        }
        .align-right{
            text-align:right;
        }
        .disabled-button{
            background:#ddd !important;
            color:#111 !important;
        }
        .dropzone-item-static {
        display: flex;
        align-items: center;
        margin-top: 0.75rem;
        border-radius: 0.42rem;
        padding: 0.5rem 1rem;
        background-color: #F3F6F9;
        }
        .dropzone-item-static .dropzone-file {
        flex-grow: 1;
        }
        .dropzone-item-static .dropzone-file .dropzone-filename {
        font-size: 0.9rem;
        font-weight: 500;
        color: #80808F;
        text-overflow: ellipsis;
        margin-right: 0.5rem;
        }
        .dropzone-item-static .dropzone-file .dropzone-filename b {
        font-size: 0.9rem;
        font-weight: 500;
        color: #B5B5C3;
        }
        .dropzone-item-static .dropzone-file .dropzone-error {
        margin-top: 0.25rem;
        font-size: 0.9rem;
        font-weight: 400;
        color: #F64E60;
        text-overflow: ellipsis;
        }
        .dropzone-item-static .dropzone-progress {
        width: 15%;
        }
        .dropzone-item-static .dropzone-progress .progress {
        height: 5px;
        transition: all 0.2s ease-in-out;
        }
        @media (prefers-reduced-motion: reduce) {
        .dropzone-item-static .dropzone-progress .progress {
            transition: none;
        }
        }
        .dropzone-item-static .dropzone-toolbar {
        margin-left: 1rem;
        display: flex;
        flex-wrap: nowrap;
        }
        .dropzone-item-static .dropzone-toolbar .dropzone-start,
        .dropzone-item-static .dropzone-toolbar .dropzone-cancel,
        .dropzone-item-static .dropzone-toolbar .dropzone-delete {
        height: 25px;
        width: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .dropzone-item-static .dropzone-toolbar .dropzone-start i,
        .dropzone-item-static .dropzone-toolbar .dropzone-cancel i,
        .dropzone-item-static .dropzone-toolbar .dropzone-delete i {
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        font-size: 0.8rem;
        color: #80808F;
        }
        .dropzone-item-static .dropzone-toolbar .dropzone-start:hover,
        .dropzone-item-static .dropzone-toolbar .dropzone-cancel:hover,
        .dropzone-item-static .dropzone-toolbar .dropzone-delete:hover {
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .dropzone-item-static .dropzone-toolbar .dropzone-start:hover i,
        .dropzone-item-static .dropzone-toolbar .dropzone-cancel:hover i,
        .dropzone-item-static .dropzone-toolbar .dropzone-delete:hover i {
        color: #3699FF;
        }
        .dropzone-item-static .dropzone-toolbar .dropzone-start {
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }
        </style>
    </head>

    <body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>

        @if (config('layout.page-loader.type') != '')
            @include('layout.partials._page-loader')
        @endif

        @include('layout.base._layout')

        <script>var HOST_URL = "{{ route('quick-search') }}";</script>

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
		    <script src="https://malsup.github.io/jquery.form.js"></script>
        @endforeach

        {{-- Includable JS --}}
        @yield('scripts')
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
        //proje detay sayfasında adres açma kapama
        $("body").on('change', '[name=send_address]', function(){
            if($(this).is(':checked')){
               $('.show-address').removeClass('d-none'); 
            }else{
               $('.show-address').addClass('d-none');
            }
        })
        </script>
        <script type="text/javascript">
        const companies = new Bloodhound({
        datumTokenizer: datum => Bloodhound.tokenizers.whitespace(datum.value),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "{{ route('companies-json') }}",

                replace: function(url, query) {
                    return url + "?query=" + query;
                },
                ajax : {
                    beforeSend: function(jqXhr, settings){
                    settings.data = $.param({q: queryInput.val()})
                    }
                }
            }
        });

        // Initialize the Bloodhound suggestion engine
        companies.initialize();

        $('.get-companies').typeahead(null, {
        name: 'countries',
        source: companies.ttAdapter()
        });
        
        const suppliers = new Bloodhound({
        datumTokenizer: datum => Bloodhound.tokenizers.whitespace(datum.value),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "{{ route('get-suppliers-json') }}",

                replace: function(url, query) {
                    return url + "?query=" + query;
                },
                ajax : {
                    beforeSend: function(jqXhr, settings){
                    settings.data = $.param({q: queryInput.val()})
                    }
                }
            }
        });

        // Initialize the Bloodhound suggestion engine
        suppliers.initialize();

        $('.get-suppliers').typeahead(null, {
        name: 'countries',
        source: suppliers.ttAdapter()
        });
        
        $("body").on('change', '[name=customer_id]', function(){
            var val = $(this).val();
            $.ajax({
                url: '{{ route("customer-personels") }}/' + val,
                dataType: 'json',
                method: 'get',
                success: function(response){
                    $(".pick-customer-personel").html('<option value="">Seçiniz</option>')
                    $.each(response, function(i, item) {
                        $(".pick-customer-personel").append('<option value="'+item.value+'">' + item.name + '</option>')
                    });
                    $('.pick-customer-personel').select2();
                }
            })
        });

        $("body").on('change', '.pick-city', function(){
            var val = $(this).val();
            $.ajax({
                url: '{{ route("counties") }}',
                data: 'city=' + val,
                dataType: 'json',
                success: function(response){
                    $(".pick-county").html('<option value="">Seçiniz</option>')
                    $.each(response, function(i, item) {
                        $(".pick-county").append('<option value="'+item.value+'">' + item.name + '</option>')
                    });
                    $('.pick-county').select2();
                }
            })
        });
        $("body").on('change', '.pick-customer', function(){
            var val = $(this).val();
            $.ajax({
                url: '{{ route("projects-list") }}',
                data: 'query=' + val,
                dataType: 'json',
                success: function(response){
                    $(".pick-project").html('<option value="">Seçiniz</option>')
                    $.each(response, function(i, item) {
                        $(".pick-project").append('<option value="'+item.value+'">' + item.name + '</option>')
                    });
                    var flg = 0;
                    $('.getting-projects').select2();
                    $('.getting-projects').on("select2:open", function () {
                        flg++;
                        if (flg == 1) {
                            $('.add-new-project').remove();
                            $(".select2-results").append("<div class='select2-results__option add-new-project'>\
                            <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Proje Ekle</a>\
                            </div>");
                        }
                    });
                }
            })
        });
        </script>
    <script>
        $(document).ready(function(){
            $('body').on('click', '.add-new-customer', function(e){
                e.preventDefault();
                $("#add-customer").modal('show');
            });
            $('#add-customer').on('show.bs.modal', function(e) {
                $(".getting-customers").select2("close");
            });
            var flg = 0;
            $('.getting-customers').select2({
                language: {
                    inputTooShort: function () {
                        return "Müşteri unvanı ile arayın.";
                    },
                    searching: function () {
                        return "Aranıyor..."
                    },
                    noResults: function () {
                        return "Sonuç bulunamadı";
                    },
                },
                ajax: {
                    url: "{{ route('customers-select2') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            query: params.term, // search term
                            page: params.page
                        };
                    },
                },
                placeholder: 'Müşteri aratınız',
                minimumInputLength: 1
            });
            $('.getting-customers').on("select2:open", function () {
                flg++;
                if (flg == 1) {
                    $('.add-new-customer').remove();
                    $(".select2-results").append("<div class='select2-results__option add-new-customer'>\
                    <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Müşteri Ekle</a>\
                    </div>");
                }
            });
        });
    </script>
    <script>
    $(document).ready(function(){
        $('body').on('click', '.add-new-project', function(e){
            e.preventDefault();
            $("#add-project").modal('show');
        });
        $('#add-project').on('show.bs.modal', function(e) {
            $(".getting-projects").select2("close");
        });
        var flg = 0;
        $('.getting-projects').select2({
            language: {
                inputTooShort: function () {
                    return "Proje başlığı ile arayın.";
                },
                searching: function () {
                    return "Aranıyor..."
                },
                noResults: function () {
                    return "Sonuç bulunamadı";
                },
            },
            ajax: {
                url: "{{ route('projects-select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page
                    };
                },
            },
            placeholder: 'Proje aratınız',
            minimumInputLength: 1
        });
        $('.getting-projects').on("select2:open", function () {
            flg++;
            if (flg == 1) {
                $('.add-new-project').remove();
                $(".select2-results").append("<div class='select2-results__option add-new-project'>\
                <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Proje Ekle</a>\
                </div>");
            }
        });
    });
    </script>
    <script>
        $(document).ready(function(){
            $('.customer-form').ajaxForm({
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
                            $(".getting-customers").append(newState).trigger('change');
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
            $('.project-form').ajaxForm({
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
                            $(".getting-projects").append(newState).trigger('change');
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
    <!-- A jQuery plugin that adds cross-browser mouse wheel support. (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

    <script src="/lightgallery/js/lightgallery.min.js"></script>
    <!-- lightgallery plugins -->
    <script src="/lightgallery/js/lg-thumbnail.min.js"></script>
    <script src="/lightgallery/js/lg-fullscreen.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.showgallery').each(function(){
            $(this).lightGallery({
                    selector: 'a.active-gallery'
            });
        });
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
     
$('.repeater').repeater({
  initEmpty: false,
  defaultValues: {
      'text-input': 'foo'
  },
  show: function () {
      $(this).slideDown();
      $(".phone").inputmask("(999) 999 99-99");
  },
  hide: function (deleteElement) {
      $(this).slideUp(deleteElement);
  }
});

</script>
<script>
$("body").on('click', '.show-notifications', function(){
    var type = $(this).attr('data-type')
    var quantity = $(this).find('.label-danger').html()
    quantity = parseInt(quantity)

    localStorage.setItem(type, quantity)
})
</script>

<script>
$(".show-notifications").each(function(item){
    var type = $(this).attr('data-type')
    var quantity = $(this).find('.label-danger').html()
    quantity = parseInt(quantity)


    var get = localStorage.getItem(type)
    if(get<=quantity){
        localStorage.setItem(type, get)
    }
})
$("body").on('click', '.show-notifications', function(){
    var type = $(this).attr('data-type')
    var quantity = $(this).find('.label-danger').html()
    quantity = parseInt(quantity)
    
    $(this).find('.label-danger').addClass('disabled-button')

    localStorage.setItem(type, quantity)
})
$(".show-notifications").each(function(item){
    var type = $(this).attr('data-type')
    var quantity = $(this).find('.label-danger').html()
    quantity = parseInt(quantity)


    var get = localStorage.getItem(type)
    if(quantity<=get){
        $(this).find('.label-danger').addClass('disabled-button')
    }
})
$('.excel-popup').magnificPopup({
  type: 'iframe'
  // other options
});
</script>

</body>
</html>


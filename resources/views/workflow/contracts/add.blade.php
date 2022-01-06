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

        <form class="form firm-form" method="post" action="{{ route('save-contract') }}">
          <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
          @csrf
          <div class="card-body">
            <div class="row {{ isset($detail->id) ? 'd-none' : ''  }}">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Firma Seçin</label>
                  @component('components.forms.select', [
                    'required' => true,
                    'name' => 'customer_id',
                    'value' => $detail->customer_id ?? '',
                    'values' => $firms ?? array(),
                    'class' => 'select2-new pick-customer getting-customers'
                    ])
                  @endcomponent
                </div>
              </div>
            </div>
            <div class="row {{ isset($detail->id) ? 'd-none' : ''  }}">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Proje Seçin</label>
                  @component('components.forms.select', [
                    'name' => 'project_id',
                    'value' => $detail->project_id ?? '',
                    'values' => $fairs ?? array(),
                    'class' => 'select2-standard pick-project getting-projects'
                    ])
                  @endcomponent
                </div>
              </div>
            </div>
            <div class="row ">
              <div class="col-sm-6">
                <div class="form-group">
                    <label style="display:block;">Müşteri Yetkilisi</label>
                    @component('components.forms.select', [
                    'name' => 'customer_personel_id',
                    'value' => $detail->customer_personel_id ?? '',
                    'values' => $personels ?? array(),
                    'class' => 'select2-standard pick-customer-personel',
                    ])
                    @endcomponent
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
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
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Para Birimi</label>
                    <select name="currency" class="select2-standard form-control m-input">
                      <option value="TRY"
                      @isset($detail->currency)
                        @if($detail->currency == 'TRY')
                          selected
                        @endif
                      @endisset 
                      >TRY</option>
                      <option value="USD"
                      @isset($detail->currency)
                        @if($detail->currency == 'USD')
                          selected
                        @endif
                      @endisset 
                     >USD</option>
                      <option value="EUR"
                      @isset($detail->currency)
                        @if($detail->currency == 'EUR')
                          selected
                        @endif
                      @endisset 
                     >EUR</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>KDV Oranı</label>
                    <select name="vat" class="select2-standard form-control m-input">
                      <option value="0"
                      @isset($detail->vat)
                        @if($detail->vat == '0')
                          selected
                        @endif
                      @endisset 
                     >0</option>
                      <option value="1"
                      @isset($detail->vat)
                        @if($detail->vat == '1')
                          selected
                        @endif
                      @endisset 
                     >1</option>
                      <option value="8"
                      @isset($detail->vat)
                        @if($detail->vat == '8')
                          selected
                        @endif
                      @endisset 
                     >8</option>
                      <option value="18"
                      @isset($detail->vat)
                        @if($detail->vat == '18')
                          selected
                        @endif
                      @endisset 
                     >18</option>
                    </select>
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
              @php 
              $deadline = "";
              if(isset($detail->deadline)){
                if($detail->deadline != '0000-00-00'){
                  $deadline = date('d-m-Y', strtotime($detail->deadline));
                }
              }
              @endphp
                @include('components.forms.input', [
                    'label' => 'Deadline',
                    'type' => 'text',
                    'placeholder' => '',
                    'help' => '',
                    'class' => 'tarih',
                    'required' => true,
                    'name' => 'deadline',
                    'value' => $deadline
                ])
              </div>
            </div>

            <div class="separator separator-dashed my-5"></div>
            <h5>Ödeme Planı</h5>
            <p>Ödeme planındaki ödemeler, KDV hariç olarak girilmelidir.</p>
            <div class="firm-repeater">
                <div class="form-group row">
                    <div data-repeater-list="odeme" class="col-lg-12">
                    @isset($bills)
                      @foreach($bills as $b)
                        <div data-repeater-item class="form-group row align-items-end">
                        <input type="hidden" name="id" value="{{ $b['id'] }}">
                          <div class="col-sm-8">
                            <div class="row">
                              <div class="col-md-6">
                                  <label>* Tutar:</label>
                                  <input type="text" class="form-control money-format" name="price" required placeholder="" value="{{ number_format($b['price'], 2, ",", ".") }}"/>
                                  <div class="d-md-none mb-2"></div>
                              </div>
                              <div class="col-md-6">
                                  <label>Vade:</label>
                                  <input type="text" class="form-control tarih" name="vade" placeholder="Vade tarihi" value="{{ date('d-m-Y', strtotime($b['bill_date'])) }}"/>
                                  <div class="d-md-none mb-2"></div>
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
                    @endisset
                        <div data-repeater-item class="form-group row align-items-end">
                          <div class="col-sm-8">
                            <div class="row">
                              <div class="col-md-6">
                                  <label>* Tutar:</label>
                                  <input type="text" class="form-control money-format" name="price" required placeholder=""/>
                                  <div class="d-md-none mb-2"></div>
                              </div>
                              <div class="col-md-6">
                                  <label>Vade:</label>
                                  <input type="text" class="form-control tarih" name="vade" placeholder="Vade tarihi"/>
                                  <div class="d-md-none mb-2"></div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                                <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                    <i class="la la-trash-o"></i>Sil
                                </a>
                          </div>
                        </div>
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
            

            <hr>


            <div class="form-group row" style="margin-top:25px;">
              <div class="col-lg-9">
                <div class="dropzone dropzone-multi" id="kt_dropzone_5">
                  <div class="dropzone-panel mb-lg-0 mb-2">
                    <a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Sözleşmeye Ait Dosya Ekle</a>
                  </div>
                  <div class="dropzone-items">
                    <div class="dropzone-item" style="display:none">
                      <div class="dropzone-file">
                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                          <span data-dz-name="">some_image_file_name.jpg</span>
                          <strong>(
                          <span data-dz-size="">340kb</span>)</strong>
                        </div>
                        <div class="dropzone-error" data-dz-errormessage=""></div>
                      </div>
                      <div class="dropzone-progress">
                        <div class="progress">
                          <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress=""></div>
                        </div>
                      </div>
                      <div class="dropzone-toolbar">
                        <span class="dropzone-delete" data-dz-remove="">
                          <i class="flaticon2-cross"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  @isset($offer_files)
                   @foreach($offer_files as $b)
                    <div class="dropzone-item-static dz-processing " style="">
                      <div class="dropzone-file">
                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                          <span>{{ $b->filename }}</span>
                          <strong>(
                          <span><strong><a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
                        </div>
                        <div class="dropzone-error"></div>
                      </div>
                      <div class="dropzone-progress">
                        <div class="progress" style="opacity: 0;">
                          <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0; width: 100%;"></div>
                        </div>
                      </div>
                      <div class="dropzone-toolbar">
                        <a href="{{ route('delete-contract-file', ['id' => $b->id]) }}" class="dropzone-delete teklif-dosya-sil" >
                          <i class="flaticon2-cross"></i>
                          </a>
                      </div>
                    </div>
                   @endforeach
                  @endisset

                </div>
                <span class="form-text text-muted">En büyük dosya boyutu 8mb ve tek seferde en fazla 5 dosya ekleyebilirsiniz.</span>
              </div>
            </div>

            <div class="form-group row" style="margin-top:25px;">
              <div class="col-lg-9">
              <div class="custom-file">
                <input type="file" name="contract" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Sözleşme Nüshasını Ekle</label>
                  @isset($detail->contract)
                    <div class="dropzone-item-static dz-processing " style="">
                      <div class="dropzone-file">
                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                          <span>{{ $detail->contract }}</span>
                          <strong>(
                          <span><strong><a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
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
            
                <span class="form-text text-muted">En büyük dosya boyutu 8mb ve tek seferde en fazla 5 dosya ekleyebilirsiniz.</span>
              </div>
            </div>
                          
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
            <a href="{{ $redirect }}" class="btn btn-secondary">Vazgeç</a>
          </div>
        </form>
    </div>
    
    
    <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Müşteri Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-customer')}}" class="customer-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('records.customer.add-inside')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-project" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Proje Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-project')}}" class="project-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('records.project.add-inside')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Styles Section --}}
@section('styles')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script>
$(document).ready(function(){
    $('.summernote').summernote({ 
        height: 100 ,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    })     
});
</script>

<script>
  

$(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
$('.select2-standard').select2({
  placeholder: "Seçiniz"
});


function select2init(){
  $(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
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

$(document).ready(function(){
  select2init()
  $('.firm-repeater').repeater({
      initEmpty: false,
      defaultValues: {
          'text-input': 'foo'
      },
      show: function () {
          $(this).slideDown();
          select2init();
      },
      hide: function (deleteElement) {
          $(this).slideUp(deleteElement);
      }
  });
});
</script>
<script>
$(document).ready(function(){
    $("body").on('click', '.teklif-dosya-sil', function(e){
        e.preventDefault();
        var thi = $(this);
        var href = $(this).attr('href');
        swal.fire({
            title: "Emin misiniz?",
            text: "Bu işlemin geri dönüşü bulunmamaktadır",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Evet, sil!",
            cancelButtonText: "Hayır, vazgeç!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                            $(".formprogress").show();
                $.ajax({
                    url: href,
                    dataType: 'json',
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error:function(e){
                            $(".formprogress").hide();
                    },
                    success: function(data){

                            $(".formprogress").hide();
                        if(data.status){
                            thi.closest(".dropzone-item-static").remove();
                        }else{
                            swal.fire(
                                "Dikkat",
                                data.message,
                                "error"
                            )
                        }
                    }
                });
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });
  $('.firm-form').ajaxForm({ 
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
          swal.fire({
          text: item.message,
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "Tamam",
          customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
          }
        }).then(function() {
          window.location.href = item.redirect;
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
          text: item.message,
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
var id = '#kt_dropzone_5';

// set the preview element template
var previewNode = $(id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var myDropzone5 = new Dropzone(id, { // Make the whole body a dropzone
    url: "/briefs/upload", // Set the url for your upload script location
    parallelUploads: 20,
    maxFilesize: 8, // Max filesize in MB
    previewTemplate: previewTemplate,
             timeout: 1800000,maxFiles: 20,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: function() {
        this.on("success", function(file, responseText) {
          console.log('this');
          console.log(file);
           $(file.previewElement).append('<input type="hidden" name="files[]" value="'+responseText+'" />');
        });
    },
    previewsContainer: id + " .dropzone-items", // Define the container to display the previews
    clickable: id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
});

myDropzone5.on("addedfile", function(file) {
    // Hookup the start button
    $(document).find( id + ' .dropzone-item').css('display', '');
});

// Update the total progress bar
myDropzone5.on("totaluploadprogress", function(progress) {
    $( id + " .progress-bar").css('width', progress + "%");
});

myDropzone5.on("sending", function(file) {
    // Show the total progress bar when upload starts
    $( id + " .progress-bar").css('opacity', "1");
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone5.on("complete", function(progress) {
    var thisProgressBar = id + " .dz-complete";
    setTimeout(function(){
        $( thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
    }, 300)
});

</script>
@endsection

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

        <form class="form firm-form" method="post" action="{{ route('save-brief') }}">
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

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Tasarımcı Seçin</label>
                  @component('components.forms.select', [
                    'required' => true,
                    'name' => 'designer_id',
                    'value' => $detail->designer_id ?? '',
                    'values' => $designers,
                    'class' => 'select2-standard'
                    ])
                  @endcomponent
                </div>
              </div>
            </div>

            <div class="row">
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
                <div class="col-sm-3 d-none">
                  @include('components.forms.input', [
                      'label' => 'Hall No',
                      'type' => 'text',
                      'placeholder' => '',
                      'help' => '',
                      'required' => true,
                      'name' => 'hall_no',
                      'class' => 'd-none',
                      'value' => 'boş'
                  ])
                </div>
                <div class="col-sm-3 d-none">
                  @include('components.forms.input', [
                      'label' => 'Stand No',
                      'type' => 'text',
                      'placeholder' => '',
                      'help' => '',
                      'required' => true,
                      'name' => 'stand_no',
                      'class' => 'd-none',
                      'value' => 'boş'
                  ])
                </div>
                <div class="col-sm-3">
                  @include('components.forms.input', [
                      'label' => 'Deadline',
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

            <hr>

            <div class="row">
                <div class="col-sm-12">
                  @foreach($types as $t)
                  <hr>
                  <h3 class="font-size-lg text-dark font-weight-bold mb-6" >{{ $t->title }}</h3>
                  <div class="row">
                    @foreach($t->parents as $p)
                      @if($p->type == 'input')
                      <div class="col-sm-3">
                        @include('components.forms.input', [
                            'label' => $p->parent,
                            'type' => 'text',
                            'placeholder' => '',
                            'help' => '',
                            'required' => true,
                            'name' => 'type['.$p->name.']',
                            'value' => $type[$p->name]->brieftype ?? ''
                        ])
                      </div>
                      @elseif($p->type == 'multiselect')
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="">{{ $p->parent }}</label>
                          <select name="type[{{ $p->name }}]" class="form-control m-input select2-standard" multiple id="">
                            <option value="">Seçiniz</option>
                            @foreach($p->values as $v)
                              <option value="{{ $v->vals }}"
                                @isset($type[$p->name])
                                  @foreach($type[$p->name]->brieftype as $bf)
                                    @if($bf->value == $v->vals)
                                      selected
                                    @endif
                                  @endforeach
                                @endisset
                                >{{ $v->vals }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      @elseif($p->type == 'radio')
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>{{ $p->parent }}</label>
                          <div class="radio-inline">
                          @foreach($p->values as $v)
                            <label class="radio">
                                <input type="radio" name="type[{{ $p->name }}]" value="{{ $v->vals }}"
                                @isset($type[$p->name])
                                  @foreach($type[$p->name]->brieftype as $bf)
                                    @if($bf->value == $v->vals)
                                      checked
                                    @endif
                                  @endforeach
                                @endisset
                               > {{ $v->vals }}
                                <span></span>
                            </label>
                          @endforeach
                          </div>
                        </div>
                      </div>
                      @endif
                    @endforeach
                  </div>
                  @endforeach
                </div>
            </div>
              
            <div class="row">
              <div class="col-sm-9">
                    <textarea name="description" id="" cols="30" rows="10" class="form-control summernote">{{ $detail->description ?? '' }}</textarea>
              </div>
            </div>

            <div class="form-group row" style="margin-top:25px;">
              <div class="col-lg-9">
                <div class="dropzone dropzone-multi" id="kt_dropzone_5">
                  <div class="dropzone-panel mb-lg-0 mb-2">
                    <a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Briefe Ait Dosya Ekle</a>
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
                  @isset($brief_file)
                   @foreach($brief_file as $b)
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
                        <a href="{{ route('brief-dosya-sil', ['id' => $b->id]) }}" class="dropzone-delete brief-dosya-sil" >
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
                          
          </div>
          <div class="card-footer">
          @isset($detail)
            @if(isset($detail->status)&&($detail->status=='Onaylandı'||$detail->status=='Reddedildi'))
            Brief sonuçlandığı için düzenleme gerçekleştiremezsiniz.
            @else 
            <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
            <a href="{{ $redirect }}" class="btn btn-secondary">Vazgeç</a>
            @endif
          @else 
            <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
            <a href="{{ $redirect }}" class="btn btn-secondary">Vazgeç</a>
          @endisset
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
$('.select2-standard').select2({
  placeholder: "Seçiniz"
});

$("body").on('change', '.firms', function(){
  var a = $(this).val();
  $.ajax({
    url: '/firms/single-json/' + a,
    dataType: 'json',
    type: 'get',
    success: function(data){
      $(".officer, .fair").html('<option value="">Seçiniz</option>');
      $.each(data.officer, function(i, item) {
        $(".officer").append('<option value="'+item.id+'">' + item.name + ' ' + item.surname + '</option>');
      });
      $.each(data.fairs, function(i, item) {
        $(".fair").append('<option value="'+item.id+'">' + item.title + '</option>');
      });
    }
  });
});


function select2init(){
  $(".phone").inputmask("(999) 999 99-99");  
  $('.getting-firms').select2({
    ajax: {
      url: "/firms/select2",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          query: params.term, // search term
          page: params.page
        };
      },
    },
    placeholder: 'Firma aratınız',
    minimumInputLength: 1
  });
  var flg = 0;
  $('.getting-firms').on("select2:open", function () {
    flg++;
    if (flg == 1) {
      $('.add-new-firma').remove();
      $(".select2-results").append("<div class='select2-results__option add-new-firma'>\
      <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Firma Ekle</a>\
      </div>");
    }
  });

  var flg2 = 0;
  $('.getting-officers').on("select2:open", function () {
    console.log('aloowwwww');
    flg2++;
    if (flg2 == 1) {
      $('.add-new-officer').remove();
      $(".select2-results").append("<div class='select2-results__option add-new-officer'>\
      <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Yetkili Ekle</a>\
      </div>");
    }
  });
  var flg3 = 0;
  $('.getting-fairs-2').on("select2:open", function () {
    console.log('açıldı amk');
    flg3++;
    if (flg3 == 1) {
      $('.add-new-fuar').remove();
      $(".select2-results").append("<div class='select2-results__option add-new-fuar'>\
      <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Proje Ekle</a>\
      </div>");
    }
  });
}

$('body').on('click', '.add-new-firma', function(e){
  e.preventDefault();
  $("#firms").modal('show');
});

$('body').on('click', '.add-new-officer', function(e){
  e.preventDefault();
  $("#officers").modal('show');
});

$('body').on('click', '.add-new-fuar', function(e){
  e.preventDefault();
  $("#fairs").modal('show');
});

$(document).ready(function(){
  select2init();
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
    $("body").on('click', '.brief-dosya-sil', function(e){
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
          html: item.message,
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
        $('.is-invalid').removeClass('is-invalid').closest('.form-group').removeClass('.invalid-select');
        $.each(item.errors, function(key, value) {
          $("[name="+key+"]").addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
          $.each(value, function(k, v) {
            $("[name="+key+"]").closest('.form-group').addClass('invalid-select').find('.invalid-feedback').append(v + "<br>");
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

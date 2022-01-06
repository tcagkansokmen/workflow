{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Yeni Montaj
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-assembly')}}" class="general-form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label style="display:block;">* Müşteri</label>
                                @component('components.forms.select', [
                                'required' => true,
                                'name' => 'customer_id',
                                'value' => $detail->customer_id ?? '',
                                'values' => $authenticated->customers() ?? array(),
                                'class' => 'getting-customers pick-customer',
                                ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label style="display:block;">Proje</label>
                                @component('components.forms.select', [
                                'name' => 'project_id',
                                'value' => $detail->project_id ?? '',
                                'values' => $projects ?? array(),
                                'class' => 'getting-projects pick-project',
                                ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-sm-8">
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
                        <div class="col-sm-8">
                            <div class="typeahead">
                            @include('components.forms.input', [
                              'label' => 'Başlık',
                              'placeholder' => '',
                              'type' => 'text',
                              'help' => '',
                              'required' => true,
                              'name' => 'title',
                              'class' => 'get-companies',
                              'value' => isset($detail->title) ? $detail->title : null
                            ])
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <label style="display:block;">Açıklama</label>
                            <textarea class="form-control" name="description">{{ $detail->description ?? null }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-5 mb-5">
                        <div class="col-sm-4">
                            @include('components.forms.input', [
                              'label' => 'Başlangıç',
                              'placeholder' => '',
                              'type' => 'text',
                              'help' => '',
                              'name' => 'start_at',
                              'class' => 'date-format',
                              'value' => isset($detail->start_at) ? date_formatter($detail->start_at) : null
                            ])
                        </div>
                        <div class="col-sm-4">
                            @include('components.forms.input', [
                              'label' => 'Bitiş',
                              'placeholder' => '',
                              'type' => 'text',
                              'help' => '',
                              'name' => 'end_at',
                              'class' => 'date-format',
                              'value' => isset($detail->end_at) ? date_formatter($detail->end_at) : null
                            ])
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-5">Ek Bilgiler</h5>
                        <div class="form-group row ">
                            <div data-repeater-list="extra" class="col-lg-10">
                                @if(isset($detail->extras))
                                    @foreach($detail->extras as $t)
                                      <div data-repeater-item class="form-group align-items-center">
                                          <div class="row">
                                              <div class="col-md-9">
                                                  <input type="hidden" name="id" value="{{ $t->id }}">
                                                  <div class="form-group">
                                                    <textarea name="message" id="" cols="30" rows="10" class="summernote form-control">{{ $t->message }}</textarea>
                                                  </div>
                                                  </div>
                                              <div class="col-md-3">
                                              </div>
                                          </div>
                                      </div>
                                    @endforeach
                                @endif
                                @if(!isset($detail->extras)||!count($detail->extras))
                                  <div data-repeater-item class="form-group align-items-center">
                                      <div class="row">
                                          <div class="col-md-9">
                                              <div class="form-group">
                                                    <textarea name="message" id="" cols="30" rows="10" class="summernote form-control"></textarea>
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                          </div>
                                      </div>
                                  </div>
                                @endif
                            </div>
                    </div>
                    <hr>
                    <h5>Resimler</h5>
                    <div class="form-group row" style="margin-top:10px;">
                      <div class="col-lg-9">
                        <div class="dropzone dropzone-multi" id="kt_dropzone_5">
                          <div class="dropzone-panel mb-lg-0 mb-2">
                            <a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Resimleri ekleyin</a>
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
                          @isset($photos)
                          @foreach($photos as $b)
                            <div class="dropzone-item-static dz-processing " style="">
                              <div class="dropzone-file">
                                <div class="dropzone-filename" title="some_image_file_name.jpg">
                                  <span>{{ $b->filename }}</span>
                                  <strong>(
                                  <span><strong><a href="{{ Storage::url('snap/assembly/') }}{{ $b->filename }}" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
                                </div>
                                <div class="dropzone-error"></div>
                              </div>
                              <div class="dropzone-progress">
                                <div class="progress" style="opacity: 0;">
                                  <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0; width: 100%;"></div>
                                </div>
                              </div>
                              <div class="dropzone-toolbar">
                                <a href="{{ route('delete-assembly-extra', ['id' => $b->id]) }}" class="dropzone-delete delete-extra" >
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

                    <hr>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label style="display:block;">* Durum</label>
                                @component('components.forms.select', [
                                'required' => true,
                                'name' => 'status',
                                'value' => $detail->status ?? '',
                                'values' => $statuses ?? array(),
                                'class' => 'select2-standard',
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
                  </div>
                </div>

            </form>
        </div>
        <!--end::Form-->
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
$("body").on('change', '.pick-customer', function(){
  var val = $(this).val();

  $("#add-project .getting-customers").closest('.form-group').remove();
  $("#add-project form").prepend('<input type="hidden" name="customer_id" value="'+val+'" />');

});
// Class definition
var KTFormRepeater = function() {
  // Private functions
  var demo1 = function() {
      $('.extra-repeater').repeater({
          initEmpty: false,
          defaultValues: {
              'text-input': 'foo'
          },
          show: function () {
                $(this).slideDown();
                $('.summernote').summernote({ 
                    height: 100,
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
          },
          hide: function (deleteElement) {
              $(this).slideUp(deleteElement);
          }
      });
  };

  return {
      // public functions
      init: function() {
          demo1();
      }
  };
}();

jQuery(document).ready(function() {
  KTFormRepeater.init();
});

$(document).ready(function(){
  $("body").on('click', '.delete-extra', function(e){
      e.preventDefault();
      var thi = $(this);
      var href = $(this).attr('href');
      swal.fire({
          title: "Emin misiniz?",
          text: "Silmek istediğinize emin misiniz?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Evet, devam et!",
          cancelButtonText: "Hayır, vazgeç!",
          reverseButtons: true
      }).then(function(result) {
          if (result.value) {
              $.ajax({
                  url: href,
                  dataType: 'json',
                  type: 'get',
                  success: function(data){
                      if(data.status){
                          thi.closest('.dropzone-item-static').remove();
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
});
var id = '#kt_dropzone_5';

// set the preview element template
var previewNode = $(id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var myDropzone5 = new Dropzone(id, { // Make the whole body a dropzone
    url: "{{ route('upload-assembly') }}", // Set the url for your upload script location
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

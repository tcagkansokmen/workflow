{{-- Extends layout --}} @extends('layout.default') {{-- Content --}} @section('content')

  <!--begin::Entry-->
  <div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
      <!--begin::Inbox-->
      <div class="d-flex flex-row">
        <!--begin::List-->
        <div class="flex-row-fluid ml-lg-8 d-none" id="kt_inbox_list">
          <!--begin::Card-->
          <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header row row-marginless align-items-center flex-wrap py-5 h-auto">
              <h5></h5>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body table-responsive px-0">
              <!--begin::Items-->
              <div class="list list-hover min-w-500px" data-inbox="list">
              </div>
              <!--end::Items-->
            </div>
            <!--end::Body-->
          </div>
          <!--end::Card-->
        </div>
        <!--end::List-->
        <!--begin::View-->
        <div class="flex-row-fluid ml-lg-8" id="kt_inbox_view">
          <!--begin::Card-->
          <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header align-items-center flex-wrap justify-content-between py-5 h-auto">
              <!--begin::Left-->
              <div class="d-flex align-items-center my-2">
              <h5 class="mb-0">{{ $detail->customer->title }} {{ $detail->project->title }}</h5>
              </div>
              <span class="label label-light-danger font-weight-bold label-inline">{{ $detail->price }} + KDV</span>
              <!--end::Left-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body p-0">
              <!--begin::Header-->
              <div class="d-flex align-items-center justify-content-between flex-wrap card-spacer-x py-5">
                <!--begin::Title-->
                <div class="d-flex align-items-center mr-2 py-2">
                  <div class="font-weight-bold font-size-h3 mr-3">{{ $messages[0]->subject ?? 'Henüz mesaj yok' }}</div>
                </div>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="d-flex py-2">
                </div>
                <!--end::Toolbar-->
              </div>
              <!--end::Header-->
              <!--begin::Messages-->
              <div class="mb-3">
              @foreach($messages as $d)
                <div class="cursor-pointer shadow-xs {{ $loop->first ? 'toggle-on' : 'toggle-off' }}" data-inbox="message">
                  <div class="d-flex align-items-center card-spacer-x py-6">
                    <span class="symbol symbol-50 mr-4">
                      <span class="symbol-label" style="background-image: url('assets/media/users/100_13.jpg')"></span>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 flex-wrap mr-2">
                      <div class="d-flex">
                        <a href="#" class="font-size-lg font-weight-bolder text-dark-75 text-hover-primary mr-2">{{ isset($d->user->name) ? $d->user->name." ".$d->user->surname : $d->from }}</a>
                        <div class="font-weight-bold text-muted">
                        <span class="label label-success label-dot mr-2"></span>{{ Carbon\Carbon::parse($d->created_at)->diffForHumans()}}</div>
                      </div>
                      <div class="d-flex flex-column">
                        <div class="toggle-off-item">
                          <span class="font-weight-bold text-muted cursor-pointer" data-toggle="dropdown">To: {{ $d->message_to }} CC: {{ $d->message_cc }}
                        </div>
                        <div class="text-muted font-weight-bold toggle-on-item" data-inbox="toggle">{{\Illuminate\Support\Str::limit(strip_tags($d->comment),100,'...')}}</div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="font-weight-bold text-muted mr-2">{{ date('d M Y H:i', strtotime($d->created_at))}}</div>
                    </div>
                  </div>
                  <div class="card-spacer-x py-3 toggle-off-item">
                    {!! $d->comment !!}
                    
                    <div class="showgallery">
                    @foreach($d->files as $b)
                      <div class="dropzone-item-static dz-processing " style="">
                        <div class="dropzone-file">
                          <div class="dropzone-filename" title="some_image_file_name.jpg">
                            <span>{{ $b->filename }}</span>
                            <strong>(
                            <span><strong><a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" target="_blank" class="active-gallery"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
                          </div>
                          <div class="dropzone-error"></div>
                        </div>
                        <div class="dropzone-progress">
                          <div class="progress" style="opacity: 0;">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0; width: 100%;"></div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    </div>

                  </div>
                </div>
              @endforeach
              </div>
              <!--end::Messages-->
              <!--begin::Reply-->
              <div class="card-spacer mb-3 customer_id" data-id="{{ $detail->customer->id }}" id="kt_inbox_reply">
                <div class="card card-custom shadow-sm">
                  <div class="card-body p-0">
                    <!--begin::Form-->
                    <form id="kt_inbox_reply_form" action="{{ route('save-offer-message') }}" method="POST" class="message-form">
                    @csrf
                    <input type="hidden" name="offer_id" value="{{ $detail->id }}">
                    <input type="hidden" name="from" value="{{ Auth::user()->email ?? Request::get('email') }}">
                      <!--begin::Body-->
                      <div class="d-block">
                        <!--begin::To-->
                        <div class="d-flex align-items-center border-bottom inbox-to px-8 min-h-50px">
                          <div class="text-dark-50 w-75px">Alıcı:</div>
                          <div class="d-flex align-items-center flex-grow-1">
                            <input type="text" class="form-control border-0" name="compose_to" value="{{ $detail->customer->email }}" />
                          </div>
                          <div class="ml-2">
                            <span class="text-muted font-weight-bold cursor-pointer text-hover-primary mr-2" data-inbox="cc-show">Cc</span>
                            <span class="text-muted font-weight-bold cursor-pointer text-hover-primary" data-inbox="bcc-show">Bcc</span>
                          </div>
                        </div>
                        <!--end::To-->
                        <!--begin::CC-->
                        <div class="d-none align-items-center border-bottom inbox-to-cc pl-8 pr-5 min-h-50px">
                          <div class="text-dark-50 w-75px">Cc:</div>
                          <div class="flex-grow-1">
                            <input type="text" class="form-control border-0" name="compose_cc" />
                          </div>
                          <span class="btn btn-clean btn-xs btn-icon" data-inbox="cc-hide">
                            <i class="la la-close"></i>
                          </span>
                        </div>
                        <!--end::CC-->
                        <!--begin::BCC-->
                        <div class="d-none align-items-center border-bottom inbox-to-bcc pl-8 pr-5 min-h-50px">
                          <div class="text-dark-50 w-75px">Bcc:</div>
                          <div class="flex-grow-1">
                            <input type="text" class="form-control border-0" name="compose_bcc" />
                          </div>
                          <span class="btn btn-clean btn-xs btn-icon" data-inbox="bcc-hide">
                            <i class="la la-close"></i>
                          </span>
                        </div>
                        <!--end::BCC-->
                        <!--begin::Subject-->
                        <div class="border-bottom">
                          <input class="form-control border-0 px-8 min-h-45px" name="subject" placeholder="Konu" value="Proje Hakkında"  />
                        </div>
                        <!--end::Subject-->
                        <!--begin::Message-->
                        <textarea name="comment" id="" cols="30" rows="10" class="form-control summernote"></textarea>
                        <!--end::Message-->
                        <!--begin::Attachments-->
                      </div>
                      <!--end::Body-->
                      <!--begin::Footer-->
                      <div class="d-flex align-items-center justify-content-between py-5 pl-8 pr-5 border-top">
                        <div class="dropzone dropzone-multi" id="kt_dropzone_5" style="width:50%;">
                          <div class="dropzone-panel mb-lg-0 mb-2">
                            <a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Mesaja Dosya Ekle</a>
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
                          @if(!count($messages))
                          <div class="showgallery">
                            @foreach($detail->files as $b)
                              <div class="dropzone-item-static dz-processing " style="">
                                <div class="dropzone-file">
                                  <div class="dropzone-filename" title="some_image_file_name.jpg">
                                    <span>{{ $b->filename }}</span>
                                    <strong>(
                                    <span><strong><a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" class="active-gallery" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)</strong>
                                  </div>
                                  <div class="dropzone-error"></div>
                                </div>
                                <div class="dropzone-progress">
                                  <div class="progress" style="opacity: 0;">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0; width: 100%;"></div>
                                  </div>
                                </div>
                                <div class="dropzone-toolbar">
                                  <input type="hidden" name="files[]" value="{{ $b->filename }}">
                                  <a href="" class="dropzone-delete offer-dosya-sil" >
                                    <i class="flaticon2-cross"></i>
                                    </a>
                                </div>
                              </div>
                            @endforeach
                            </div>
                          @endif
                        </div>
                        <!--end::Attachments-->
                        <!--begin::Toolbar-->
                        <div class="d-flex align-items-center">
                          @if($detail->status == 'Yönetici Onayladı')
                            <button type="submit" class="btn btn-success font-weight-bold px-6">Müşteriye Gönder</button>
                          @else 
                            <button type="submit" class="btn btn-primary font-weight-bold px-6">Mesajı Gönder</button>
                          @endif
                        </div>
                        <!--end::Toolbar-->
                      </div>
                      <!--end::Footer-->
                    </form>
                    <!--end::Form-->
                  </div>
                </div>
              </div>
              <!--end::Reply-->
            </div>
            <!--end::Body-->
          </div>
          <!--end::Card-->
        </div>
        <!--end::View-->
      </div>
      <!--end::Inbox-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Entry-->
@endsection 
@section('styles') 
<link type="text/css" rel="stylesheet" href="/lightgallery/css/lightgallery.css" />
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
</script>
<!-- A jQuery plugin that adds cross-browser mouse wheel support. (Optional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

<script src="/lightgallery/js/lightgallery.min.js"></script>
<!-- lightgallery plugins -->
<script src="/lightgallery/js/lg-thumbnail.min.js"></script>
<script src="/lightgallery/js/lg-fullscreen.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
			$('.showgallery').each(function(){
				$(this).lightGallery({
						selector: 'a.active-gallery'
				});
						
			});
    });
</script>
<script src="/inbox.js"></script>
<script>
$('.message-form').ajaxForm({ 
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
	headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
	success:   function(html){
		
		location.reload();

		$('.message-form textarea').val('');
	}
}); 

$("body").on('click', '.offer-dosya-sil', function(){
  $(this).closest(".dropzone-item-static").remove();
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
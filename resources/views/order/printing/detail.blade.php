{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Profile 4-->
		<div class="d-flex flex-row">
			<!--begin::Aside-->
			<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
				<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<!--begin::Body-->
					<div class="card-body pt-4">
						<!--begin::Toolbar-->
						<div class="d-flex justify-content-end">
							<div class="dropdown dropdown-inline">
							</div>
						</div>
						<!--end::Toolbar-->
						<!--begin::User-->
						<div class="d-flex align-items-center">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol symbol-light-info mr-3">
									<span class="symbol-label font-size-h5">B</span>
								</div>
							</div>
							<div>
								<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $detail->customer->title }}</a>
								<div class="text-muted">{{ $detail->project->title }}</div>
								<div class="mt-2">
								@if($authenticated->power('printing', 'status')&&$detail->printer_id==$authenticated->id)
									@if($detail->status=='Talep Açıldı')
										<a href="#" class="btn btn-sm btn-light-primary change-status" 
										onclick="change_status('{{ $detail->id }}', 'Kabul Edildi')" 
										data-id="{{ $detail->id }}"><i class="flaticon2-calendar-8"></i> Kabul Edildi</a>&nbsp;
									@endif

									@if($detail->status=='Kabul Edildi')
										<a href="#" class="btn btn-sm btn-light-primary change-status" 
										onclick="change_status('{{ $detail->id }}', 'Baskı Başladı')" 
										data-id="{{ $detail->id }}"><i class="flaticon2-calendar-8"></i> Baskı Başladı</a>&nbsp;
									@endif

									@if($detail->status=='Baskı Başladı')
										<a href="#" class="btn btn-sm btn-light-primary change-status" 
										onclick="change_status('{{ $detail->id }}', 'Baskı Tamamlandı')" 
										data-id="{{ $detail->id }}"><i class="flaticon2-calendar-8"></i> Baskı Tamamlandı</a>&nbsp;
									@endif
									
								@endif
								</div>
							</div>
						</div>
						<!--end::User-->
						<!--begin::Contact-->
						<div class="pt-8 pb-6">
						@isset($detail->log)
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Sorumlu Adı:</span>
								<span class="text-muted">{{ $detail->log->user->name }} {{ $detail->log->user->surname }}</span>
							</div>
						@endisset
							@if($detail->printer_id)
								<div class="d-flex align-items-center justify-content-between mb-2">
									<span class="font-weight-bold mr-2">Baskı Operatörü:</span>
									<span class="text-muted">{{ $detail->printer->name }} {{ $detail->printer->surname }}</span>
								</div>
							@endif
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Başlangıç:</span>
								<span class="text-muted">{{ date_formatter($detail->start_at) }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Bitiş:</span>
								<span class="text-muted">{{ date_formatter($detail->end_at) }}</span>
							</div>
						</div>
						<!--end::Contact-->
						<hr>
						<!--begin::Contact-->
						<!--end::Contact-->
						@if($detail->status=='Talep Açıldı')
						<span href="#" class="btn btn-light-danger font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Baskı Başladı')
						<span href="#" class="btn btn-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Baskı Tamamlandı')
						<span href="#" class="btn btn-light-success font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Baskı Onaylandı')
						<span href="#" class="btn btn-light-info font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@else
						<span href="#" class="btn btn-light-info font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@endif
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
						<div class="d-flex">
              @if($authenticated->power('printing', 'edit'))
            		<a href="{{ route('update-printing', ['id' => $detail->id]) }}" class="btn btn-lg btn-light-success font-weight-bolder mr-3">Düzenle</a>
							@endif
						</div>
					</div>
				</div>

				@if(count($detail->bills))
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
					<strong>Faturalar</strong>
					<hr>
						@foreach($detail->bills as $b)
							<div class="d-flex align-items-center flex-grow-1">
								<!--begin::Section-->
								<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
									<!--begin::Info-->
									<div class="d-flex flex-column align-items-cente py-2 w-55">
										<!--begin::Title-->
										<a href="{{ route('bill-detail', ['id' => $b->id]) }}" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">{{ $b->bill_no }}</a>
										<!--end::Title-->
										<!--begin::Data-->
										<span class="text-muted font-weight-bold">{{ \Carbon\Carbon::parse($b->bill_date)->formatLocalized('%d %B %Y') }}</span>
										<!--end::Data-->
									</div>
									<!--end::Info-->
									<!--begin::Label-->
									<span class="label label-lg label-light-primary label-inline font-weight-bold">{{ $b->status }}</span>
									<!--end::Label-->
								</div>
								<!--end::Section-->
							</div>
						@endforeach
					</div>
				</div>
				@endif

				<!--begin::Mixed Widget 14-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header h-auto py-4">
						<div class="card-title">
							<h3 class="card-label">Genel Baskı Bilgileri</h3>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body py-4">
						<div class="form-group row my-2">
							<label class="col-4 col-form-label">Açıklama:</label>
							<div class="col-8">
								<span class="form-control-plaintext font-weight-bolder">{{ $detail->description }}</span>
							</div>
						</div>
            @if(count($detail->extras))
						<div class="form-group row my-2">
							<label class="col-4 col-form-label">Ek Bilgiler:</label>
							<div class="col-8">
                @foreach($detail->extras as $xt)
								  <span class="form-control-plaintext font-weight-bolder">{{ $xt->message }}</span>
                @endforeach
							</div>
						</div>
            @endif
					</div>
					<!--end::Body-->
				</div>
				<!--end::Mixed Widget 14-->
			</div>
			<!--end::Aside-->
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">

			<div class="row">
				<div class="col-lg-6">
					<!--begin::Mixed Widget 5-->
					<div class="card card-custom">
						<!--begin::Header-->
						<div class="card-header h-auto py-4">
							<div class="card-title">
								<h3 class="card-label">Genel Bilgiler
								<span class="d-block text-muted pt-2 font-size-sm">tüm teknik bilgiler</span></h3>
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body py-4">
							<div data-scroll="true" data-height="500">
								@foreach($metas as $m)
									@if(isset($detail_meta[$m->key]))
									<div class="form-group row my-2">
										<label class="col-4 col-form-label">{{ $m->value }}</label>
										<div class="col-8">
											<span class="form-control-plaintext font-weight-bolder">
												{{ $detail_meta[$m->key]->value }}
											</span>
										</div>
									</div>
									<hr>
									@endif
								@endforeach
							</div>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Mixed Widget 5-->
				</div>
				<div class="col-lg-6">
					<div class="card card-custom card-stretch gutter-b">
						<!--begin::Header-->
						<div class="card-header h-auto py-4">
							<div class="card-title">
								<h3 class="card-label">Görseller
								<span class="d-block text-muted pt-2 font-size-sm">dosyalara tıklayarak görüntüleyebilirsiniz</span></h3>
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body">
							<div class="showgallery" data-scroll="true" data-height="475">
									@foreach($photos as $b)
									<!--begin::Item-->
									<a href="{{ Storage::url('snap/printing/') }}{{ $b->filename }}" class="active-gallery d-flex align-items-center flex-wrap mb-10">
										<!--begin::Symbol-->
										<div class="symbol symbol-50 symbol-light mr-5">
											<span class="symbol-label">
														{!! image_format(Storage::url('snap/printing/').$b->filename) !!}
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Text-->
										<div class="d-flex flex-column flex-grow-1 mr-2">
											<span href="#" target="_blank" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"
											 style="text-overflow:ellipsis; white-space:nowrap; max-width:125px;">{{ $b->filename
												}}
											</span>
											<span class="text-muted font-weight-bold">{{ date('d M, Y H:i', strtotime($b->created_at)) }}</span>
										</div>
										<!--end::Text-->
									</a>
									<!--end::Item-->
									@endforeach
							</div>
						</div>
						<!--end::Body-->
					</div>
				</div>
			</div>

				<!--begin::Card-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header align-items-center px-4 py-3">
						<div class="text-center flex-grow-1">
							<div class="text-dark-75 font-weight-bold font-size-h5">Mesajlar</div>
							<div>
								<span class="label label-sm label-dot label-success"></span>
								<span class="font-weight-bold text-muted font-size-sm">Aktif</span>
							</div>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Scroll-->
						<div data-scroll="true" style="max-height:500px;">
							<!--begin::Messages-->
							<div class="messages offer-comment">
								@include('order.printing.messages')
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->
					<div class="card-footer align-items-center">
						<form action="{{ route('save-printing-message') }}" method="POST" class="comment-form">
							@csrf
						<input type="hidden" name="id" value="{{ $detail->id }}">
						<!--begin::Compose-->
						<textarea class="form-control border-0 p-0" name="comment" required rows="2" placeholder="Mesajınızı yazın"></textarea>
            <div class="d-flex align-items-center justify-content-between mt-5">
              <div class="mr-3">
              <div class="dropzone dropzone-multi" id="dropzone_design_comment" style="margin-top:20px;">
                <div class="dropzone-panel mb-lg-0 mb-2">
                  <a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">
                    <i class="flaticon-attachment"></i>
                  </a>
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
              </div>
              </div>
              <div>
                <button type="submit" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Gönder</button>
              </div>
            </div>
						</form>
						<!--begin::Compose-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Card-->

				<!--end::Advance Table Widget 8-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Profile 4-->
	</div>
	<!--end::Container-->
</div>
@endsection
@section('scripts')
<script>
function change_status(id, val){
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'danger',
        message: 'Lütfen Bekleyin...'
    });
    $.ajax({
        url: '{{ route("update-printing-status") }}',
        dataType: 'json',
        type: 'post',
        data: 'id=' + id + '&val=' + val,
        errur: function(){
            KTApp.unblockPage();
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            KTApp.unblockPage();
            if(data.status){
                location.reload()
            }else{
                swal.fire(
                    "Dikkat",
                    data.message,
                    "error"
                )
            }
        }
    });
}
$('.comment-form').ajaxForm({
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
  error: function () {
    swal.fire({
      text: "Dikkat! Sistemsel bir hata nedeniyle kaydedilemedi!",
      icon: "error",
      buttonsStyling: false,
      confirmButtonText: "Tamam",
      customClass: {
        confirmButton: "btn font-weight-bold btn-light-primary"
      }
    }).then(function () {
      KTUtil.scrollTop();
    });
    $(".formprogress").hide();
  },
  dataType: 'json',
  success: function (html) {
    location.reload();
    $('.brief-comment-form textarea').val('');
  }
});
var design_comment_id = '#dropzone_design_comment';

// set the preview element template
var previewNode = $(design_comment_id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var dropzone_design_comment = new Dropzone(design_comment_id, { // Make the whole body a dropzone
    url: "{{ route('upload-printing') }}", // Set the url for your upload script location
    parallelUploads: 20,
    maxFilesize: 8, // Max filesize in MB
    previewTemplate: previewTemplate,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: function() {
        this.on("success", function(file, responseText) {
          console.log('comment burada');
          console.log(file);
           $(file.previewElement).append('<input type="hidden" name="files[]" value="'+responseText+'" />');
        });
    },
    previewsContainer: design_comment_id + " .dropzone-items", // Define the container to display the previews
    clickable: design_comment_id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
});

dropzone_design_comment.on("addedfile", function(file) {
    // Hookup the start button
    $(document).find( design_comment_id + ' .dropzone-item').css('display', '');
});

// Update the total progress bar
dropzone_design_comment.on("totaluploadprogress", function(progress) {
    $( design_comment_id + " .progress-bar").css('width', progress + "%");
});

dropzone_design_comment.on("sending", function(file) {
    // Show the total progress bar when upload starts
    $( design_comment_id + " .progress-bar").css('opacity', "1");
});

// Hide the total progress bar when nothing's uploading anymore
dropzone_design_comment.on("complete", function(progress) {
    var thisProgressBar = design_comment_id + " .dz-complete";
    setTimeout(function(){
        $( thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
    }, 300)
});
</script>

	<script>
var comment_id = '#dropzone_comment';

// set the preview element template
var previewNode = $(comment_id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var dropzone_comment = new Dropzone(comment_id, { // Make the whole body a dropzone
    url: "{{ route('upload-printing') }}", // Set the url for your upload script location
    parallelUploads: 20,
    maxFilesize: 8, // Max filesize in MB
    previewTemplate: previewTemplate,
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
    previewsContainer: comment_id + " .dropzone-items", // Define the container to display the previews
    clickable: comment_id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
});

dropzone_comment.on("addedfile", function(file) {
    // Hookup the start button
    $(document).find( comment_id + ' .dropzone-item').css('display', '');
});

// Update the total progress bar
dropzone_comment.on("totaluploadprogress", function(progress) {
    $( comment_id + " .progress-bar").css('width', progress + "%");
});

dropzone_comment.on("sending", function(file) {
    // Show the total progress bar when upload starts
    $( comment_id + " .progress-bar").css('opacity', "1");
});

// Hide the total progress bar when nothing's uploading anymore
dropzone_comment.on("complete", function(progress) {
    var thisProgressBar = comment_id + " .dz-complete";
    setTimeout(function(){
        $( thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
    }, 300)
});
</script>

@endsection

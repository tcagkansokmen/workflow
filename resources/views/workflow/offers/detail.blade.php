{{-- Extends layout --}} @extends('layout.default') {{-- Content --}} @section('content')
<!--begin::Entry-->
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
									@if($detail->status=='Yönetici Onayında')
										@if(Auth::user()->isAdmin()) 
											<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="Yönetici Onayladı" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Onay</a>
											<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="Yönetici Reddetti" class="btn btn-sm btn-danger offer-status font-weight-bold py-2 px-3 px-xxl-5 my-1">Red</a>
										@endif
									@elseif(Auth::user()->id==$detail->user_id && $detail->status=='Hazırlanıyor')
									<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="Yönetici Onayında" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Onaya
										Gönder
									</a>
									@else($detail->status=='Yönetici Onayladı')
									<a href="{{ route('offer-message', ['id' => $detail->id]) }}" class="btn btn-sm btn-success font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Müşteriye Gönder
									</a>
									<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="müşteri onayladı" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Onaylandı
									</a>
									<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="müşteri reddetti" class="btn btn-sm btn-danger offer-status font-weight-bold py-2 px-3 px-xxl-5 my-1">Reddedildi
									</a>
									@elseif($detail->status=='Müşteri Onayında')
									<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="müşteri onayladı" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Müşteri
										Onayladı
									</a>
									<a href="{{ route('offer-status', ['id' => $detail->id]) }}" data-status="müşteri reddetti" class="btn btn-sm btn-danger offer-status font-weight-bold py-2 px-3 px-xxl-5 my-1">Müşteri
										Reddetti
									</a>
									@endif 

								</div>
							</div>
						</div>
						<!--end::User-->
						<!--begin::Contact-->
						<div class="pt-8 pb-6">
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Sorumlu:</span>
								<span class="text-muted">{{ $detail->responsible->name }} {{ $detail->responsible->surname }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Teklif:</span>
								<span class="text-muted">{{ number_format($detail->price, 2, ",", ".") }} {{ $detail->currency }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">KDV:</span>
								<span class="text-muted">{{ number_format(($detail->price*$detail->vat/100), 2, ",", ".") }} {{ $detail->currency
									}}
								</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Toplam:</span>
								<span class="text-muted">{{ number_format(($detail->price*$detail->vat/100)+$detail->price, 2, ",", ".") }} {{ $detail->currency
									}}
								</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Deadline:</span>
								<span class="text-muted">{{ date('d M, Y', strtotime($detail->deadline)) }}</span>
							</div>
						</div>
						<!--end::Contact-->
						<hr>
						<!--begin::Contact-->
						<!--end::Contact-->
						@if(strpos($detail->status, 'onayında') !== false)
						<span href="#" class="btn btn-light-warning font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif(strpos($detail->status, 'onay') !== false)
						<span href="#" class="btn btn-light-success font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif(strpos($detail->status, 'red') !== false)
						<span href="#" class="btn btn-light-danger font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@else
						<span href="#" class="btn btn-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@endif
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
						<div class="d-flex">
							@if($detail->status!='Onaylandı')
            		<a href="{{ route('update-offer', ['id' => $detail->id]) }}" class="btn btn-lg btn-light-success font-weight-bolder mr-3">Düzenle</a>
							@endif
							@if($detail->contract_status)
									<a href="{{ route('contract-detail', ['id' => $detail->id]) }}" class="btn btn-lg btn-light-info btn-block">Sözleşme</a>
							@endif
						</div>
					</div>
				</div>
				<!--begin::Mixed Widget 14-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header h-auto py-4">
						<div class="card-title">
							<h3 class="card-label">Genel Teklif Bilgileri
								<span class="d-block text-muted pt-2 font-size-sm">sözleşmeye ait şirket ve Proje bilgileri</span></h3>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body py-4">
						<div class="form-group row my-2">
							<label class="col-4 col-form-label">Firma Adı:</label>
							<div class="col-8">
								<span class="form-control-plaintext font-weight-bolder">{{ $detail->customer->title }}</span>
							</div>
						</div>
						<div class="form-group row my-2">
							<label class="col-4 col-form-label">Proje Adı:</label>
							<div class="col-8">
								<span class="form-control-plaintext">
									<span class="font-weight-bolder">{{ $detail->project->title }}</span>&#160;
							</div>
						</div>
						<div class="form-group row my-2">
							<label class="col-4 col-form-label">Sözleşme Tarihi:</label>
							<div class="col-8">
								<span class="form-control-plaintext font-weight-bolder">{{ date('d M, Y', strtotime($detail->project->start_at)) }}
									- {{ date('d M, Y', strtotime($detail->project->end_at)) }}</span>
							</div>
						</div>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Mixed Widget 14-->
			</div>
			<!--end::Aside-->
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				<!--begin::Row-->
				<div class="row">
					<div class="col-lg-12">
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">Teklif Dosyaları
										<span class="d-block text-muted pt-2 font-size-sm">dosyalara tıklayarak görüntüleyebilirsiniz</span></h3>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body">
								<div class="showgallery" data-scroll="true" data-height="475">
									@foreach($detail->files as $b)
										<!--begin::Item-->
										<a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" class="active-gallery d-flex align-items-center flex-wrap mb-10">
											<!--begin::Symbol-->
											<div class="symbol symbol-50 symbol-light mr-5">
												<span class="symbol-label">
														{!! image_format(Storage::url('snap/brief/').$b->filename) !!}
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
				<div class="card card-custom mt-5">
					<!--begin::Header-->
					<div class="card-header align-items-center px-4 py-3">
						<div class="text-center flex-grow-1">
							<div class="text-dark-75 font-weight-bold font-size-h5">Teklife Ait Yorumlar</div>
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
								@include('workflow.offers.offer-comment')
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->


					<!--begin::Footer-->
					<div class="card-footer align-items-center">
						<form action="{{ route('save-offer-comment') }}" method="POST" class="offer-comment-form">
							@csrf
						<input type="hidden" name="id" value="{{ $detail->id }}">
						<input type="hidden" name="type" value="offer">
						<!--begin::Compose-->
						<textarea class="form-control border-0 p-0" name="comment" required rows="2" placeholder="Mesajınızı yazın"></textarea>
						<div class="d-flex align-items-center justify-content-between mt-5">
							<div class="mr-3">
								<!--
								<a href="#" class="btn btn-clean btn-icon btn-md mr-1">
									<i class="flaticon2-photograph icon-lg"></i>
								</a>
								<a href="#" class="btn btn-clean btn-icon btn-md">
									<i class="flaticon2-photo-camera icon-lg"></i>
								</a>
								-->
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


				<a href="{{ route('offer-message', ['id' => $detail->id]) }}" class="btn lg-sm btn-success btn-lg btn-block font-weight-bold mt-5 mr-2 py-2 px-3 px-xxl-5 my-1">Müşteri Mesajları
				</a>

				<!--end::Advance Table Widget 8-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Profile 4-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->

@endsection {{-- Styles Section --}} @section('styles') @endsection {{-- Scripts Section --}} @section('scripts')
<script>
$('body').on('[data-inbox="cc-show"]', 'click', function(e) {
		var inputEl = $('body').find('.inbox-to-cc');
		$('body').removeClass(inputEl, 'd-none');
		$('body').addClass(inputEl, 'd-flex');
		$('body').find("[name=compose_cc]").focus();
});

// CC input hide
$('body').on('[data-inbox="cc-hide"]', 'click', function(e) {
		var inputEl = $('body').find('.inbox-to-cc');
		$('body').removeClass(inputEl, 'd-flex');
		$('body').addClass(inputEl, 'd-none');
});

// BCC input show
$('body').on('[data-inbox="bcc-show"]', 'click', function(e) {
		var inputEl = $('body').find('.inbox-to-bcc');
		$('body').removeClass(inputEl, 'd-none');
		$('body').addClass(inputEl, 'd-flex');
		$('body').find("[name=compose_bcc]").focus();
});

// BCC input hide
$('body').on('[data-inbox="bcc-hide"]', 'click', function(e) {
		var inputEl = $('body').find('.inbox-to-bcc');
		$('body').removeClass(inputEl, 'd-flex');
		$('body').addClass(inputEl, 'd-none');
});
				
	$('body').on('click', '[data-inbox="message"]', function(){
		if($(this).hasClass('toggle-on')){
			$(this).removeClass('toggle-on').addClass('toggle-off');
		}else{
			$(this).addClass('toggle-on').removeClass('toggle-off');
		}
	});
	$('.offer-comment-form').ajaxForm({
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
	
	$("body").on('click', '.offer-status', function (e) {
		e.preventDefault();
		var thi = $(this);
		var href = $(this).attr('href');
		var status = $(this).attr('data-status');
		swal.fire({
			title: "Emin misiniz?",
			text: "Bu işlemin geri dönüşü bulunmamaktadır",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Evet, devam et!",
			cancelButtonText: "Hayır, vazgeç!",
			reverseButtons: true
		}).then(function (result) {
			if (result.value) {
				$.ajax({
					url: href,
					dataType: 'json',
					type: 'post',
					data: 'status=' + status,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (data) {
						if (data.status) {
							location.reload();
						} else {
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

	var id = '#kt_dropzone_5';

	if($('#kt_dropzone_5').length){
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
			init: function () {
				this.on("success", function (file, responseText) {
					console.log('this');
					console.log(file);
					$(file.previewElement).append('<input type="hidden" name="files[]" value="' + responseText + '" />');
				});
			},
			previewsContainer: id + " .dropzone-items", // Define the container to display the previews
			clickable: id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
		});

		myDropzone5.on("addedfile", function (file) {
			// Hookup the start button
			$(document).find(id + ' .dropzone-item').css('display', '');
		});

		// Update the total progress bar
		myDropzone5.on("totaluploadprogress", function (progress) {
			$(id + " .progress-bar").css('width', progress + "%");
		});

		myDropzone5.on("sending", function (file) {
			// Show the total progress bar when upload starts
			$(id + " .progress-bar").css('opacity', "1");
		});

		// Hide the total progress bar when nothing's uploading anymore
		myDropzone5.on("complete", function (progress) {
			var thisProgressBar = id + " .dz-complete";
			setTimeout(function () {
				$(thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
			}, 300)
		});
	}
</script> @endsection
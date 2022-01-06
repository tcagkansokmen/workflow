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
									@if(Auth::user()->group_id=='1') 
										@if($detail->status=='Yönetici Onayında')
										<a href="{{ route('bill-status', ['id' => $detail->id]) }}" data-status="Yönetici Onayladı" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Onay</a>
										<a href="{{ route('bill-status', ['id' => $detail->id]) }}" data-status="Yönetici Reddetti" class="btn btn-sm btn-danger offer-status font-weight-bold py-2 px-3 px-xxl-5 my-1">Red</a>
										@endif 
									@elseif(Auth::user()->id==$detail->user_id&&$detail->status=='Hazırlanıyor')
									<a href="{{ route('bill-status', ['id' => $detail->id]) }}" data-status="Yönetici Onayında" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Onaya
										Gönder
									</a>
									@elseif($detail->status=='Yönetici Onayladı'&&Auth::user()->isAccountant())
									<a href="#" data-toggle="modal" data-target="#bills" data-status="Fatura Kesildi" class="btn btn-sm btn-success font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Fatura Kesildi
									</a>
									@elseif(Auth::user()->id==$detail->user_id&&$detail->status=='Fatura Kesildi')
									<a href="#" data-toggle="modal" data-target="#send_customer" data-status="Müşteriye Gönderildi" class="btn btn-sm btn-success font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Müşteriye
										Gönder
									</a>
									@elseif(Auth::user()->group_id=='5'&&$detail->status=='Müşteriye Gönderildi')
									<a href="{{ route('bill-status', ['id' => $detail->id]) }}" data-status="ödendi" class="btn btn-sm btn-success offer-status font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Ödendi</a>
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
								<span class="font-weight-bold mr-2">Fatura Tarihi:</span>
								<span class="text-muted">{{ date('d M, Y', strtotime($detail->bill_date)) }}</span>
							</div>
						</div>
						@if(($detail->status=='Yönetici Onayladı'||$detail->status=='Fatura Kesildi'||($detail->status=='Müşteriye Gönderildi'))&&Auth::user()->isAccountant())
						<hr>
						<a href="#" data-toggle="modal" data-target="#bills" data-status="Fatura Kesildi" class="btn btn-sm btn-success font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Fatura Ekle
						</a>
						@endif  
						<!--end::Contact-->
						<hr>
						<!--begin::Contact-->
						<!--end::Contact-->
						@if(strpos($detail->status, 'onayında') !== false)
						<span href="#" class="btn btn-light-warning font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif(strpos($detail->status, 'onay')!==false )
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
				@if($detail->offer_id)
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
						<div class="d-flex">
									<a href="{{ Storage::url('snap/contract/') }}{{ $detail->contract->contract }}" target="_blank" class="btn btn-lg btn-light-info btn-block">Sözleşme</a>
						</div>
					</div>
				</div>
				@endif
				@if($detail->id)
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
						<div class="d-flex">
							<a href="{{ route('update-bill', ['id' => $detail->id]) }}" target="_blank" class="btn btn-lg btn-light-primary btn-block">Hızlı Düzenle</a>
						</div>
					</div>
				</div>
				@endif
				<!--begin::Mixed Widget 14-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header h-auto py-4">
						<div class="card-title">
							<h3 class="card-label">Fatura Bilgileri
								<span class="d-block text-muted pt-2 font-size-sm">Faturaya ait şirket ve Proje bilgileri</span></h3>
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
							<label class="col-4 col-form-label">Proje Tarihi:</label>
							<div class="col-8">
								<span class="form-control-plaintext font-weight-bolder">{{ date('d M, Y', strtotime($detail->project->start_at)) }}
									- {{ date('d M, Y', strtotime($detail->project->end_at)) }}</span>
							</div>
						</div>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Mixed Widget 14-->

				<div class="card card-custom mt-5">
					<div class="card-body py-4">
					<strong>Bağlı Olduğu İşler</strong>
					<hr>
						@foreach($detail->assemblies as $b)
							<div class="d-flex align-items-center flex-grow-1">
								<!--begin::Section-->
								<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
									<!--begin::Info-->
									<div class="d-flex flex-column align-items-cente py-2 w-55">
										<!--begin::Title-->
										<a href="{{ route('assembly-detail', ['id' => $b->id]) }}" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">{{ $b->title }}</a>
										<!--end::Title-->
										<!--begin::Data-->
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
						@foreach($detail->productions as $b)
							<div class="d-flex align-items-center flex-grow-1">
								<!--begin::Section-->
								<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
									<!--begin::Info-->
									<div class="d-flex flex-column align-items-cente py-2 w-55">
										<!--begin::Title-->
										<a href="{{ route('production-detail', ['id' => $b->id]) }}" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">{{ $b->title }}</a>
										<!--end::Title-->
										<!--begin::Data-->
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
						@foreach($detail->printings as $b)
							<div class="d-flex align-items-center flex-grow-1">
								<!--begin::Section-->
								<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
									<!--begin::Info-->
									<div class="d-flex flex-column align-items-cente py-2 w-55">
										<!--begin::Title-->
										<a href="{{ route('printing-detail', ['id' => $b->id]) }}" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">{{ $b->title }}</a>
										<!--end::Title-->
										<!--begin::Data-->
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

			</div>
			<!--end::Aside-->
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				<!--begin::Row-->
				<div class="row">
					<div class="col-lg-6">
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">Fatura Dosyaları
										<span class="d-block text-muted pt-2 font-size-sm">dosyalara tıklayarak görüntüleyebilirsiniz</span></h3>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body">
								<div class="showgallery" data-scroll="true" data-height="475">
									@foreach($detail->files as $b)
										<!--begin::Item-->
										<div class=" d-flex align-items-center flex-wrap mb-10">
											<!--begin::Symbol-->
											<div class="symbol symbol-50 symbol-light mr-5">
												<a class="symbol-label {{ is_image($b->filename) ? 'active-gallery' : 'excel-popup' }}" href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}">
														{!! image_format(Storage::url('snap/brief/').$b->filename) !!}
												</a>
											</div>
											<!--end::Symbol-->
											<!--begin::Text-->
											<div class="d-flex flex-column flex-grow-1 mr-2">
												<span href="#" target="_blank" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"
												style="text-overflow:ellipsis; white-space:nowrap; max-width:125px;">{{ $b->filename
													}}
												</span>
												<span class="text-muted font-weight-bold">{{ date('d M, Y H:i', strtotime($b->created_at)) }}</span>
                        <a href="{{ route('delete-bill-file', ['id' => $b->id]) }}" class="dropzone-delete teklif-dosya-sil" >
                          <i class="flaticon2-cross"></i>
                          </a>
											</div>
											<!--end::Text-->
										</div>
										<!--end::Item-->
										@endforeach
								</div>
							</div>
							<!--end::Body-->
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">Açıklama ve Notlar
										<span class="d-block text-muted pt-2 font-size-sm">faturaya ilişkin not ve açıklamalar</span></h3>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body">
								@if(!$detail->description&&!$detail->notes)
									<p>Faturaya ilişkin açıklama ve not bulunamadı.</p>
								@endif
								@if($detail->description)
								<div class="mb-5">
									<strong>Açıklama: </strong> {!! $detail->description !!}
								</div>
								@endif

								<div>
								@if($detail->notes)
								<strong>Notlar: </strong> {!! $detail->notes !!}
								@endif
								</div>
							</div>
							<!--end::Body-->
						</div>
					</div>
				</div>
			</div>
			<!--end::Content-->
		</div>
		<!--end::Profile 4-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->


<div class="modal fade" id="bills" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
	<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="{{ route('bill-files', ['id' => $detail->id]) }}" method="POST" class="general-form">
						@csrf
					<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Fatura Kopyaları</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
					</div>
					<div class="modal-body">
				<div class="form-group row" style="margin-top:25px;">
					<div class="col-lg-9">
						<div class="dropzone dropzone-multi" id="kt_dropzone_5" style="margin-top:20px;">
							<div class="dropzone-panel mb-lg-0 mb-2">
								<a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Fatura Kopyaları</a>
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
						<span class="form-text text-muted">En büyük dosya boyutu 8mb ve tek seferde en fazla 5 dosya ekleyebilirsiniz.</span>
					</div>
				</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success" type="submit">Kaydet</button>
					</div>
					</form>
			</div>
	</div>
</div>

<div class="modal fade" id="send_customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
	<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="{{ route('send-bill-to-customer', ['id' => $detail->id]) }}" method="POST" class="general-form">
						@csrf
					<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Faturayı Müşteriye Gönder</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
						<p>Her alıcı arasına virgül koyunuz</p>
						<div class="col-lg-12">
							@include('components.forms.input', [
								'label' => 'Başlık',
								'placeholder' => '',
								'type' => 'text',
								'help' => '',
								'required' => false,
								'name' => 'title',
								'class' => '',
							])

							@include('components.forms.input', [
								'label' => 'Alıcı',
								'placeholder' => '',
								'type' => 'text',
								'help' => '',
								'required' => false,
								'name' => 'to',
								'class' => '',
								'value' => $detail->customer->email,
								'id' => 'to'
							])

							@include('components.forms.input', [
								'label' => 'CC',
								'placeholder' => '',
								'type' => 'text',
								'help' => '',
								'required' => false,
								'name' => 'cc',
								'class' => '',
								'id' => 'cc'
							])

							@include('components.forms.input', [
								'label' => 'BCC',
								'placeholder' => '',
								'type' => 'text',
								'help' => '',
								'required' => false,
								'name' => 'bcc',
								'class' => '',
								'id' => 'bcc'
							])

							<textarea id="" cols="30" rows="10" class="summernote" name="message" placeholder="Mesajınız"></textarea>
						</div>

						@isset($detail->id)
						<div class="col-lg-12">
							<div class="dropzone dropzone-multi" id="kt_dropzone_5">
								<div class="dropzone-panel mb-lg-0 mb-2">
									<a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm d-none">Fatura Kopyaları</a>
								</div>
									@foreach($detail->files as $b)
									<div class="dropzone-item-static dz-processing " style="">
										<div class="dropzone-file">
											<div class="dropzone-filename" title="some_image_file_name.jpg" style="display:flex; justify-content:space-between;">
												<span style="max-width:150px; overflow:hidden; white-space:nowrap; display:block; text-overflow:ellipsis;">{{ $b->filename }}</span>
												<strong>(
													<span><strong><a href="{{ Storage::url('snap/brief/') }}{{ $b->filename }}" target="_blank"><i class="flaticon-download"></i> Görüntüle</a></strong></span>)
												</strong>
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
									@endforeach

							</div>
							<span class="form-text text-muted">En büyük dosya boyutu 8mb ve tek seferde en fazla 5 dosya ekleyebilirsiniz.</span>
						</div>
            @endisset

						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success" type="submit">Gönder</button>
					</div>
					</form>
			</div>
	</div>
</div>


@endsection {{-- Styles Section --}} @section('styles') @endsection {{-- Scripts Section --}} @section('scripts')
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
										error: function(err) {
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
										},
                    success: function(data){

                        (".formprogress").hide();
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
            } else if (result.dismiss === "cancel") {
                
            }
        });
    });
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
$(document).ready(function(){

	var toEl = document.getElementById('to');
	var tagifyTo = new Tagify(toEl, {
		delimiters: ",", // add new tags when a comma or a space character is entered
		maxTags: 10,
		keepInvalidTags: true, // do not remove invalid tags (but keep them marked as invalid)
		transformTag: function(e) {
				e.class = "tagify__tag tagify__tag-light--primary"
		},
		dropdown : {
				classname : "color-blue",
				enabled   : 1,
				maxItems  : 15
		}
	});

	var toEl = document.getElementById('cc');
	var tagifyTo = new Tagify(toEl, {
		delimiters: ",", // add new tags when a comma or a space character is entered
		maxTags: 10,
		keepInvalidTags: true, // do not remove invalid tags (but keep them marked as invalid)
		transformTag: function(e) {
				e.class = "tagify__tag tagify__tag-light--info"
		},
		dropdown : {
				classname : "color-blue",
				enabled   : 1,
				maxItems  : 15
		}
	});

	var toEl = document.getElementById('bcc');
	var tagifyTo = new Tagify(toEl, {
		delimiters: ",", // add new tags when a comma or a space character is entered
		maxTags: 10,
		keepInvalidTags: true, // do not remove invalid tags (but keep them marked as invalid)
		transformTag: function(e) {
				e.class = "tagify__tag tagify__tag-light--danger"
		},
		dropdown : {
				classname : "color-blue",
				enabled   : 1,
				maxItems  : 15
		}
	});
});
</script>
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

        $(".formprogress").show();
				$.ajax({
					url: href,
					dataType: 'json',
					type: 'post',
					data: 'status=' + status,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					error:function(e){
							$(".formprogress").hide();
					},
					success: function(data){

							$(".formprogress").hide();
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
             timeout: 1800000,maxFiles: 20,
			previewTemplate: previewTemplate,
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
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
									<span class="symbol-label font-size-h5">K</span>
								</div>
							</div>
							<div>
								<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $detail->customer->title }}</a>
								<div class="text-muted">{{ $detail->project->title }}</div>
							</div>
						</div>
						<!--end::User-->
								<div class="mt-2 d-flex">
								@if($authenticated->power('exploration', 'status'))
									@if($detail->user_id==$authenticated->id)
										@if($detail->status!='Talep Açıldı')
											@if($detail->status=='Kabul Edildi')
												<a href="#" class="btn btn-sm btn-light-primary change-status" 
												onclick="change_status('{{ $detail->id }}', 'Keşif Bekliyor')" 
												data-id="{{ $detail->id }}">Bekliyor</a>&nbsp;
											@endif
											@if($detail->status=='Keşif Bekliyor')
												<a href="#" class="btn btn-sm btn-light-success change-status" 
												onclick="change_status('{{ $detail->id }}', 'Keşif Tamamlandı')" 
												data-id="{{ $detail->id }}">Tamamlandı</a>&nbsp;
											@endif
												<a href="#" class="btn btn-sm btn-success font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1" data-toggle="modal" data-target="#designs">Yeni Rapor Ekle</a>
										@else 
											<a href="#" class="btn btn-sm btn-light-success change-status" 
											onclick="change_status('{{ $detail->id }}', 'Kabul Edildi')" 
											data-id="{{ $detail->id }}">Kabul</a>&nbsp;
											<a href="#" class="btn btn-sm btn-light-danger change-status" 
											onclick="change_status('{{ $detail->id }}', 'Reddedildi')" 
											data-id="{{ $detail->id }}">Red</a>&nbsp;
										@endif
									@endif	
								@endif
								</div>
						<!--begin::Contact-->
						<div class="pt-8 pb-6">
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Müşteri Temsilcisi:</span>
								<span class="text-muted">{{ $detail->user->name }} {{ $detail->user->surname }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Firma/Bayi:</span>
								<span class="text-muted">{{ $detail->company }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Adres:</span>
								<span class="text-muted">{{ $detail->address }} {{ isset($detail->city) ? $detail->city->city : '' }}/{{ isset($detail->county) ? $detail->county->county : '' }}</span>
							</div>
							<div class="d-flex align-items-center justify-content-between mb-2">
								<span class="font-weight-bold mr-2">Yetkili:</span>
								<span class="text-muted">{{ $detail->name }} {{ $detail->phone }} {{ $detail->email }}</span>
							</div>
							@if($detail->project)
								<div class="d-flex align-items-center justify-content-between mb-2">
									<span class="font-weight-bold mr-2">Proje Adı:</span>
									<span class="text-muted">{{ $detail->project->title }}</span>
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
						@elseif($detail->status=='Keşif Bekliyor')
						<span href="#" class="btn btn-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Keşif Tamamlandı')
						<span href="#" class="btn btn-light-success font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Kabul Edildi')
						<span href="#" class="btn btn-light-info font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@elseif($detail->status=='Reddedildi')
						<span href="#" class="btn btn-light-danger font-weight-bold py-3 px-6 mb-2 text-center btn-block">{{ $detail->status }}
						</span>
						@endif
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
				<div class="card card-custom mb-5">
					<div class="card-body py-4">
						<div class="d-flex">
              @if($authenticated->power('exploration', 'edit'))
            		<a href="{{ route('update-exploration', ['id' => $detail->id]) }}" class="btn btn-lg btn-light-success font-weight-bolder mr-3">Düzenle</a>
							@endif
						</div>
					</div>
				</div>
			</div>
			<!--end::Aside-->
			<!--begin::Content-->
			<div class="flex-row-fluid ml-lg-8">
				<!--begin::Mixed Widget 14-->
				<div class="row">
					<div class="col-lg-6">
						<div class="card card-custom gutter-b">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">Genel Keşif Bilgileri</h3>
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
					</div>
					<div class="col-lg-6">
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">Ek Görseller
										<span class="d-block text-muted pt-2 font-size-sm">dosyalara tıklayarak görüntüleyebilirsiniz</span></h3>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body">
								<div class="showgallery" >
									@foreach($photos as $b)
									<!--begin::Item-->
									<a href="{{ Storage::url('snap/exploration/') }}{{ $b->filename }}" class="active-gallery d-flex align-items-center flex-wrap mb-10">
										<!--begin::Symbol-->
										<div class="symbol symbol-50 symbol-light mr-5">
											<span class="symbol-label">
														{!! image_format(Storage::url('snap/exploration/').$b->filename) !!}
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
				<!--end::Mixed Widget 14-->
				@foreach($detail->designs as $d)
				<div class="card card-custom {{ $d->is_active ? '' : 'card-collapsed' }}" data-card="true" id="kt_card_4">
					<!--begin::Header-->
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">{{ date('d M, Y H:i', strtotime($d->created_at)) }} Tarihli Keşifler</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-1" data-card-tool="toggle">
							<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">
						<div>
							<div class="row">
								<div class="col-sm-12">
									<p>{{ $d->comment }}</p>
								</div>
								<div class="showgallery row">
									@foreach($d->designs as $s)
									<div class="col-sm-2 mb-5">
									<a href="{{ Storage::url('snap/exploration/') }}{{ $s->file }}" data-iframe="true" target="_blank" class="active-gallery">
										<div class="card card-custom overlay">
											<div class="card-body p-0">
												<div class="overlay-wrapper">
													@if(pathinfo(Storage::url('snap/exploration/').$s->file, PATHINFO_EXTENSION)=='jpg'||pathinfo(Storage::url('snap/exploration/').$s->file, PATHINFO_EXTENSION)=='jpeg' || pathinfo(Storage::url('snap/exploration/').$s->file, PATHINFO_EXTENSION)=='png'||pathinfo(Storage::url('snap/exploration/').$s->file, PATHINFO_EXTENSION)=='gif')
														<img src="{{ Storage::url('snap/exploration/') }}{{ $s->file }}" alt="" class="w-100 rounded" style="height:100px; width:100px; object-fit:cover;"/>
													@else 
														<div style="width:100px; height:100px; background-color:#f7f7f7;">
															{{ Metronic::getSVG("media/svg/icons/Files/Download.svg", "svg-icon-2x svg-icon-primary d-block") }}
														</div>
													@endif
												</div>
													<div class="overlay-layer align-items-end justify-content-center">
														<div class="d-flex flex-grow-1 flex-center bg-white-o-5 py-5">
																Görüntüle
														</div>
													</div>
												</div>
										</div>
										</a>
									</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="scroll-me" id="{{ $loop->last ? 'design-messages' : '' }}" style="max-height:500px; overflow:scroll">
							<!--begin::Messages-->
							<div class="messages exploration-design-comment design-comments-here">
								@include('order.exploration.exploration-design-comment')
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->
					@if($d->is_active && $detail->status!='Onaylandı' && $detail->status!='Reddedildi')
					<!--begin::Footer-->
					<div class="card-footer align-items-center">
						<form action="{{ route('save-exploration-design-comment') }}" method="POST" class="exploration-design-comment-form">
						@csrf
						<input type="hidden" name="exploration_id" value="{{ $detail->id }}">
						<input type="hidden" name="id" value="{{ $d->id }}">
						<!--begin::Compose-->
						<textarea class="form-control border-0 p-0" name="comment"  required rows="2" placeholder="Mesajınızı yazın"></textarea>
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
						<!--begin::Compose-->
						</form>
					</div>
					<!--end::Footer-->
					@endif
				</div>
				@endforeach
				<!--begin::Row-->
				
				<!--begin::Card-->
				<div class="card card-custom" style="margin-top:25px;">
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
								@include('order.exploration.messages')
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->
					<div class="card-footer align-items-center">
						<form action="{{ route('save-exploration-message') }}" method="POST" class="comment-form">
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


<div class="modal fade" id="designs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<form action="{{ route('save-exploration-design') }}" class="form modal-form" method="POST">
		<input type="hidden" name="id" value="{{ $detail->id }}">
		@csrf
		<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Keşif Dosyaları</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
								</button>
						</div>
						<div class="modal-body">
							<div class="form-group row" style="margin-top:25px;">
								<div class="col-lg-9">
									<textarea name="comment" placeholder="Açıklama girebilirsiniz." required id="" cols="30" rows="3" class="form-control"></textarea>
									<div class="dropzone dropzone-multi" id="kt_dropzone_5" style="margin-top:20px;">
										<div class="dropzone-panel mb-lg-0 mb-2">
											<a class="dropzone-select btn btn-light-primary font-weight-bold btn-sm">Keşfe Ait Dosyaları Ekle</a>
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
								<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Kapat</button>
								<button type="submit" class="btn btn-primary font-weight-bold">Kaydet</button>
						</div>
				</div>
		</div>
	</form>
</div>

@endsection
@section('scripts')
<script>
$('.exploration-comment-form').ajaxForm({ 
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
						$(".formprogress").hide();
		location.reload();
		$('.dropzone-item').remove();
		$('.exploration-comment-form textarea').val('');
	}
}); 
$('.exploration-design-comment-form').ajaxForm({ 
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
						$(".formprogress").hide();


		$('.dropzone-item').remove();
		$('.exploration-design-comment-form textarea').val('');
	}
}); 

$('.modal-form').ajaxForm({ 
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
				location.reload();
        $('#designs').modal('hide');
      }else{
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
	
function change_status(id, val){
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'danger',
        message: 'Lütfen Bekleyin...'
    });
    $.ajax({
        url: '{{ route("update-exploration-status") }}',
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
							location.reload();
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
    $('.exploration-comment-form textarea').val('');
  }
});
var design_comment_id = '#dropzone_design_comment';

// set the preview element template
var previewNode = $(design_comment_id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var dropzone_design_comment = new Dropzone(design_comment_id, { // Make the whole body a dropzone
    url: "{{ route('upload-exploration') }}", // Set the url for your upload script location
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
    url: "{{ route('upload-exploration') }}", // Set the url for your upload script location
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
<script>
var kt_5_id = '#kt_dropzone_5';

// set the preview element template
var previewNode = $(kt_5_id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var myDropzone5 = new Dropzone(kt_5_id, { // Make the whole body a dropzone
    url: "/exploration/upload", // Set the url for your upload script location
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
    previewsContainer: kt_5_id + " .dropzone-items", // Define the container to display the previews
    clickable: kt_5_id + " .dropzone-select" // Define the element that should be used as click trigger to select files.
});

myDropzone5.on("addedfile", function(file) {
		// Hookup the start button
    $(document).find( kt_5_id + ' .dropzone-item').css('display', '');
});

// Update the total progress bar
myDropzone5.on("totaluploadprogress", function(progress) {
    $( kt_5_id + " .progress-bar").css('width', progress + "%");
});

myDropzone5.on("sending", function(file) {
    // Show the total progress bar when upload starts
    $( kt_5_id + " .progress-bar").css('opacity', "1");
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone5.on("complete", function(progress) {
    var thisProgressBar = kt_5_id + " .dz-complete";
    setTimeout(function(){
        $( thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
    }, 300)
});
	</script>
	<script>
var design_comment_id = '#dropzone_design_comment';

// set the preview element template
var previewNode = $(design_comment_id + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var dropzone_design_comment = new Dropzone(design_comment_id, { // Make the whole body a dropzone
    url: "/exploration/upload", // Set the url for your upload script location
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
    url: "/exploration/upload", // Set the url for your upload script location
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

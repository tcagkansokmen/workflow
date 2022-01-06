{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="container">
  <!--begin::Profile Overview-->
  <div class="d-flex flex-row">
    <!--begin::Aside-->
      @include('hr.notice.candidate.left')
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
      <!--begin::Advance Table: Widget 7-->
      <div class="card card-custom">
        <div class="card-header flex-wrap pt-2 pb-2">
            <div class="card-title">
                <h3 class="card-label">{{ $page_title ?? null }}
                  <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
                </h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
          <form class="form general-form" method="POST" action="{{ route('save-interview-rating') }}" >
          @csrf
          <input type="hidden" name="id" value="{{ $interview->id ?? '' }}">
          <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
          <input type="hidden" name="type" value="rating">
          <div class="portlet__body">
            

            <style>
            .simple-rating i{
              color: rgba(0,0,0,0.10);
              display: inline-block;
              padding: 1px 2px;
              cursor: pointer;
              font-size:22px;
            }
            .simple-rating i.active{
              color: #5d78ff;
            }
            </style>
            <h5 class="font-dark">Yetkinlik Değerlendirme</h5>
            @foreach($perfections as $p)
            <div class="form-group row" style="margin-top:15px;">
              <label class="col-xl-3 col-lg-3 col-form-label">{{ $p->perfection->name }}</label>
              <div class="col-lg-9">
                <input type="hidden" name="rating[{{ $loop->iteration }}][perfection_id]" value="{{ $p->perfection->id }}">
                <div class="rate">
                  <input class="rating" type="hidden" name="rating[{{ $loop->iteration }}][rating]" value="{{ $p->rating ? round($p->rating) : 0 }}">
                    <div class="simple-rating star-rating">
                      <i class="fa fa-star" data-rating="1"></i>
                      <i class="fa fa-star" data-rating="2"></i>
                      <i class="fa fa-star" data-rating="3"></i>
                      <i class="fa fa-star" data-rating="4"></i>
                      <i class="fa fa-star" data-rating="5"></i>
                    </div>

                  <textarea id="" cols="30" rows="2" name="rating[{{ $loop->iteration }}][notes]" class="form-control" placeholder="Notlarınız" style="margin-top:15px;">{{ $p->notes ?? null }}</textarea>
                </div>
              </div>
            </div>
            <hr>
            @endforeach
            
            <div class="divider">
                <span></span>
                <hr>
                <span></span>
            </div>
            <h5 class="font-dark">Sonuç</h5>
            <div class="form-group row ">
              <label class="col-xl-3 col-lg-3 col-form-label">* Görüş</label>
              <div class="col-lg-4">
                <div class="radio-inline">
                  <label class="radio radio--bold radio--success">
                    <input type="radio" name="status" value="olumlu_mulakat"
                    @if(isset($interview['status']))
                      @if($interview['status'] == "olumlu_mulakat")
                        checked
                      @endif
                    @endif>
                    <span></span> Olumlu
                  </label>
                  <label class="radio radio--bold radio--success">
                    <input type="radio" name="status" value="olumsuz_mulakat"
                    @if(isset($interview['status']))
                      @if($interview['status'] == 'olumsuz_mulakat')
                        checked
                      @endif
                    @endif>
                    <span></span> Olumsuz
                  </label>
                  <label class="radio radio--bold radio--success">
                    <input type="radio" name="status" value="randevu_gelmedi"
                    @if(isset($interview['status']))
                      @if($interview['status'] == 'randevu_gelmedi')
                        checked
                      @endif
                    @endif>
                    <span></span> Randevuya Gelmedi
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-top:15px;">
              <label class="col-xl-3 col-lg-3 col-form-label">* Genel Notlar</label>
              <div class="col-lg-9">
                  <textarea class="form-control summernote" type="text" required name="notes"  placeholder="Açıklama">{{ $interview->notes ?? '' }}</textarea>
              </div>
            </div>

            @isset($interview->id)

            <div class="repeater">
              <div class="form-group form-group-last row ">
                <label class="col-lg-3 col-form-label">Dosyalar:</label>
                <div data-repeater-list="file" class="col-lg-9">
                  
                  @if(count($candidate_files))
                  @foreach($candidate_files as $p)
                  <div data-repeater-item class="form-group row align-items-center">
                    <div class="col-md-4">
                    <input type="hidden" name="id" value="{{ $p->id }}">
                      <div class="form__group--inline">
                        <div class="form__control">
                          <input type="text" class="form-control" name="title" value="{{ $p->title ?? '' }}" placeholder="Dosya Adı">
                        </div>
                      </div>
                      <div class="d-md-none margin-b-10"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="file_side">
                        <a target="_blank" href="{{ Storage::url('uploads/candidate') }}/{{ $p->file }}" class="custom-file-upload btn btn-bold btn-md btn-light-brand" style="margin-bottom:0;">
                        <i class="la la-search"></i> Görüntüle
                        </a>
                      </div>
                      <div class="d-md-none margin-b-10"></div>
                    </div>
                    <div class="col-md-2">
                      <a href="javascript:;" data-repeater-delete="" class="btn-sm btn-icon btn btn-light-danger btn-bold">
                        <i class="la la-trash-o"></i>
                      </a>
                    </div>
                  </div>
                  @endforeach
                  @endif

                  @if($demand->status != 'kapatıldı')
                  <div data-repeater-item class="form-group row align-items-center">
                    <div class="col-md-4">
                      <div class="form__group--inline">
                        <div class="form__control">
                          <input type="text" class="form-control" name="title" placeholder="Dosya Adı">
                        </div>
                      </div>
                      <div class="d-md-none margin-b-10"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="file_side">
                      <input type="hidden" class="file_input" name="file_input" >
                              <label for="file-upload" class="custom-file-upload btn btn-bold btn-md btn-light-info" style="margin-bottom:0;">
                              <i class="la la-plus"></i> Dosya Ekle
                          </label>
                          <input id="file-upload" type="file" accept=".pdf"/>
                      </div>
                      <div class="d-md-none margin-b-10"></div>
                    </div>
                    <div class="col-md-2">
                      <a href="javascript:;" data-repeater-delete="" class="btn-sm btn-icon btn btn-light-danger btn-bold">
                        <i class="la la-trash-o"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group form-group-last row">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-4">
                  <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-light-info">
                    <i class="la la-plus"></i> Yeni Ekle
                  </a>
                </div>
              </div>
              @endif
            </div>
            @endisset

          </div>
          <div class="portlet__foot">
          <button class="btn btn-success" type="submit">Kaydet</button>
          </div>
          </form>
        </div>
        <!--end::Body-->
      </div>
      <!--end::Advance Table Widget 7-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Profile Overview-->
  </div>

  @endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="/js/simple-rating.js" type="text/javascript"></script>
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

  <script>
$("body").on('click', '.star-rating i', function(){
  var a = $(this).attr('data-rating');
  
  $(this).closest('.star-rating').find('.fa-star').removeClass('active');
  $(this).closest('.rate').find('.rating').val(a);

  for(var i = 0; i<a; i++){
    $(this).closest('.star-rating').find('.fa-star:eq('+i+')').addClass('active');
  }
});

$(document).ready(function(){
  $('.rating').rating();
  $( ".rate" ).each(function( index ) {
    var a = $(this).find('.rating').val();

    for(var i = 0; i<a; i++){
      $(this).find('.fa-star:eq('+i+')').addClass('active');
    }

  });
});
  $(document).ready(function(){
    $('.repeater').repeater({
      initEmpty: false,
      defaultValues: {
          'text-input': 'foo'
      },
      show: function () {
          $(this).slideDown();
          formatlar()
      },
      hide: function (deleteElement) {
          $(this).slideUp(deleteElement);
      }
    });
  })
  $("body").on('change', '.file_side input[type="file"]', function(event){
    var thi = $(this);
    var a = $(this).val();
    $(this).prev().addClass("active").html(a);

      var files = event.target.files;
      
    var data = new FormData();
    $.each(files, function(key, value)
    {
      data.append(key, value);
    });

    $.ajax({
      type: "POST",
      url: "{{ route('upload-candidate') }}",
      dataType: 'json',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: data,
      cache: false,
      dataType: 'json',
      processData: false, // Don't process the files
      contentType: false, // Set content type to false as jQuery will tell the server its a query string request
      error: function(){
      },
      beforeSubmit: function(){
      },
      success: function(item){
        thi.closest(".file_side").find(".file_input").val(item.file);
        thi.prev().html(item.file);
      }
    });

  });
				$('.new-form').ajaxForm({ 
						error: function(){
							swal.fire({
								"title": "",
								"text": "Kaydedilemedi",
								"type": "warning",
								"confirmButtonClass": "btn btn-secondary"
							});

						},
						dataType:  'json', 
						success:   function(item){
							window.location.href = item.redirect;
						}
				}); 

$("body").on('click', '.star-rating i', function(){
  var a = $(this).attr('data-rating');
  
  $(this).closest('.star-rating').find('.fa-star').removeClass('active');
  $(this).closest('.rate').find('.rating').val(a);

  for(var i = 0; i<a; i++){
    $(this).closest('.star-rating').find('.fa-star:eq('+i+')').addClass('active');
  }
});

$(document).ready(function(){
  $( ".rate" ).each(function( index ) {
    var a = $(this).find('.rating').val();

    for(var i = 0; i<a; i++){
      $(this).find('.fa-star:eq('+i+')').addClass('active');
    }

  });
});
</script>
@endsection

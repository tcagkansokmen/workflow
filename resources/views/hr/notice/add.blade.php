{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
.select2{
  width:100% !important;
}
table td{
  vertical-align:middle !important;
}
</style>
<style>
.file_side {
position: relative;
overflow: hidden;
display: inline-block;
cursor:pointer;
}

.file_side input[type=file] {
font-size: 100px;
position: absolute;
left: 0;
top: 0;
opacity: 0;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap pt-6 pb-0">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>

  <form class="form general-form form--label-right" method="POST" action="{{ route('save-notice') }}" >
  @csrf
    <div class="card-body">
      <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
        <div class="portlet__body">
          <div class="section section--first">
            <div class="section__body">
              @if(!Request::get('ik'))
              <div class="form-group row ">
                <label class="col-xl-3 col-lg-3 col-form-label">* Departman</label>
                <div class="col-lg-6">
                  <div class="form-group">
                    @component('components.forms.select', [
                        'required' => true,
                        'name' => 'department_id',
                        'value' => $detail->department_id ?? '',
                        'values' => $departmanlar ?? array(),
                        'class' => 'select2-standard'
                    ])
                    @endcomponent
                  </div>
                </div>
              </div>

              <div class="form-group row ">
                <label class="col-xl-3 col-lg-3 col-form-label">* Pozisyon/Personel Sayısı</label>
                <div class="col-lg-3">
                  <div class="form-group">
                    @component('components.forms.select', [
                        'required' => true,
                        'name' => 'position_id',
                        'value' => $detail->position_id ?? '',
                        'values' => $title ?? array(),
                        'class' => 'select2-standard'
                    ])
                    @endcomponent
                  </div>
                </div>
                <div class="col-lg-3">
                  <input type="number" name="quantity" class="form-control" value="{{ $detail->quantity ?? '' }}">
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">* Çalışma Şekli/Talep Tarihi</label>
                <div class="col-lg-3">
                  <div class="form-group">
                    @component('components.forms.select', [
                        'required' => true,
                        'name' => 'type',
                        'value' => $detail->type ?? '',
                        'values' => $types ?? array(),
                        'class' => 'select2-standard'
                    ])
                    @endcomponent
                  </div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="demand_date" class="form-control date-format" value="{{ isset($detail->demand_date) ? date_formatter($detail->demand_date) : '' }}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">* Detaylar</label>
                <div class="col-lg-6">
                  <textarea name="details" id="" cols="30" rows="10" class="summernote">{{ $detail->details ?? '' }}</textarea>
                </div>
              </div>
              @endif

              @if(Request::get('ik'))

              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">* İlan Linki / İlan Yayın Tarihi</label>
                <div class="col-lg-3">
                  <input type="text" name="url" class="form-control" placeholder="Lütfen kariyer.net üzerindeki ilana ait bağlantıyı giriniz." value="{{ $detail->url ?? '' }}" required>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="notice_date" class="form-control pick-date date-format" value="@isset($detail->notice_date)
                      @if($detail->notice_date != '0000-00-00')
                      {{date('d-m-Y', strtotime($detail->notice_date)) }} 
                      @endif
                    @endisset">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Yayından Kaldırılma Tarihi</label>
                <div class="col-lg-6">
                    <input type="text" name="notice_end_date" class="form-control pick-date date-format" value="@isset($detail->notice_end_date)
                      @if($detail->notice_end_date != '0000-00-00')
                      {{date('d-m-Y', strtotime($detail->notice_end_date)) }} 
                      @endif
                    @endisset">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">* İlan Başlığı</label>
                <div class="col-lg-6">
                  <input type="text" name="title" class="form-control" value="{{ $detail->title ?? '' }}">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">* İlan Açıklaması</label>
                <div class="col-lg-6">
                  <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $detail->description ?? '' }}</textarea>
                </div>
              </div>
              @endisset

              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-9 col-xl-9 offset-lg-3">
            <div class="d-flex">
            @isset($detail->id)
              @if(Request::get('ik') && $detail->status == "talep edildi")
                <label class="checkbox checkbox--bold checkbox--success">
                  <input type="checkbox" name="is_status" value="1">
                  <span></span>&nbsp;&nbsp;İlanı Yayınla
                </label>
              @endif
            @endisset
            &nbsp;&nbsp;
            <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
  <script>
  $(document).ready(function(){
    $('.summernote').summernote({
    height: 150
    });
  })
  $("body").on('click', '.sorgula', function(e){
      e.preventDefault();
      var thi = $(this);
      var href = $(this).attr('href');
      swal.fire({
          title: "Emin misiniz?",
          text: "Bu işlemi yapmak istediğinizi onaylayın.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Evet!",
          cancelButtonText: "Hayır!",
          reverseButtons: true
      }).then(function(result) {
          if (result.value) {
              $.ajax({
                  url: href,
                  dataType: 'json',
                  type: 'get',
                  success: function(data){
                      if(data.status){
                          qrform.ajax.reload();
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
  </script>
@endsection

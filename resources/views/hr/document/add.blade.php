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

  <form class="form general-form form--label-right" method="POST" action="{{ route('save-document') }}" >
  @csrf
    <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
    <div class="card-body">
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">Kişiye Özel</label>
          <div class="col-lg-3">
              <label class="checkbox checkbox--bold checkbox--success">
                <input type="checkbox" name="is_status" value="1"
                @if(isset($detail)&&count($detail->users))
                  checked
                @endisset
                > 
                <span></span>
              </label>
          </div>
        </div>

        <div class="form-group row kisiler 
        @if(isset($detail)&&count($detail->users))
        @else
        d-none
        @endisset        
        ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Kişi Seçin</label>
          <div class="col-lg-3">
            <select name="user_id[]" id="" multiple class="select2-standard form-control">
              <option value="">Seçiniz</option>
              @foreach($users as $us)
                <option value="{{ $us->id }}"
                @if(isset($detail)&&count($detail->users))
                  @foreach($detail->users as $usr)
                    @if($usr->user_id == $us->id)
                      selected
                    @endif
                  @endforeach
                @endisset
                >{{ $us->name }} {{ $us->surname }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row departmanlar
        @if(isset($detail)&&count($detail->users))
        d-none
        @endisset        
        ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Departmanlar</label>
          <div class="col-lg-3">
            <select name="department_id" id="" class="select2-standard form-control">
              <option value="">Seçiniz</option>
              @foreach($departments as $us)
                <option value="{{ $us->id }}"
                @isset($detail->department_id)
                  @if($detail->department_id == $us->id)
                    selected
                  @endif
                @endisset
                >{{ $us->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row  ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Kategori</label>
          <div class="col-lg-3">
            <select name="category_id" id="" class="select2-standard form-control">
              <option value="">Seçiniz</option>
              @foreach($categories as $us)
                <option value="{{ $us->id }}"
                @isset($detail->category_id)
                  @if($detail->category_id == $us->id)
                    selected
                  @endif
                @endisset
                >{{ $us->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Gizlilik Derecesi</label>
          <div class="col-lg-3">
            <select name="priority" id="" class="select2-standard form-control">
              <option value="">Seçiniz</option>
              <option value="1"
                @isset($detail->priority)
                  @if($detail->priority == 1)
                    selected
                  @endif
                @endisset
                >Gizli</option>
              <option value="2"
                @isset($detail->priority)
                  @if($detail->priority == 2)
                    selected
                  @endif
                @endisset
                >Hassas</option>
              <option value="3"
                @isset($detail->priority)
                  @if($detail->priority == 3)
                    selected
                  @endif
                @endisset
                >Hizmet Özel</option>
              <option value="4"
                @isset($detail->priority)
                  @if($detail->priority == 4)
                    selected
                  @endif
                @endisset
                >Halka Açık</option>
              <option value="5"
                @isset($detail->priority)
                  @if($detail->priority == 5)
                    selected
                  @endif
                @endisset
                >Kişiye Özel</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">Onay Türü</label>
          <div class="col-lg-9">
              <label class="radio radio--bold radio--success mb-5" style="margin-right:15px;">
                <input type="radio" name="sign_type" value="mobile"
                @isset($detail->mobile_sign)
                  @if($detail->mobile_sign)
                    checked
                  @endif
                @endisset
               > 
                <span class="mr-3"></span>
                Mobil İmza
              </label>
              <label class="radio radio--bold radio--success mb-5" style="margin-right:15px;">
                <input type="radio" name="sign_type" value="confirmation"
                @isset($detail->confirmation)
                  @if($detail->confirmation)
                    checked
                  @endif
                @endisset
               > 
                <span class="mr-3"></span>
                Okudum/Anladım
              </label>
              <label class="radio radio--bold radio--success mb-5" style="margin-right:15px;">
                <input type="radio" name="sign_type" value="none"
                @isset($detail->confirmation)
                  @if(!$detail->confirmation&&!$detail->mobile_sign)
                    checked
                  @endif
                @endisset
               > 
                <span class="mr-3"></span>
                Onay Gerekmiyor
              </label>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Doküman Başlığı</label>
          <div class="col-lg-3">
            <input type="text" name="title" class="form-control" value="{{ $detail->title ?? '' }}">
          </div>
        </div>
        

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Doküman Açıklaması</label>
          <div class="col-lg-3">
            <textarea name="notes" id="" cols="30" rows="10" class="form-control">{{ $detail->notes ?? '' }}</textarea>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">Doküman</label>
          <div class="col-lg-3">
            <div class="custom-file" style="margin-bottom:15px;">
						  	<input type="file" name="file" accept=".pdf, .docx, .jpg, .jpeg, .png, .ppt, .pptx" class="custom-file-input" id="customFile">
						  	<label class="custom-file-label" style="text-align:left !important;" for="customFile">Dosya seçiniz</label>
            </div>
            @isset($detail->filename)
              <a href="{{ Storage::url('uploads/document') }}/{{ $detail->filename }}" target="_blank" class="btn btn-dark"><i class="fa fa-eye"></i> Dosyayı Görüntüle</a>
            @endisset

          </div>
          </div>
        </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-9 col-xl-9 offset-lg-3">
            <div class="d-flex">
            <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
            </div>
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
  $('[name=photo]').change(function () {
    var file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
      $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
    }
  });
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
  $("body").on('change', '[name=is_status]', function(){
    if($(this).is(':checked')){
      $(".kisiler").removeClass('d-none');
      $(".departmanlar").addClass('d-none');
    }else{
      $(".departmanlar").removeClass('d-none');
      $(".kisiler").addClass('d-none');
    }
  });
  </script>
@endsection

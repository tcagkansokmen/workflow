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

  <form class="form general-form form--label-right" method="POST" action="{{ route('save-announcement') }}" >
  @csrf
    <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
    <div class="card-body">
    
    <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Departmanlar</label>
          <div class="col-lg-6">
            <select name="department_id" id="" class="select2-standard form-control">
              <option value="">Seçiniz</option>
              <option value="Herkes"
                @isset($detail->department_id)
                  @if($detail->department_id == 0)
                    selected
                  @endif
                @endisset>Herkese Gönder</option>
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
          <div class="col-lg-6">
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
          <label class="col-xl-3 col-lg-3 col-form-label">* Duyuru Başlığı</label>
          <div class="col-lg-6">
            <input type="text" name="title" class="form-control" value="{{ $detail->title ?? '' }}">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Fotoğraf</label>
          <div class="col-lg-6">
            <div class="custom-file" style="margin-bottom:15px;">
                <input type="file" name="photo" accept=".jpg, .jpeg, .png" class="custom-file-input" id="customFile">
                <label class="custom-file-label" style="text-align:left !important;" for="customFile">Dosya seçiniz</label>
            </div>
            @isset($detail->photo)
              <a href="{{ Storage::url('uploads/announcement') }}/{{ $detail->photo }}" target="_blank" class="btn btn-dark"><i class="fa fa-eye"></i> Fotoğrafı Görüntüle</a>
            @endisset

          </div>
        </div>
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Dosya</label>
          <div class="col-lg-6">
            <div class="custom-file" style="margin-bottom:15px;">
                <input type="file" name="file" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xls, .xlsx, .zip, .csv" class="custom-file-input" id="customFile">
                <label class="custom-file-label" style="text-align:left !important;" for="customFile">Dosya seçiniz</label>
            </div>
            @isset($detail->file)
              <a href="{{ Storage::url('uploads/announcement') }}/{{ $detail->file }}" target="_blank" class="btn btn-dark"><i class="fa fa-file"></i> Dosyayı Görüntüle</a>
            @endisset
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Duyuru Açıklaması</label>
          <div class="col-lg-6">
            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $detail->description ?? '' }}</textarea>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Yayın/Yayından Kaldırılma Tarihi</label>
          <div class="col-lg-3">
            <input type="text" name="start_at" class="form-control date-format" value="{{ isset($detail->start_at) ? date('d-m-Y', strtotime($detail->start_at)) : '' }}">
          </div>
          <div class="col-sm-3">
            <input type="text" name="end_at" class="form-control date-format" value="{{ isset($detail->end_at) ? date('d-m-Y', strtotime($detail->end_at)) : '' }}">
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
  <script>
  $(document).ready(function(){
    $('.date-time').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
  });
  </script>
@endsection

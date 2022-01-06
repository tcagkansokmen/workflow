{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
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

<div class="container">
  <div class="card card-custom gutter-b">
    <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">Maaş Listesi</h3>
      </div>
      <div class="card-toolbar">
          <a href="{{ route('maaslar-odeme-bekleyen') }}?status=Bekliyor" class="btn btn-light-info btn-bold btn-icon-h ml-10" >
            Bekleyenler
          </a>&nbsp;
          <a href="{{ route('maaslar-odeme-bekleyen') }}?status=odenen" class="btn btn-light-success btn-bold btn-icon-h ml-10" >
            Ödenenler
          </a>&nbsp;
          <a href="{{ route('maaslar-odeme-bekleyen') }}?status=bordro" class="btn btn-light-warning btn-bold btn-icon-h ml-10" >
            Bordro Bekleyenler
          </a>
      </div>
    </div>
     <div class="card-body">
      <table class="table table-striped- table-hover table-checkable" id="kt_table_1">
        <thead>
          <tr>
            <th>Personel</th>
            <th>Dönem</th>
            <th>Tutar</th>
            <th>Bordro</th>
            <th>Durum</th>
            <th class="align-right">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr>
          <td>
            <div class="kt-user-card-v2">
              @if($d->user->avatar)
                <a class=" kt-media mr-5 t-5" >
                    <img src="{{ Storage::url('uploads/users') }}/{{ $d->user->avatar }}" alt="image">
                </a>
              @else
                <span class="kt-media kt-media--md kt-media--danger mr-5 t-5">
                    <span>{{ strtoupper(substr($d->user->name, 0, 2)) }}</span>
                </span>
              @endif
              <div class="kt-user-card-v2__details">
                <a class="kt-user-card-v2__name">{{ $d->user->name }}</a>
                <span class="kt-user-card-v2__email">{{ $d->user->title }}</span>
              </div>
            </div>
            </td>
            <td>
              <span class="btn btn-label-info">{{ $d->month }} / {{ $d->year }}</span>
            </td>
            <td>
              <span class="btn btn-label-danger">{{ $d->wage }}</span>
            </td>
            <td>
              @if(!$d->bordro)
              <div class="file_side" data-id="{{ $d->id }}">
                <input type="hidden" class="file_input" name="file_input">
                  <label for="file-upload" class="custom-file-upload btn btn-bold btn-md btn-label-brand btn-block" style="margin-bottom:0;">
                    <i class="la la-plus"></i> Dosya Ekle
                  </label>
                  <input id="file-upload" type="file" accept=".pdf, .jpg, .png, .jpeg" capture="camera">
              </div>
              @else
              <a 
              data-fancybox data-caption="{{ $d->user->name }} - {{ $d->user->surname }}"
              href="{{ Storage::url('uploads/wage') }}/{{ $d->bordro }}" class="btn btn-bold btn-sm btn-font-sm  btn-label-warning" target="_blank">
                <i class="flaticon-file-1"></i>
              </a>
              @endif
            </td>
            <td class="status-side">
              @if($d->is_paid)
                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">ödendi</span>
              @else
                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Bekliyor</span>
              @endif
            </td>
            <td style="white-space:nowrap">
              <button class="btn btn-outline-success btn-icon btn-sm odeme-onayla" data-id="{{ $d->id }}" data-status="1"
              @if($d->is_paid)
                disabled
              @endif
              >
                <i class="fa fa-check"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tfoot>
      </table>

      <!--end: Datatable -->
    </div>
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
$("body").on('click', '.custom-file-upload', function(){
  $(this).next().trigger('click');
});
$("body").on('change', '.file_side input[type="file"]', function(event){
console.log('çalıştı');
  var thi = $(this);
  var a = $(this).val();
  $(this).prev().addClass("active").html(a);
  var id = $(this).closest('.file_side').attr('data-id');
  
  $("button[type=submit]").addClass('disabled');

    var files = event.target.files;
    
  var data = new FormData();
  $.each(files, function(key, value)
  {
    data.append(key, value);
  });

  $.ajax({
    type: "POST",
    url: "/wage/upload/" + id,
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
      $("button[type=submit]").removeClass('disabled');
      thi.closest(".file_side").find(".file_input").val(item.file);
      thi.prev().html(item.file);
    }
  });
});


$(document).ready(function(){
  $("body").on('click', '.odeme-onayla', function(event){
    var thi = $(this);
    var a = $(this).attr('data-status');
    var id = $(this).attr('data-id');
    
    var r = confirm("Emin misiniz?");
    if (r == true) {
      $.ajax({
        type: "POST",
        url: "/wage/odeme",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'id='+id+"&status="+a,
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(result){

          if(result.is_paid){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">ödendi</span>';
          }else{
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Bekliyor</span>';
          }

          thi.closest("tr").find('.status-side').html(status);
        }
      });
    }

        
  });
});
</script>
@endsection

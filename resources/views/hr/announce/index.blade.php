{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
.nowrap{
  white-space:nowrap;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        @if($authenticated->power('announcement', 'add'))
        <a href="{{ route('add-announcement') }}" class="btn btn-primary font-weight-bolder">
        <span class="svg-icon svg-icon-md">
          {{ Metronic::getSVG("media/svg/icons/Navigation/Plus.svg", "svg-icon-1x") }}
        </span>Yeni Ekle</a>
        @endif
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable" id="teklif-listesi">
      </table>
    </div>
  </div>
</div>

<div class="modal reason-modal fade" id="yorum_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Açıklama Girebilirsiniz</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <textarea id="" cols="30" rows="10" class="form-control aciklama"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary document-state">Kaydet</button>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

  {{-- page scripts --}}
  <script>
  $('.select2-standard').select2({
  placeholder: "Seçiniz",
  allowClear: true,
  });
  var qrform = $('#teklif-listesi').DataTable({
      responsive: true,
      "processing": true,
      "serverSide": true,
      "ajax": "{{ route('announcements-json') }}?{!! \Request::getQueryString() !!}",
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 3, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()
      },
      columns: [
          { data: 'title', name: 'title', title: 'Duyuru', "render": function (data, type, row) {
              return '\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.title + '</div>\
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.category.name + "</a>\
                </div>\
                </div>"
              }
          },
          { data: 'user.name', name: 'user.name', title: 'Oluşturan', "render": function (data, type, row) {
                return '\
                  <div class="ml-4">\
                    <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.user.name + ' ' + row.user.surname + '</div>\
                    <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.user.title + "</a>\
                  </div>\
                </div>"
              },
          },
          { data: 'priority', name: 'priority', className:'nowrap', title: 'Hedef', "render": function (data, type, row) {
              if(!row.department_id){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-primary">Herkes</span>'
              }else{
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">'+ row.department.name +'</span>'
              }
            },
          },
          { data: 'start_at', name: 'start_at', title: 'Yayın', "render": function (data, type, row) {
                return '\
                  <div class="ml-4">\
                    <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.start_at_formatted + '</div>\
                    <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.end_at_formatted + "</a>\
                  </div>\
                </div>"
              },
          },
          { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
              if(row.status=='Hazırlanıyor'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">Hazırlanıyor</span>'
              }else if(row.status=='onayda'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-warning">Onay Bekliyor</span>'
              }else if(row.status=='Reddedildi'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger"\
                    data-skin="dark" \
                    data-toggle="tooltip" \
                    data-placement="top" title="" data-original-title="' + row.description + '">Reddedildi</span>'
              }else if(row.status=='Onaylandı'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Onaylandı</span>'
              }else if(row.status=='kaldırıldı'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">Yayından Kaldırıldı</span>'
              }
            },
          },
          { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
              var editme = '<a href="{{ route("update-announcement") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Güncelle">\
              <i class="flaticon2-writing icon-md text-primary"></i>\
              </a>&nbsp;';

              var deleteme = "";

              deleteme = '<a href="#" class="btn btn-sm btn-outline-danger document-state" data-id="'+data+'" data-status="kaldırıldı">\
                    Yayından Kaldır\
                  </a>';

              var result = '';

              
              result += row.edit_allowed ? editme : '';
              if(row.delete_allowed){
                result += '<a href="{{ route("delete-announcement") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-danger sorgula" title="Sil">\
                <i class="la la-trash text-danger"></i>\
              </a>&nbsp;'
              }
              if(row.status=='Onaylandı'&&row.confirmation_allowed){
              result += row.delete_allowed ? deleteme : '';
              }

              if(row.status=='onayda'){
                result += '\
                  <a href="#" class="btn btn-sm btn-outline-success document-state" data-id="'+data+'" data-status="Onaylandı">\
                    Onayla\
                  </a>\
                  <a href="#" class="btn btn-sm btn-outline-danger document-state" data-id="'+data+'" data-status="Reddedildi">\
                    Reddet\
                  </a>'
              }

              if(row.status=='Hazırlanıyor'&&row.send_confirmation_allowed){
                result += '<a href="#" class="btn btn-sm btn-outline-info document-state" data-id="'+data+'" data-status="onayda">\
                  Onaya Gönder\
                </a>'
              }

              return '<div style="white-space:nowrap">'+result+'</div>';
          }
        },
      ],
      buttons: [
      'print',
      'copyHtml5',
      'excelHtml5',
      { extend: 'csvHtml5', text: 'CSV'  },
      'pdfHtml5',
      ]
  });
  </script>
  <script>
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
$(document).ready(function(){
  $('body').on('click', '.duyuru-sil', function(e){
    e.preventDefault();

    var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
    if (r == true) {
      window.location.href = $(this).attr('href');
    }

  });
  $('body').on('click', '.document-state', function(e){
    e.preventDefault();
    var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
    if (r == true) {
      var id = $(this).attr('data-id');
      var value = $(this).attr('data-status');

      if(value == "Reddedildi"){
        if($(this).closest(".reason-modal").length){
          var aciklama = $(".aciklama").val();
          $.ajax({
            url: "{{ route('update-announcement-status') }}",
            data: "id=" + id + "&value=" + value + "&aciklama="+aciklama,
            dataType: 'json',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(){
            },
            beforeSubmit: function(){
            },
            success: function(data){
              if(data.status){
                qrform.ajax.reload();
              }else{
              swal.fire({
                "title": "",
                "text": data.message,
                "type": "warning",
                "confirmButtonClass": "btn btn-secondary"
              });
              }
            }
          });
        }else{
          $(".reason-modal .document-state").attr('data-id', id);
          $(".reason-modal .document-state").attr('data-status', value);
          $(".reason-modal").modal('show');
        }
      }else{
        $.ajax({
            url: "{{ route('update-announcement-status') }}",
          data: "id=" + id + "&value=" + value,
          dataType: 'json',
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          error: function(){
          },
          beforeSubmit: function(){
          },
          success: function(data){
              if(data.status){
                qrform.ajax.reload();
              }else{
              swal.fire({
                "title": "",
                "text": data.message,
                "type": "warning",
                "confirmButtonClass": "btn btn-secondary"
              });
              }
          }
        });
      }
    }
});
});
  </script>
@endsection

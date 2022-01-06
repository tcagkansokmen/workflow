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
          @if($authenticated->power('notice', 'demand'))
          <a href="{{ route('candidate-pool') }}" class="btn btn-light-info btn-icon-sm">
            Aday Havuzu
          </a>&nbsp;&nbsp;
          <a href="{{ route('personel-demand') }}" class="btn btn-primary btn-icon-sm">
            Yeni Talep Ekle
          </a>
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
      "ajax": "{{ route('notice-json') }}?{!! \Request::getQueryString() !!}",
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 0, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()

      },
      columns: [
          { data: 'id', name: 'id', title: '#', order: 'desc', visible: false },
          { data: 'user.name', name: 'user.name', title: 'Talep Eden', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                <div class="symbol symbol-light-danger flex-shrink-0">\
                  ' + ( row.user.avatar ? '<a href="#" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >\
                      <img src="{{ Storage::url("uploads/users") }}/'+row.user.avatar+'" alt="image">\
                  </a>' : '<span class="symbol-label font-size-h5 kt-margin-r-5 t-5">\
                    <span>' + row.user.name.charAt(0) + row.user.surname.charAt(0) + '</span>\
                </span>' ) + '\
                </div>\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.user.name + ' ' + row.user.surname + '</div>\
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.user.title + "</a>\
                </div>\
                </div>"
              }
          },
          { data: 'position.title', name: 'position.title', title: 'Pozisyon', "render": function (data, type, row) {
                  return '<div class="d-flex align-items-center" style="max-width:220px;">\
                    <div class="ml-4">\
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + data + '</div>\
                      <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.department.name + "</a>\
                    </div>\
                  </div>"
              },
          },
          { data: 'type', name: 'type', title: 'Türü', "render": function (data, type, row) {
                  return '<span class="label label-lg font-weight-bold  label-light-danger label-inline nowrap">' + data + '</span>'
              },
          },
          { data: 'quantity', name: 'quantity', title: 'Personel', "render": function (data, type, row) {
                  return '<span class="label label-lg font-weight-bold label-light-dark label-inline">' + data + '</span>'
              },
          },
          { data: 'quantity', name: 'quantity', title: 'Aday', "render": function (data, type, row) {
                  return '<span class="label label-lg font-weight-bold label-light-primary label-inline">' + row.candidates_count + '</span>'
              },
          },
          { data: 'quantity', name: 'quantity', title: 'Olumlu', "render": function (data, type, row) {
                  return '<span class="label label-lg font-weight-bold label-light-success label-inline">' + row.olumlu + '</span>'
              },
          },
          { data: 'demand_date', name: 'demand_date', title: 'Talep Tarihi', "render": function (data, type, row) {
                  return  row.date_formatted
              },
          },
          { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
              if(data == 'Onaylandı' || data == 'ilan yayınlandı' || data == 'kapatıldı'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">' + data + '</span>'
              }else if(data == 'kaldırıldı'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">' + data + '</span>'
              }else if(data == 'mülakat süreci'){
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-warning">' + data + '</span>'
              }else{
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">' + data + '</span>'
              }
            },
          },
          { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
              var editme = '<a href="{{ route("update-notice") }}/'+data+'?ik=1" class="btn btn btn-icon btn-light btn-hover-info btn-sm" data-toggle="tooltip" data-theme="light" title="İlan">\
              <i class="flaticon2-sheet icon-md text-info"></i>\
              </a>&nbsp;';

              var demandme = '<a href="{{ route("update-notice") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Talebi Güncelle">\
              <i class="flaticon2-writing icon-md text-primary"></i>\
              </a>&nbsp;';

              var detailme = '<a href="{{ route("notice-detail") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

              var deleteme = "";

              deleteme = '<a href="{{ route("delete-notice") }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm sorgula" data-toggle="tooltip" data-theme="light" title="Sil">\
              <i class="flaticon2-trash icon-md text-danger"></i>\
              </a>';

              var result = row.detail_allowed ? detailme : '';
              result += row.demand_allowed ? demandme : '';
              result += row.edit_allowed ? editme : '';
              result += row.delete_allowed ? deleteme : '';

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
  </script>
@endsection

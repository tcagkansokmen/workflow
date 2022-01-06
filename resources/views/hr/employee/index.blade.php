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
          @if($authenticated->power('employee', 'add'))
          <a href="{{ route('add-employee') }}" class="btn btn-primary btn-icon-sm">
            Yeni Personel Ekle
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
      "ajax": "{{ route('employees-json') }}?{!! \Request::getQueryString() !!}",
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
          { data: 'name', name: 'name', title: 'Talep Eden', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                <div class="symbol symbol-light-danger flex-shrink-0">\
                  ' + ( row.avatar ? '<a href="#" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >\
                      <img src="{{ Storage::url("uploads/users") }}/'+row.avatar+'" alt="image">\
                  </a>' : '<span class="symbol-label font-size-h5 kt-margin-r-5 t-5">\
                    <span>' + row.name.charAt(0) + row.surname.charAt(0) + '</span>\
                </span>' ) + '\
                </div>\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.name + ' ' + row.surname + '</div>\
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.title + "</a>\
                </div>\
                </div>"
              }
          },
          { data: 'department.name', name: 'department.name', title: 'Departman', "render": function (data, type, row) {
                  return '<div class="d-flex align-items-center" style="max-width:220px;">\
                    <div class="ml-4">\
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + data + '</div>\
                    </div>\
                  </div>'
              },
          },
          { data: 'phone', name: 'phone', title: 'İletişim', "render": function (data, type, row) {
                  return '<div class="d-flex align-items-center" style="max-width:220px;">\
                    <div class="ml-4">\
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.email + '</div>\
                      <a href="#" class="text-muted font-weight-bold text-hover-primary">' + data + "</a>\
                    </div>\
                  </div>"
              },
          },
          { data: 'check_in_date', name: 'check_in_date', title: 'İşe Başlama Tarihi', "render": function (data, type, row) {
                  return '<span class="label label-lg font-weight-bold label-light-dark label-inline">' + row.date_formatted + '</span>'
              },
          },
          { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
              var editme = '<a href="{{ route("employee-detail") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-info btn-sm" data-toggle="tooltip" data-theme="light" title="Personel Detayları">\
              <i class="flaticon2-sheet icon-md text-info"></i>\
              </a>&nbsp;';

              var result = row.edit_allowed ? editme : '';

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

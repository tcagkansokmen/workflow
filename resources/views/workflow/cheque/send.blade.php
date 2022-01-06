{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="card card-custom">
  <div class="card-header flex-wrap border-0 pt-6 pb-0">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
          <!--begin::Button-->
          <a href="{{ route('add-send-cheque') }}" class="btn btn-primary font-weight-bolder">
          <span class="svg-icon svg-icon-md">
              <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <rect x="0" y="0" width="24" height="24"/>
                      <circle fill="#000000" cx="9" cy="15" r="6"/>
                      <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"/>
                  </g>
              </svg>
              <!--end::Svg Icon-->
          </span>Yeni Ekle</a>
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


{{-- Scripts Section --}}
@section('scripts')
    {{-- vendors --}}
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
        "ajax": "{{ route('send-cheques-json') }}?{!! \Request::getQueryString() !!}",
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
        <'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 10,
        "language": {
        "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 0, "desc" ]],
        columns: [
            { data: 'id', name: 'id', title: '#', order: 'desc' },
            { data: 'supplier', name: 'supplier', title: 'Tedarikçi Adı', "render": function (data, type, row) {
                return '<div class="d-flex align-items-center" style="max-width:220px;">\
                  <div>\
                    <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + data + '</div>\
                  </div>\
                  </div>'
                }
            },
            { data: 'price', name: 'price', title: 'Tutar', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-danger label-inline">'+row.price_formatted +'</span>';
              },
            },
            { data: 'deadline', name: 'deadline', title: 'Vadesi', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">'+row.deadline_formatted +'</span>';
              },
            },
            { data: 'description', name: 'description', title: 'Açıklama'},
            { data: 'status', name: 'status', title: 'Vadesi', "render": function (data, type, row) {
                if(row.status){
                  return '<span class="label label-lg font-weight-bold  label-light-success label-inline">Ödendi</span>';
                }else{
                  return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">Bekliyor</span>';
                }
              },
            },
            { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {

                var status = ""
                if(!row.status){
                  status = '<a href="{{ route("update-send-cheque") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Ödendi İşaretle"><i class="fas fa-edit icon-md text-primary"></i></a>&nbsp;'

                  status += '<a href="{{ route("confirm-cheque") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-success btn-sm teklif-sil" data-toggle="tooltip" data-theme="light" title="Ödendi İşaretle"><i class="fas fa-check icon-md text-success"></i></a>&nbsp;'
                }

                var deleteme = "";

                deleteme = '<a href="{{ route("delete-cheque") }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm teklif-sil" data-toggle="tooltip" data-theme="light" title="Sil">\
                <i class="flaticon2-trash icon-md text-danger"></i>\
                </a>';

                var result = row.edit_allowed ? status : '';
                result += row.delete_allowed ? deleteme : '';

                return '<div style="white-space:nowrap">'+result+'</div>';

            }
          }
        ],
        buttons: [
        'print',
        'copyHtml5',
        'excelHtml5',
        { extend: 'csvHtml5', text: 'CSV'  },
        'pdfHtml5',
        ]
    });
    $("body").on('click', '.teklif-sil', function(e){
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
                    error:function(e){
                      qrform.ajax.reload();
                    },
                    success: function(data){

                        $(".formprogress").hide();
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
    <script>
$("body").on('click', '.delete-cost', function(e){
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

{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">{{ $page_title }}
                    <span class="d-block text-muted pt-2 font-size-sm">{{ $page_description }}</h3>
            </div>
            <div class="card-toolbar">
              <!--begin::Button-->
              @if($authenticated->power('expense', 'add'))
              <a href="{{ route('add-expense') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Fatura</a>
              @endif
              <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->


    <div class="modal fade" id="demand-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Talep Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body demand-details">

                </div>
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
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script>
    var costtable = $('#productcategories').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        ajax: {
            url: "{{ route('expenses-json') }}?{!! \Request::getQueryString() !!}",
        },
        dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
            <'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 10,
        lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
        "language": {
            "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 1, "desc" ]],
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip()
        },
        columns: [
        { data: 'supplier.title', name: 'supplier.title', title: 'Tedarikçi', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.supplier.name + ' ' + row.supplier.surname + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'bill_date', name: 'bill_date', title: 'Fatura', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + row.bill_no + '\
                        </div>\
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">\
                        ' + data + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'price', name: 'price', searchable: false, title: 'Tutar', "render": function (data, type, row) {
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-primary label-inline">'+row.price_formatted+'</span>\
                  <i class="fa fa-pen"  data-skin="light" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+row.description+'"></i>'
            }
        },
        { data: 'vat', name: 'vat', searchable: false, title: 'KDV', "render": function (data, type, row) {
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-primary label-inline">'+row.vat_formatted+'</span>'
            }
        },
        { data: 'price', name: 'price', searchable: false, title: 'Toplam', "render": function (data, type, row) {
              return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">'+row.total_formatted+'</span>'
            }
        },
        { data: 'file', name: 'file', searchable: false, title: 'Dosya', "render": function (data, type, row) {
            if(data){
              return '<a href="{{ Storage::url("snap/expense/") }}'+data+'" target="_blank" class="label label-lg font-weight-bold  label-light-primary label-inline label-light-info label-inline"><i class="flaticon2-download-2"></i></a>'
            }else{

            }
            }
        },
        { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
            if(data=='Yönetici Onayında'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-primary label-inline">Yönetici Onayında</span>';
            }else if(data=='Onaylandı'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-success label-inline">'+data+'</span>';
            }else if(data=='Reddedildi'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-danger label-inline">'+data+'</span>';
            }else if(data=='Ödendi'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-info label-inline">'+data+'</span>';
            }else if(data=='Revize Edildi'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-warning label-inline">'+data+'</span>';
            }else{
                return '<span class="label label-lg font-weight-bold label-inline label-light-warning label-inline">'+data+'</span>';
            }
          }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var editme = '<a href="{{ route("add-expense") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("expense-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm detail-popup" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

            var deleteme = "";

            var status_update = '<a href="#" onclick="change_status('+data+', \'Onaylandı\')" class="btn btn btn-icon btn-light btn-hover-success btn-sm" data-toggle="tooltip" data-theme="light" title="Onayla">\
                <i class="flaticon2-check-mark icon-md text-success"></i>\
            </a>&nbsp;<a href="#" onclick="change_status('+data+', \'Reddedildi\')" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" data-toggle="tooltip" data-theme="light" title="Reddet">\
                <i class="flaticon2-cross icon-md text-danger"></i>\
                </a>&nbsp;';

            var paid_update = '<a href="#" onclick="change_status('+data+', \'Ödendi\')" class="btn btn btn-icon btn-light btn-hover-success btn-sm" data-toggle="tooltip" data-theme="light" title="Ödendi">\
                <i class="flaticon2-check-mark icon-md text-success"></i>\
            </a>';

            var status_bought ='<a href="#" onclick="change_status('+data+', \'Satın Alındı\')" class="btn btn btn-icon btn-light btn-hover-success btn-sm" data-toggle="tooltip" data-theme="light" title="Satın Alındı">\
                <i class="flaticon2-correct icon-md text-success"></i>\
            </a>&nbsp;'

            deleteme = '<a href="{{ route("delete-expense", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
              <i class="flaticon2-trash icon-md text-danger"></i>\
              </a>';
            
            var result = row.confirmation_allowed ? status_update : '';
            result += row.paid_allowed ? paid_update : '';
            result += row.edit_allowed&&!row.confirmation_allowed ? editme : '';
            result += row.delete_allowed&&!row.confirmation_allowed ? deleteme : '';

            return '<div style="white-space:nowrap">'+result+'</div>';
        },
    },
        { data: 'bill_no', name: 'bill_no', title: 'Durum', visible: false }
    ],
    buttons: [
    {
        extend: 'print',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    {
        extend: 'copyHtml5',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    {
        extend: 'excelHtml5',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    {
        extend: 'csvHtml5',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    {
        extend: 'pdfHtml5',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    ]
});

function change_status(id, val){
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'danger',
        message: 'Lütfen Bekleyin...'
    });
    $.ajax({
        url: '{{ route("update-expense-status") }}',
        dataType: 'json',
        type: 'post',
        data: 'id=' + id + '&status=' + val,
        errur: function(){
            KTApp.unblockPage();
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            KTApp.unblockPage();
            if(data.status){
                costtable.ajax.reload();
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
$("body").on('click', '.detail-popup', function(e){
    e.preventDefault();
    var href = $(this).attr('href');

    $.ajax({
        url: href,
        dataType: 'html',
        type: 'get',
        success: function(data){
            $('.demand-details').html(data)
            $('#demand-detail').modal('show')
        }
    });

});

$("body").on('click', '.delete-cost', function(e){
    e.preventDefault();
    var thi = $(this);
    var href = $(this).attr('href');
    swal.fire({
        title: "Emin misiniz?",
        text: "Bunun geri dönüşü yoktur",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Evet, devam et!",
        cancelButtonText: "Hayır, vazgeç!",
        reverseButtons: true
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: href,
                dataType: 'json',
                type: 'get',
                success: function(data){
                    if(data.status){
                        costtable.ajax.reload();
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

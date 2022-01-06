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
              @if($authenticated->power('purchase', 'add'))
              <a href="{{ route('add-purchase') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Talep</a>
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
    function formatMoney(amount, decimalCount = 2, decimal = ",", thousands = ".") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;


            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };
    $('body').on('change', '.pick-all', function(){
        if($(this).is(':checked')){
            $('[data-chkbox-shiftsel]').prop('checked', 'checked');
        }else{
            $('[data-chkbox-shiftsel]').prop('checked', false);
        }
    });
    $(document).ready(function() {
        var chkboxShiftLastChecked = [];
        $('body').on('click', '[data-chkbox-shiftsel]', function(e){
            var chkboxType = $(this).data('chkbox-shiftsel');
            if(chkboxType === ''){
                chkboxType = 'default';
            }
            var $chkboxes = $('[data-chkbox-shiftsel="'+chkboxType+'"]');

            if (!chkboxShiftLastChecked[chkboxType]) {
                chkboxShiftLastChecked[chkboxType] = this;
                return;
            }

            if (e.shiftKey) {
                var start = $chkboxes.index(this);
                var end = $chkboxes.index(chkboxShiftLastChecked[chkboxType]);

                $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', chkboxShiftLastChecked[chkboxType].checked);
            }

            chkboxShiftLastChecked[chkboxType] = this;
        });
    });
    var costtable = $('#productcategories').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        ajax: {
            url: "{{ route('purchases-json') }}?{!! \Request::getQueryString() !!}",
        },
        dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
            <'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 10,
        lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
        "language": {
            "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 3, "desc" ]],
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip()
        },
        columns: [
        { data: 'product.title', name: 'product.title', title: 'Ürün/Talep Eden', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                        ' + data + '\
                        </div>\
                        <a class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.purchase.user.name + ' ' + row.purchase.user.surname + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'quantity', name: 'quantity', searchable: false, title: 'Miktar', "render": function (data, type, row) {
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-primary label-inline">'+data+' '+row.type+'</span>'
            }
        },
        { data: 'price', name: 'price', title: 'Fiyat', "render": function (data, type, row) {
              
            return '<span class="label label-lg font-weight-bold  label-light-dark label-inline label-light-primary label-inline">'+row.total_price_formatted+' TL</span>'
            }
        },
        { data: 'purchase.start_at', name: 'purchase.start_at', title: 'Talep Tarihi', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + row.start_at_formatted + '\
                        </div>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
            if(data=='Yönetici Onayında'){
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-primary label-inline">'+data+'</span>';
            }else if(data=='Onaylandı'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-success label-inline">'+data+'</span>';
            }else if(data=='Reddedildi'){
                return '<span class="label label-lg font-weight-bold  label-light-danger label-inline label-light-danger label-inline">'+data+'</span>';
            }else if(data=='Satın Alındı'){
                return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-light-info label-inline">'+data+'</span>';
            }else if(data=='Revize Edildi'){
                return '<span class="label label-lg font-weight-bold label-inline label-light-warning label-inline">'+data+'</span>';
            }else{
                return '<span class="label label-lg font-weight-bold label-inline label-light-warning label-inline">'+data+'</span>';
            }
          }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var editme = '<a href="{{ route("add-purchase") }}/'+row.purchase.id+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("purchase-detail", ["id" => "."]) }}/'+row.purchase.id+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm detail-popup" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

            var deleteme = "";

            var status_update = '<a href="#" onclick="change_status('+row.purchase_items_id+', \'Onaylandı\')" class="btn btn btn-icon btn-light btn-hover-success btn-sm" data-toggle="tooltip" data-theme="light" title="Onayla">\
                <i class="flaticon2-check-mark icon-md text-success"></i>\
            </a>&nbsp;<a href="#" onclick="change_status('+row.purchase_items_id+', \'Reddedildi\')" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" data-toggle="tooltip" data-theme="light" title="Reddet">\
                <i class="flaticon2-cross icon-md text-danger"></i>\
                </a>&nbsp;';

            var status_bought ='<a href="#" onclick="change_status('+row.purchase_items_id+', \'Satın Alındı\')" class="btn btn btn-icon btn-light btn-hover-success btn-sm" data-toggle="tooltip" data-theme="light" title="Satın Alındı">\
                <i class="flaticon2-correct icon-md text-success"></i>\
            </a>&nbsp;'

            deleteme = '<a href="{{ route("delete-purchase", ["id" => "."]) }}/'+row.purchase_items_id+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
              <i class="flaticon2-trash icon-md text-danger"></i>\
              </a>';
            
            var result = row.status_update_allowed ? status_update : '';
            result += row.status_bought_allowed ? status_bought : '';
            result += row.detail_allowed ? detailme : '';
            result += row.edit_allowed ? editme : '';
            result += row.delete_allowed ? deleteme : '';

            return '<div style="white-space:nowrap">'+result+'</div>';
        },
    },
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
        url: '{{ route("update-purchase-status") }}',
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

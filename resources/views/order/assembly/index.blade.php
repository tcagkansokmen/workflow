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
              @if($authenticated->power('assembly', 'add'))
              <a href="{{ route('add-assembly') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Montaj</a>
              @endif
              <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-sm-3">
                    <label style="display:block;">Müşteri</label>
                    @component('components.forms.select', [
                    'required' => true,
                    'name' => 'customer',
                    'value' => '',
                    'values' => $customers ?? array(),
                    'class' => 'select2-standard customer-filter',
                    ])
                    @endcomponent
                </div>
                <div class="col-sm-3">
                    <label style="display:block;">Durum</label>
                    @component('components.forms.select', [
                    'required' => true,
                    'name' => 'customer',
                    'value' => Request::get('status'),
                    'values' => $statuses ?? array(),
                    'class' => 'select2-standard status-filter',
                    ])
                    @endcomponent
                </div>
                <div class="col-sm-3">
                    <label style="display:block;">Fatura Durumu</label>
                    <select name="" id="" class="form-control select2-standard bill-filter">
                        <option value="">Tümü</option>
                        <option value="1">Fatura Kesilenler</option>
                        <option value="2">Fatura Kesilmeyenler</option>
                    </select>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->

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
            url: "{{ route('assemblys-json') }}?{!! \Request::getQueryString() !!}",
        },
        dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
            <'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 10,
        lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
        "language": {
            "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 0, "desc" ]],
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip()
            $('.new-dropdown').dropdown()
            $('body').on('change', '.customer-filter', function(e){
                var categories = $(this).val();
                costtable.column(8)
                .search(categories)
                .draw();
            }); 

            $('body').on('change', '.status-filter', function(e){
                var val = $(this).val();
                costtable.column(5)
                .search(val)
                .draw();
            }); 

            $('body').on('change', '.bill-filter', function(e){
                var val = $(this).val();
                costtable.column(4)
                .search(val)
                .draw();
            }); 
        },
        columns: [
        { width:20, data: 'id', orderable: true, title: '#', name: 'id'},
        { data: 'customer.title', name: 'customer.title', title: 'Müşteri/Proje', "render": function (data, type, row) {
                return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <span class="symbol symbol-45 symbol-light-dark ">\
                          '+( row.customer.logo ? '<img src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/'+row.customer.logo+'" style="object-fit:contain;" />' : '<span class="symbol-label font-size-h5">'+ data.charAt(0) + ' ' ) +'</span>\
                        </span>\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                        <a href="{{ route("assembly-detail", ["id" => "."]) }}/'+row.id+'" class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.project.title + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'title', name: 'title', title: 'Başlık', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'start_at', name: 'start_at', title: 'Başlangıç', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-light-success label-inline" >'+ data +'</span>';
            }
        },
        { data: 'end_at', name: 'end_at', title: 'Bitiş', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-danger label-inline" >'+ data +'</span>';
            }
        },
        { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
        
            if(row.bills_count){
                return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-light-primary label-inline">Fatura Kesildi</span>';
            }
            if(data=='Montaja Başlandı'){
                    return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">Başladı</span>';
                }else if(data=='Talep Açıldı'){
                    return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-danger label-inline">Talep Açıldı</span>';
                }else if(data=='Montaj Tamamlandı'){
                    return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-success label-inline" >Tamamlandı</span>';
                }
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-info label-inline" >'+data+'</span>';
            }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var statusme = '<div class="dropdown new-dropdown dropdown-inline">\
                            <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="tooltip" title="" data-placement="left" data-original-title="Durumu Güncelle" aria-haspopup="true" aria-expanded="false">\
                                <i class="ki ki-bold-more-hor"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="width:275px;">\
                                <ul class="navi navi-hover">\
                                <li class="navi-item">\
                                    <a href="#" class="navi-link change-status" onclick="change_status('+data+', \'Montaja Başlandı\')" data-id="'+data+'">\
                                    <span class="navi-icon">\
                                        <i class="flaticon2-calendar-8"></i>\
                                    </span>\
                                    <span class="navi-text new-status">Montaja Başlandı</span>\
                                    </a>\
                                </li>\
                                <li class="navi-item">\
                                    <a href="#" class="navi-link change-status" onclick="change_status('+data+', \'Montaj Tamamlandı\')" data-id="'+data+'">\
                                    <span class="navi-icon">\
                                        <i class="flaticon2-checkmark"></i>\
                                    </span>\
                                    <span class="navi-text new-status">Montaj Tamamlandı</span>\
                                    </a>\
                                </li>\
                                </ul>\
                            </div>\
                        </div>'
            var editme = '<a href="{{ route("add-assembly") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("assembly-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

            var deleteme = "";

            deleteme = '<a href="{{ route("delete-assembly", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
            <i class="flaticon2-trash icon-md text-danger"></i>\
            </a>';

            var messageme = '<a href="{{ route("assembly-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" data-toggle="tooltip" data-theme="light" title="'+row.messages+' Yeni Mesaj"><span class="fas fa-envelope icon-md text-danger"></i></a>&nbsp;';

            var result = row.messages>0 ? messageme : '';
            result += row.status_allowed ? statusme : '';
            result += row.detail_allowed ? detailme : '';
            result += row.edit_allowed ? editme : '';
            result += row.delete_allowed ? deleteme : '';

            return '<div style="white-space:nowrap">'+result+'</div>';
        },
      },
      { data: 'project.title', className:"align-right", visible: false, name: 'project.title' }
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

$(document).ready(function(){
    var val = $('.status-filter').val();
    costtable.column(5)
    .search(val)
    .draw();
})

function change_status(id, val){
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'danger',
        message: 'Lütfen Bekleyin...'
    });
    $.ajax({
        url: '{{ route("update-assembly-status") }}',
        dataType: 'json',
        type: 'post',
        data: 'id=' + id + '&val=' + val,
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

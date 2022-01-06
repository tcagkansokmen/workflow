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
              @if($authenticated->power('exploration', 'add'))
              <a href="{{ route('add-exploration') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Keşif</a>
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
            url: "{{ route('explorations-json') }}?{!! \Request::getQueryString() !!}",
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
                        <a href="{{ route("exploration-detail", ["id" => "."]) }}/'+row.id+'" class="text-muted font-weight-bold text-hover-primary">\
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
        { data: 'user.name', name: 'user.name', title: 'Sorumlu', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-light-success label-inline" >'+ row.user.name +' '+ row.user.surname +'</span>';
            }
        },
        { data: 'end_at', name: 'end_at', title: 'Bitiş', visibility: false, "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-danger label-inline" >'+ data +'</span>';
            }
        },
        { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
          if(data=='Başlandı'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">Başladı</span>';
            }else if(data=='Talep Açıldı'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-warning label-inline">'+data+'</span>';
            }else if(data=='Kabul Edildi'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">Kabul Edildi</span>';
            }else if(data=='Keşif Tamamlandı'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-success label-inline">'+data+'</span>';
            }else if(data=='Reddedildi'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-danger label-inline">'+data+'</span>';
            }else{
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-info label-inline">'+data+'</span>';
            }
          }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var statusme = ''
            var editme = '<a href="{{ route("add-exploration") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("exploration-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

            var deleteme = "";

            deleteme = '<a href="{{ route("delete-exploration", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
            <i class="flaticon2-trash icon-md text-danger"></i>\
            </a>';

            var messageme = '<a href="{{ route("exploration-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" data-toggle="tooltip" data-theme="light" title="'+row.messages+' Yeni Mesaj"><i class="fas fa-envelope icon-md text-danger"></i></a>&nbsp;';

            var result = row.messages>0 ? messageme : '';
            result += row.status_allowed ? statusme : '';
            result += row.detail_allowed ? detailme : '';
            result += row.edit_allowed ? editme : '';
            result += row.delete_allowed ? deleteme : '';

            return '<div style="white-space:nowrap">'+result+'</div>';
        },
      },
      { data: 'project.title', className:"align-right", visible: false, name: 'project.title' },
      { data: 'customer_id', className:"align-right", visible: false, name: 'customer_id' }
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
        url: '{{ route("update-exploration-status") }}',
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

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
              @if($authenticated->power('supplier', 'add'))
              <a href="{{ route('add-supplier') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Tedarikçi</a>
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
            url: "{{ route('suppliers-json') }}?{!! \Request::getQueryString() !!}",
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
        },
        columns: [
        { data: 'title', name: 'title', title: 'Tedarikçi', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <span class="symbol symbol-45 symbol-light-dark ">\
                          '+( row.logo ? '<img src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/'+row.logo+'" style="object-fit:contain;" />' : '<span class="symbol-label font-size-h5">'+ data.charAt(0) + ' ' ) +'</span>\
                        </span>\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.name + ' ' + row.surname + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'phone', name: 'phone', title: 'İletişim', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + row.phone + '\
                        </div>\
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.email + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'id', name: 'id', title: 'Bekleyen', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + formatMoney(row.accepted_expenses_count) + 'TL\
                        </div>\
                        <a class="text-muted font-size-xs text-hover-primary">\
                        Ödemesi yapılmayan faturalar\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'id', name: 'id', title: 'Ödenen', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + formatMoney(row.paid_expenses_count) + 'TL\
                        </div>\
                        <a class="text-muted font-size-xs text-hover-primary">\
                        Ödemesi tamamlanan faturalar\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var editme = '<a href="{{ route("add-supplier") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("supplier-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

            detailme = '';

            var deleteme = '<a href="{{ route("delete-supplier", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
              <i class="flaticon2-trash icon-md text-danger"></i>\
              </a>';

            var result = row.edit_allowed ? detailme : '';
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

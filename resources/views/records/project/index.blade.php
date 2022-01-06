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
              @if($authenticated->power('projects', 'add'))
              <a href="{{ route('add-project') }}" class="btn btn-primary font-weight-bolder">
              {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
              Yeni Proje</a>
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
            url: "{{ route('projects-json') }}?{!! \Request::getQueryString() !!}",
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
        { width:20, data: 'id', orderable: false, title: '<label class="checkbox checkbox-success" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none; margin-right:10px;min-height:18px;" unselectable="on"><input type="checkbox" class="pick-all"/><span></span></label>', name: 'id', "render": function (data, type, row) {
        //return (row.customer_id ? '<i class="flaticon2-correct text-success font-size-h5"></i>' : '') + data;
        return '<label class="checkbox checkbox-success" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none; margin-right:10px;min-height:18px;" unselectable="on">\
                    <input type="checkbox" class="pick-line" data-chkbox-shiftsel="picked" value="'+data+'" />\
                    <span></span>\
                </label>'
        }
        },
        { data: 'customer.title', name: 'customer.title', title: 'Müşteri', "render": function (data, type, row) {
        return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <span class="symbol symbol-45 symbol-light-dark ">\
                          '+( row.customer.logo ? '<img src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/'+row.customer.logo+'" style="object-fit:contain;" />' : '<span class="symbol-label font-size-h5">'+ data.charAt(0) + ' ' ) +'</span>\
                        </span>\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-md mb-0">\
                            ' + data + '\
                        </div>\
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">\
                        ' + row.customer.personel.name + ' ' + row.customer.personel.surname + '\
                        </a>\
                        <div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'title', name: 'title', title: 'Proje', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                    </div>\
                    </div>\
                </div>'
            }
        },
        { data: 'productions_count', name: 'productions_count', searchable: false, title: 'Üretim', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-inline" >'+ data +'</span>';
            }
        },
        { data: 'assemblies_count', name: 'assemblies_count', searchable: false, title: 'Montaj', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-inline" >'+ data +'</span>';
            }
        },
        { data: 'printings_count', name: 'printings_count', searchable: false, title: 'Baskı', "render": function (data, type, row) {
                return '<span class="label label-lg font-weight-bold  label-light-danger label-inline label-light-danger label-inline" >'+ data +'</span>';
            }
        },
        { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
          if(data=='aktif'){
                return '<span class="label label-lg font-weight-bold  label-inline label-light-primary label-inline">Aktif</span>';
            }else if(data=='tamamlandı'){
                return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-inline">Tamamlandı</span>';
            }else{
                return '<span class="label label-lg font-weight-bold  label-light-dark label-inline label-inline">Başlanmadı</span>';
            }
          }
        },
        { data: 'id', className:"align-right", title: 'İşlemler', name: 'id', "render": function(data, type, row) {
            var tamamlandi = '<a href="{{ route("project-done", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light delete-cost btn-hover-info btn-sm" data-toggle="tooltip" data-theme="light" title="Tamamlandı"><i class="la la-thumbs-up icon-md text-info"></i></a>&nbsp;';
            var editme = '<a href="{{ route("add-project") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';
            var detailme = '<a href="{{ route("project-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';
            detailme = '';

            var deleteme = "";

            if (row.productions_count<1&&row.assemblies_count<1&&row.printings_count<1){
              deleteme = '<a href="{{ route("delete-project", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
              <i class="flaticon2-trash icon-md text-danger"></i>\
              </a>';
            }

            var result = row.detail_allowed&&row.status!='tamamlandı' ? tamamlandi : '';
            result += row.edit_allowed ? detailme : '';
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

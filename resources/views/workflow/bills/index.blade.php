{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
	<div class="alert-text">
        
        <form action="{{ route('bills') }}" method="get">
            <div class="row align-items-end" >
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label style="display:block;">Proje</label>
                            @component('components.forms.select', [
                                'required' => false,
                                'name' => 'project_id',
                                'value' => Request::query('project_id') ? Request::query('project_id') : '',
                                'values' => $fairs,
                                'class' => 'select2-standard'
                                ])
                            @endcomponent
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label style="display:block;">Firma</label>
                            @component('components.forms.select', [
                                'required' => false,
                                'name' => 'customer_id',
                                'value' => Request::query('customer_id') ? Request::query('customer_id') : '',
                                'values' => $firms,
                                'class' => 'select2-standard'
                                ])
                            @endcomponent
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label style="display:block;">Sorumlu</label>
                            @component('components.forms.select', [
                                'required' => false,
                                'name' => 'user_id',
                                'value' => Request::query('user_id') ? Request::query('user_id') : '',
                                'values' => $responsibles,
                                'class' => 'select2-standard'
                                ])
                            @endcomponent
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label style="display:block;">Durum</label>
                            @component('components.forms.select', [
                                'required' => false,
                                'name' => 'status',
                                'value' => Request::query('status') ? Request::query('status') : '',
                                'values' => $statuses,
                                'class' => 'select2-standard'
                                ])
                            @endcomponent
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            @include('components.forms.input', [
                                'label' => 'Başlangıç Tarihi (Deadline)',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'required' => false,
                                'name' => 'start_at',
                                'class' => 'date-format',
                                'value' => Request::query('start_at') ? date('d-m-Y', strtotime(Request::query('start_at'))) : ''
                            ])
                        </div>
                        <div class="col-sm-3">
                            @include('components.forms.input', [
                                'label' => 'Bitiş Tarihi (Deadline)',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'required' => false,
                                'name' => 'end_at',
                                'class' => 'date-format',
                                'value' => Request::query('end_at') ? date('d-m-Y', strtotime(Request::query('end_at'))) : ''
                            ])
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Filtrele</button>&nbsp;
                        <a href="{{ route('bills') }}" class="btn btn-primary">Sıfırla</a>&nbsp;
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>

<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">{{ $page_title }}
                <div class="text-muted pt-2 font-size-sm">{{ $page_description }}</div>
            </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <a href="#" data-toggle="modal" data-target="#add-stock" class="btn btn-light-danger font-weight-bolder">
            <span class="svg-icon svg-icon-md">
            </span>İçeri Aktar</a>&nbsp;
            <a href="{{ route('add-bill') }}" class="btn btn-primary font-weight-bolder">
            <span class="svg-icon svg-icon-md">
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

<div class="modal fade" id="add-stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title yeni-ekle" id="exampleModalLabel">Yeni Rapor Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('import-bill') }}" class="stock-form general-form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="filename" name="file">
                    <div class="dropzone dropzone-default dz-clickable" action="{{ route('upload-bill') }}" id="mizan-upload">
                        <div class="dropzone-msg dz-message needsclick">
                            <i class="flaticon-file-2" style="font-size:64px; color:#000;"></i>
                            <h3 class="dropzone-msg-title">Yüklemek istediğiniz dosyayı sürükleyin veya tıklayarak seçin.</h3>
                            <span class="dropzone-msg-desc">Yüklediğiniz dosya excel formatında olmalıdır.</span>
                        </div>
                    </div>
                    <hr>
                    <strong>Excel formatı aşağıdaki gibi olmalıdır:</strong>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Firma</th>
                                <th>Kısa Kod</th>
                                <th>Tarih</th>
                                <th>Fatura No</th>
                                <th>Tutar</th>
                                <th>Birim</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="form-group mt-5">
                    <button class="btn btn-light-success btn-block" type="submit">Kaydet</button>
                    </div>
                </form>
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
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
    <script>
    
    $('#mizan-upload').dropzone({
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 1,
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            maxFilesize: 350, // MB
            timeout: 1500000,
            dataType: 'json',
            addRemoveLinks: true,
            acceptedFiles: "text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx",
            accept: function(file, done) {
                done();
            },
            sending: function(){
                console.log('yükleme başladı');
                $(".my-loader").addClass('active');

            },
            error: function(){
                $(".my-loader").removeClass('active');
            },
            success: function(file, response){
                console.log('response::')
                console.log(response.file)
                $(".filename").val(response.file);

              //  $(".filename").val(response);
              //  $(".img-ekle").attr("src", "/public/images/dosya/"+response);
            }
        });

    $('.select2-standard').select2({
    placeholder: "Seçiniz",
    allowClear: true,
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
                        $(".formprogress").hide();
                    },
                    success: function(data){

                        $(".formprogress").hide();
                        if(data.status){
                            thi.closest("tr").remove();
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
    var qrform = $('#teklif-listesi').DataTable({
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('bills-json') }}?{!! \Request::getQueryString() !!}",
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
        <'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 10,
        "language": {
        "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 3, "desc" ]],
        columns: [
            { data: 'customer.title', name: 'customer.title', title: 'Müşteri/Fatura No', "render": function (data, type, row) {
                return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <span class="symbol symbol-45 symbol-light-dark ">\
                          '+( row.customer.logo ? '<img src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/'+row.customer.logo+'" style="object-fit:contain;" />' : '<span class="symbol-label font-size-h5">'+ data.charAt(0) + ' ' ) +'</span>\
                        </span>\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\
                            ' + data + '\
                        </div>\
                        <a href="{{ route("bill-detail", ["id" => "."]) }}/'+row.id+'" class="text-muted font-weight-bold text-hover-primary">' + row.bill_no + "</a>\
                        <div>\
                    </div>\
                    </div>\
                </div>"
                }
            },
            { data: 'responsible.name', name: 'responsible.name', title: 'Sorumlu', "render": function (data, type, row) {
                  if(row.responsible.avatar){
                    return '<div class="d-flex align-items-center" style="max-width:220px;">\
                      <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.responsible.name + ' ' + row.responsible.surname + '</div>\
                      </div>\
                      </div>'
                  }else{
                    return '<div class="d-flex align-items-center" style="max-width:220px;">\
                      <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.responsible.name + ' ' + row.responsible.surname + '</div>\
                      </div>\
                      </div>'
                  }
                }
            },
            { data: 'price', name: 'price', title: 'Fatura Tutarı', "render": function (data, type, row) {
                    return '<span class="label label-lg font-weight-bold  label-light-info label-inline">'+row.money_formatted +' TL</span>';
                },
            },
            { data: 'bill_date', name: 'bill_date', title: 'Fatura Tarihi', "render": function (data, type, row) {
                    
                    return '<span class="label label-lg font-weight-bold  label-light-info label-inline">'+data ?? 'Yok' +'</span>';
                },
            },
            { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
                if(row.status == 'Bekliyor'){
                    if(row.designer_status == 'Kabul Edildi'){
                        return '<span class="label label-lg font-weight-bold  label-light-success label-inline">tasarımcı kabul</span>';
                    }else if(row.designer_status == 'Reddedildi'){
                        return '<span class="label label-lg font-weight-bold  label-light-danger label-inline">tasarımcı red</span>';
                    }else{
                        return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">'+data+'</span>';
                    }
                }else{
                    if(row.status == 'Onaylandı'){
                        return '<span class="label label-lg font-weight-bold  label-light-success label-inline">'+data+'</span>';
                    }else if(row.status == 'Reddedildi'){
                        return '<span class="label label-lg font-weight-bold  label-light-danger label-inline">Reddedildi</span>';
                    }else{
                        return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">'+data+'</span>';
                    }
                }
                },
            },
            { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
                var messageme = '<a href="{{ route("bill-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" data-toggle="tooltip" data-theme="light" title="'+row.messages+' Yeni Mesaj"><i class="fas fa-envelope icon-md text-danger"></i></a>&nbsp;';

                var editme = '<a href="{{ route("update-bill", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">\
                <i class="flaticon2-writing icon-md text-primary"></i>\
                </a>&nbsp;';

                var detailme = '<a href="{{ route("bill-detail", ["id" => "."]) }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

                var deleteme = "";

                deleteme = '<a href="{{ route("delete-bill", ["id" => "."]) }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Sil">\
                <i class="flaticon2-trash icon-md text-danger"></i>\
                </a>';

                var result = row.messages>0 ? messageme : '';
                result += row.detail_allowed ? detailme : '';
                result += row.edit_allowed ? editme : '';
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

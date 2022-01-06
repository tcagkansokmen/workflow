{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="card card-custom gutter-b">
  <div class="card-body">
    <div class="d-flex">
      <!--begin: Pic-->
      <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
        @if($detail->logo)
        <div class="symbol symbol-50 symbol-lg-120">
          <img alt="{{ $detail->title }}" src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/{{ $detail->logo }}">
        </div>
        @else
        <div class="symbol symbol-50 symbol-lg-120 symbol-light-danger">
          <span class="font-size-h1 symbol-label font-weight-boldest">{{ mb_substr($detail->title, 0, 1) }}</span>
        </div>
        @endif
      </div>
      <!--end: Pic-->
      <!--begin: Info-->
      <div class="flex-grow-1">
        <!--begin: Title-->
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div class="mr-3">
            <!--begin::Name-->
            <a class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
            {{ $detail->title }}
            <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
            <!--end::Name-->
            <!--begin::Contacts-->
            <div class="d-flex flex-wrap my-2">
              @isset($detail->address)
              <a class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ Metronic::getSVG("media/svg/icons/Map/Marker2.svg", "svg-icon svg-icon-md svg-icon-gray-500 mr-1") }}
                {{ $detail->address }}</a>
              @endisset

              @isset($detail->tax_no)
              <a class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ Metronic::getSVG("media/svg/icons/Clothes/Briefcase.svg", "svg-icon svg-icon-md svg-icon-gray-500 mr-1") }}
                {{ $detail->tax_office }}/{{ $detail->tax_no }}</a>
              @endisset
            </div>
            <!--end::Contacts-->
          </div>
          <div class="my-lg-0 my-1">
            <a href="{{ route('update-customer', ['id' => $detail->id]) }}" class="btn btn-sm btn-light-primary font-weight-bolder text-uppercase mr-3">Düzenle</a>
          </div>
        </div>

        <div class="d-flex align-items-center flex-wrap justify-content-between">
          <div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
            @foreach($detail->personels as $personel)
            <div class="d-flex flex-wrap my-2">
              <a class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ Metronic::getSVG("media/svg/icons/General/User.svg", "svg-icon svg-icon-md svg-icon-gray-500 mr-1") }}
                {{ $personel->name }} {{ $personel->surname }}</a>

              <a class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ Metronic::getSVG("media/svg/icons/Devices/Phone.svg", "svg-icon svg-icon-md svg-icon-gray-500 mr-1") }}
                {{ $personel->phone }}</a>

              <a class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ Metronic::getSVG("media/svg/icons/Communication/Mail.svg", "svg-icon svg-icon-md svg-icon-gray-500 mr-1") }}
                {{ $personel->email }}</a>
            </div>
            @endforeach
          </div>
          <div class="d-flex flex-wrap align-items-center py-2">
            <div class="d-flex align-items-center mr-10">
            </div>
            <div class="flex-grow-1 flex-shrink-0 w-150px w-xl-300px mt-4 mt-sm-0">
            </div>
          </div>
        </div>
        <!--end: Content-->
      </div>
      <!--end: Info-->
    </div>
    <div class="separator separator-solid my-7"></div>
    <!--begin: Items-->
    <div class="d-flex align-items-center flex-wrap">
      <!--begin: Item-->
      <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
        <span class="mr-4">
          <i class="flaticon-coins icon-2x text-muted font-weight-bold"></i>
        </span>
        <div class="d-flex flex-column text-dark-75">
          <span class="font-weight-bolder font-size-sm">Toplam Kazanç</span>
          <span class="font-weight-bolder font-size-h5">
          {{ money_formatter($detail->bills->whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi'])->sum('price')) }} <span class="text-dark-50 font-weight-bold">TL</span></span>
        </div>
      </div>
      <!--end: Item-->
      <!--begin: Item-->
      <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
        <span class="mr-4">
          <i class="flaticon2-writing icon-2x text-muted font-weight-bold"></i>
        </span>
        <div class="d-flex flex-column text-dark-75">
          <span class="font-weight-bolder font-size-sm">Bekleyen Ödeme</span>
          <span class="font-weight-bolder font-size-h5">
          {{ money_formatter($detail->bills->whereIn('status', ['Müşteriye Gönderildi'])->sum('price')) }} <span class="text-dark-50 font-weight-bold">TL</span></span>
        </div>
      </div>
      <!--end: Item-->
      <!--begin: Item-->
      <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
        <span class="mr-4">
          <i class="flaticon-pie-chart icon-2x text-muted font-weight-bold"></i>
        </span>
        <div class="d-flex flex-column text-dark-75">
          <span class="font-weight-bolder font-size-sm">Bekleyen Çek</span>
          <span class="font-weight-bolder font-size-h5">
          {{ money_formatter($detail->cheques->where('status', 1)->sum('price')) }} <span class="text-dark-50 font-weight-bold">TL</span></span>
        </div>
      </div>
      <!--end: Item-->
      <!--begin: Item-->
      <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
        <span class="mr-4">
          <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
        </span>
        <div class="d-flex flex-column flex-lg-fill">
          <span class="text-dark-75 font-weight-bolder font-size-sm">{{ $detail->projects->count() }} Proje</span>
          <a href="{{ route('projects', ['customer_id' => $detail->id]) }}" class="text-primary font-weight-bolder">Görüntüle</a>
        </div>
      </div>
      <!--end: Item-->
      <!--begin: Item-->
      <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
        <span class="mr-4">
          <i class="flaticon-chat-1 icon-2x text-muted font-weight-bold"></i>
        </span>
        <div class="d-flex flex-column">
          <span class="text-dark-75 font-weight-bolder font-size-sm">{{ $authenticated->billApproval() }} Bekleyen Fatura</span>
          <a href="{{ route('bills', ['customer_id' => $detail->id, 'waiting' => 1]) }}" class="text-primary font-weight-bolder">Görüntüle</a>
        </div>
      </div>
      <!--end: Item-->
    </div>
    <!--begin: Items-->
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Keşifler</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable kesifler" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>

  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Üretim</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable uretimler" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Montaj</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable montajlar" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>

  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Baskı</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable baskilar" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Brief</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable briefler" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>

  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Teklif</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable teklifler" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>
</div>

<div class="row">
  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Sözleşmeler</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable sozlesmeler" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>

  <div class="col-sm-12 col-md-6">
    <!--begin::Card-->
    <div class="card card-custom card-stretch gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Faturalar</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-striped- table-hover table-checkable faturalar" id="productcategories">
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
    var costtable = $('.kesifler').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        ajax: {
            url: "{{ route('explorations-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
        },
        dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
            <'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 5,
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
      { data: 'project.title', className:"align-right", visible: false, name: 'project.title' },
      { data: 'customer_id', className:"align-right", visible: false, name: 'customer_id' }
    ],
    buttons: [
    {
        extend: 'excelHtml5',
        exportOptions: {
            columns: [ 0, 1, 2 ]
        }
    },
    ]
});
</script>
<script>
  var costtable = $('.uretimler').DataTable({
      "responsive": true,
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      ajax: {
          url: "{{ route('productions-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
      },
      dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
          <'row'<'col-sm-12'tr>>
          <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 5,
      lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
      "language": {
          "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 0, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()
          $('.new-dropdown').dropdown()
      },
      columns: [
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
        if(data=='Başlandı'){
              return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">Başladı</span>';
          }else if(data=='Talep Açıldı'){
              return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-danger label-inline">Talep Açıldı</span>';
          }else{
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-success label-inline" >Tamamlandı</span>';
          }
        }
      },
    { data: 'project.title', className:"align-right", visible: false, name: 'project.title' },
    { data: 'customer_id', className:"align-right", visible: false, name: 'customer_id' }
  ],
  buttons: [
  {
      extend: 'excelHtml5',
      exportOptions: {
          columns: [ 0, 1, 2 ]
      }
  },
  ]
});
</script>
<script>
  var costtable = $('.montajlar').DataTable({
      "responsive": true,
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      ajax: {
          url: "{{ route('assemblys-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
      },
      dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
          <'row'<'col-sm-12'tr>>
          <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 5,
      lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
      "language": {
          "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 0, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()
          $('.new-dropdown').dropdown()
      },
      columns: [
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
    { data: 'project.title', className:"align-right", visible: false, name: 'project.title' }
  ],
  buttons: [
  {
      extend: 'excelHtml5',
      exportOptions: {
          columns: [ 0, 1, 2 ]
      }
  },
  ]
  });
  </script>
  <script>
  var costtable = $('.baskilar').DataTable({
      "responsive": true,
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      ajax: {
          url: "{{ route('printings-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
      },
      dom: `<'row'<'col-sm-3 text-left'f><'col-sm-9 text-right tirepool'B>>
          <'row'<'col-sm-12'tr>>
          <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 5,
      lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
      "language": {
          "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 0, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()
          $('.new-dropdown').dropdown()
      },
      columns: [
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
          if(data=='Baskı Başladı'){
              return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-primary label-inline">Başladı</span>';
          }else if(data=='Talep Açıldı'){
              return '<span class="label label-lg font-weight-bold  label-light-success label-inline label-light-danger label-inline">Talep Açıldı</span>';
          }else if(data=='Baskı Tamamlandı'){
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-success label-inline" >Tamamlandı</span>';
          }else if(data=='Baskı Onaylandı'){
              return '<span class="label label-lg font-weight-bold  label-light-primary label-inline label-light-info label-inline" >Baskı Onaylandı</span>';
          }else if(data=='Kabul Edildi'){
              return '<span class="label label-lg font-weight-bold  label-light-info label-inline label-light-info label-inline" >Kabul Edildi</span>';
          }
        }
      },
    { data: 'project.title', className:"align-right", visible: false, name: 'project.title' }
  ],
  buttons: [
  {
      extend: 'excelHtml5',
      exportOptions: {
          columns: [ 0, 1, 2 ]
      }
  },
  ]
});
</script>
<script>
var qrform = $('.briefler').DataTable({
  responsive: true,
  "processing": true,
  "serverSide": true,
  "ajax": "{{ route('briefs-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
  dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
  <'row'<'col-sm-12'tr>>
  <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
  pageLength: 5,
  "language": {
  "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
  },
  "order": [[ 0, "desc" ]],
  columns: [
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
      { data: 'designer.name', name: 'designer.name', title: 'Tasarımcı', "render": function (data, type, row) {
            if(row.designer.avatar){
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.designer.name + ' ' + row.designer.surname + '</div>\
                </div>\
                </div>'
            }else{
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.designer.name + ' ' + row.designer.surname + '</div>\
                </div>\
                </div>'
            }
          }
      },
      { data: 'deadline', name: 'deadline', title: 'Deadline', "render": function (data, type, row) {
              return '<span class="label label-lg font-weight-bold  label-light-info label-inline">'+data+'</span>';
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
      {data: 'customer.title', name: 'customer.title', visible: false},
      {data: 'customer.code', name: 'customer.code', visible: false},
      {data: 'project.title', name: 'project.title', visible: false},
      {data: 'responsible.name', name: 'responsible.name', visible: false},
      {data: 'responsible.surname', name: 'responsible.surname', visible: false},
  ],
  buttons: [
  {
      extend: 'excelHtml5',
      exportOptions: {
          columns: [ 0, 1, 2 ]
      }
  },
  ]
});
</script>
<script>
    var qrform = $('.teklifler').DataTable({
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('offers-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
        <'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 5,
        "language": {
        "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 1, "desc" ]],
        columns: [
            { data: 'responsible', name: 'responsible.name', title: 'Sorumlu', "render": function (data, type, row) {
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
            { data: 'deadline', name: 'deadline', title: 'Deadline', "render": function (data, type, row) {
                    
                    return '<span class="label label-lg font-weight-bold  label-light-info label-inline">'+data+'</span>';
                },
            },
            { data: 'status', name: 'status', title: 'Durum', "render": function (data, type, row) {
                if(row.status.includes('onayında')){
                        return '<span class="label label-lg font-weight-bold  label-light-warning label-inline">'+data+'</span>';
                }else if(row.status.includes('onayladı')){
                        return '<span class="label label-lg font-weight-bold  label-light-success label-inline">'+data+'</span>';
                }else if(row.status.includes('red')){
                        return '<span class="label label-lg font-weight-bold  label-light-danger label-inline">'+data+'</span>';
                }else{
                    if(row.status){
                        return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">'+data+'</span>';
                    }else{
                        return '<span class="label label-lg font-weight-bold  label-light-info label-inline">Taslak</span>';
                    }
                }
                },
            },
            {data: 'customer.title', name: 'customer.title', visible: false},
            {data: 'customer.code', name: 'customer.code', visible: false},
            {data: 'project.title', name: 'project.title', visible: false},
            {data: 'responsible.name', name: 'responsible.name', visible: false},
            {data: 'responsible.surname', name: 'responsible.surname', visible: false},
        ],
      buttons: [
      {
          extend: 'excelHtml5',
          exportOptions: {
              columns: [ 0, 1, 2 ]
          }
      },
      ]
    });
</script>
<script>
var qrform = $('.sozlesmeler').DataTable({
  responsive: true,
  "processing": true,
  "serverSide": true,
  "ajax": "{{ route('contracts-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
  dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
  <'row'<'col-sm-12'tr>>
  <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
  pageLength: 5,
  "language": {
  "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
  },
  "order": [[ 0, "desc" ]],
  columns: [
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
      { data: 'deadline', name: 'deadline', title: 'Deadline', "render": function (data, type, row) {
              
              return '<span class="label label-lg font-weight-bold  label-light-info label-inline">'+data+'</span>';
          },
      },
      { data: 'contract_status', name: 'contract_status', title: 'Durum', "render": function (data, type, row) {
          if(row.contract_status.includes('onayında')){
                  return '<span class="label label-lg font-weight-bold  label-light-warning label-inline">'+data+'</span>';
          }else if(row.contract_status.includes('onayladı')){
                  return '<span class="label label-lg font-weight-bold  label-light-success label-inline">'+data+'</span>';
          }else if(row.contract_status.includes('red')){
                  return '<span class="label label-lg font-weight-bold  label-light-danger label-inline">'+data+'</span>';
          }else{
              if(row.contract_status){
                  return '<span class="label label-lg font-weight-bold  label-light-primary label-inline">'+data+'</span>';
              }else{
                  return '<span class="label label-lg font-weight-bold  label-light-info label-inline">Taslak</span>';
              }
          }
          },
      },
      {data: 'customer.title', name: 'customer.title', visible: false},
      {data: 'customer.code', name: 'customer.code', visible: false},
      {data: 'project.title', name: 'project.title', visible: false},
      {data: 'responsible.name', name: 'responsible.name', visible: false},
      {data: 'responsible.surname', name: 'responsible.surname', visible: false},
  ],
  buttons: [
    {
      extend: 'excelHtml5',
      exportOptions: {
          columns: [ 0, 1, 2 ]
      }
    },
  ]
});
</script>
<script>
    var qrform = $('.faturalar').DataTable({
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('bills-json') }}?customer_id={{$detail->id}}&{!! \Request::getQueryString() !!}",
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
        <'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 5,
        "language": {
        "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        "order": [[ 2, "desc" ]],
        columns: [
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
        ],
      buttons: [
        {
          extend: 'excelHtml5',
          exportOptions: {
              columns: [ 0, 1, 2 ]
          }
        },
      ]
    });
</script>
@endsection

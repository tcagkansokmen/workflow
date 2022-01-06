{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
table td{
  vertical-align:middle !important;
}
</style>
<!-- begin:: Content -->
<div class="container">
  <div class="card card-custom">
    <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">Zimmet Listesi</h3>
      </div>
      <div class="card-toolbar">
      </div>
    </div>
     <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="kt_table_1">
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Zimmet Adı</th>
            <th>Seri No</th>
            <th>Açıklama</th>
            <th>Teslim Tarihi</th>
            <th>İade Tarihi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">
                {{ $d->category }}
              </span>
            </td>
            <td>
                {{ $d->name }}
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">
                {{ $d->serial_no }}
              </span>
            </td>
            <td>
                {{ $d->description }}
            </td>
            <td>
              {{ date('d M Y', strtotime($d->start_at)) }}
            </td>
            <td>
              @if($d->end_at)
                {{ date('d M Y', strtotime($d->end_at)) }}
              @endif 
            </td>
          </tr>
          @endforeach
        </tfoot>
      </table>

      <!--end: Datatable -->
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
<script>
  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "order": [[ 2, "desc" ]],
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
  </script>
@endsection

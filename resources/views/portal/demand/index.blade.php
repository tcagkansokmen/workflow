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
          <h3 class="card-label">İhtiyaç Talebi Listesi</h3>
      </div>
      <div class="card-toolbar">
          <a href="{{ route('personel-ihtiyac-ekle') }}" class="btn btn-light-primary btn-icon-sm">
            Yeni Talep Ekle
          </a>
      </div>
    </div>
     <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="kt_table_1">
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Öncelik</th>
            <th>İhtiyaç Tarihi</th>
            <th>Talep Tarihi</th>
            <th>Statü</th>
            <th style="text-align:right">İşlem</th>
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
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">
                {{ $d->priority }}
              </span>
            </td>
            <td>
              {{ date('d M Y', strtotime($d->deadline)) }}
            </td>
            <td>
              {{ date('d M Y', strtotime($d->created_at)) }}
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">
                {{ $d->status }}
              </span>
            </td>
            <td style="text-align:right">
                <a href="{{ route('personel-ihtiyac-duzenle', ['id' => $d->id]) }}" class="btn btn btn-icon btn-light btn-hover-info btn-sm" title="Güncelle">
                  <i class="la la-edit text-info"></i>
                </a>
                <a href="{{ route('personel-ihtiyac-sil', ['id' => $d->id]) }}" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" title="Sil">
                  <i class="la la-trash text-danger"></i>
                </a>
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

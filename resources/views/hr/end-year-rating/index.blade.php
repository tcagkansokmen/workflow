{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
.nowrap{
  white-space:nowrap;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        @if($authenticated->power('year_rating', 'add'))
        <a href="{{ route('add-end-year-rating') }}" class="btn btn-primary font-weight-bolder">
        <span class="svg-icon svg-icon-md">
          {{ Metronic::getSVG("media/svg/icons/Navigation/Plus.svg", "svg-icon-1x") }}
        </span>Yeni Yıl Sonu Değerlendirmesi Ekle</a>
        @endif
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="teklif-listesi">
        <thead>
          <tr>
            <th>#</th>
            <th>İsim</th>
            <th>Dönem</th>
            <th>Toplam Personel</th>
            <th>İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $a)
            <tr>
              <td>
                {{ $a->id }}
              </td>
              <td>
                {{ $a->title }}
              </td>
              <td>
                {{ $a->year }}
              </td>
              <td>
                {{ $a->personels_count }}
              </td>
              <td>
                <a href="{{ route('update-end-year-rating', ['id' => $a->id]) }}" class="btn btn btn-icon btn-light btn-hover-info btn-sm" title="Güncelle">
                  <i class="la la-edit text-info"></i>
                </a>
                <a href="{{ route('user-list-end-year-rating', ['id' => $a->id]) }}" class="btn btn btn-icon btn-light btn-hover-success btn-sm" title="Personeller">
                  <i class="la la-users text-success"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
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
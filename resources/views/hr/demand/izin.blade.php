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
          <h3 class="card-label">
          İzin Talebi Listesi
          </h3>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="kt_table_1">
        <thead>
          <tr>
            <th>Personel</th>
            <th>İzin Türü</th>
            <th>Toplam Gün Sayısı</th>
            <th>Başlangıç</th>
            <th>Bitiş</th>
            <th>Talep Tarihi</th>
            <th>Statü</th>
            <th style="text-align:right">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="symbol symbol-light-dark flex-shrink-0">
                  @if($d->user->avatar)
                    <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >
                      <img src="{{ Storage::url('uploads/users') }}/{{ $d->user->avatar }}" alt="image">
                    </a>
                  @else 
                    <span class="symbol-label font-size-h5 kt-margin-r-5 t-5">
                      <span>{{ strtoupper(substr($d->user->name, 0, 2)) }}</span>
                    </span>
                  @endif
                </div>
                <div class="ml-4">
                  <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->user->name }}</a>
                  <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class="text-muted font-weight-bold text-hover-primary">{{ $d->user->title }}</a>
                </div>
              </div>
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">
                {{ $d->category->name }}
              </span>
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">
                {{ $d->days }}
              </span>
            </td>
            <td>
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ date('d M Y', strtotime($d->start_at)) }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ date('H:i', strtotime($d->start_at)) }}</a>
              </div>
            </td>
            <td>
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ date('d M Y', strtotime($d->end_at)) }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ date('H:i', strtotime($d->end_at)) }}</a>
              </div>
            </td>
            <td>
                {{ date('d M Y', strtotime($d->created_at)) }}
            </td>
            <td>
                @if($d->status == 'ödendi')
                <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
                @elseif($d->status == 'Onaylandı')
                  @if($authenticated->isAccountant())
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
                  @else 
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
                  @endif
                @elseif($d->status == 'Reddedildi')
                <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">{{ $d->status }}</span>
                @else 
                <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
                @endif
            </td>
            <td style="text-align:right">
                  @if($d->status=='Bekliyor')
                  <button class="btn btn-success btn-icon icon-sm btn-sm izin-guncelle"
                  data-id="{{ $d->id }}"
                  data-value="Onaylandı"
                  >
                    <i class="fa fa-check"></i>
                  </button>
                  <button class="btn btn-danger btn-icon btn-sm izin-guncelle"
                  data-id="{{ $d->id }}"
                  data-value="Reddedildi"
                  >
                    <i class="fa fa-times"></i>
                  </button>
                  @endif
            </td>
          </tr>
          @endforeach
        </tbody>
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
      "order": [[ 3, "desc" ]],
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
$("body").on('click', '.izin-guncelle', function(){
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-value');
      $.ajax({
        url: "{{ route('update-permision') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
    }
});
  </script>
@endsection

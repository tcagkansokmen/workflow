@extends('management.users.detail')

@section('inside')
<div class="container">
<div class="card card-custom gutter-b" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">İzin Hakları
              <div class="text-muted pt-2 font-size-sm">Onaylanmış izinlerin listesi</div>
          </h3>
      </div>
      <div class="card-toolbar">
        <span class="mr-5">Kalan Yıllık İzin</span>
        <span class="btn btn-light-danger">{{ $detail->permissionsLeft() }} Gün</span>
      </div>
  </div>
  <div class="card-body">
    <table class="table table-striped- table-hover table-checkable standard-datatable" >
        <thead>
          <tr>
            <th>İzin Türü</th>
            <th>Toplam Gün Sayısı</th>
            <th>Tarihler</th>
            <th>Statü</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr>
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
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ date('d M Y H:i', strtotime($d->start_at)) }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ date('d M Y H:i', strtotime($d->end_at)) }}</a>
              </div>
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
          </tr>
          @endforeach
        </tbody>
    </table>
  </div>
</div>
</div>
@endsection
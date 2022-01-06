@extends('management.users.detail')

@section('inside')
<div class="container">
<div class="card card-custom gutter-b" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">Avanslar
              <div class="text-muted pt-2 font-size-sm">Onaylanmış avans taleplerinin listesi</div>
          </h3>
      </div>
      <div class="card-toolbar">
        <span class="btn btn-light-primary">{{ money_formatter($total_earnest) }}TL Avans</span>&nbsp;
        <span class="btn btn-light-danger">{{ money_formatter($total_cost) }}TL Masraf</span>&nbsp;
      </div>
  </div>
  <div class="card-body">
    <table class="table table-striped- table-hover table-checkable standard-datatable" >
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Talep Edilen Tutar</th>
            <th>Tarih</th>
            <th>Statü</th>
          </tr>
        </thead>
        <tbody>
          @foreach($earnest as $d)
          <tr>
            <td>
              {{ $d->category }}
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">{{ number_format($d['price'], 2, ",", ".") }} TL</span>
              </td>
            <td>
              {{ date('d M Y', strtotime($d->created_at)) }}
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
            </td>
          </tr>
          @endforeach
        </tfoot>
    </table>
  </div>
</div>

<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">Masraf Fişleri
              <div class="text-muted pt-2 font-size-sm">Onaylanmış masraf fişlerinin listesi</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>
  <div class="card-body">
    <table class="table table-striped- table-hover table-checkable standard-datatable" >
        <thead>
          <tr>
            <th>Türü</th>
            <th>Tutar</th>
            <th>Tarihi</th>
            <th>Dosya</th>
            <th>Durum</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cost as $d)
          <tr class="tr-{{ $d->id }}">
            <td>
              {{ $d->expense->name }}
                  <i class="fa fa-pen"  data-skin="light" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $d->description }}"></i>
            </td>
            <td>
              {{ money_formatter($d->price) }}TL
            </td>
            <td>
              {{ date('d.m.Y', strtotime($d->doc_date)) }}
            </td>
            <td>
              @if($d->file)
              <a 
              data-fancybox data-caption="{{ $d->firm['title'] }} - {{ $d->project['name'] }}"
              href="{{ Storage::url('uploads/cost') }}/{{ $d->file }}" class="btn btn-bold btn-sm btn-font-sm  btn-light-warning" target="_blank">
                <i class="flaticon-file-1"></i>
              </a>
              @endif
            </td>
            <td class="status-side">
              @if($d->status == 'Onaylandı')
                @if($authenticated->isAccountant())
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
                @else 
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
                @endif
              @elseif($d->status == 'ödendi')
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
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
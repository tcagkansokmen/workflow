@extends('hr.employee.detail')

@section('inside')

<div style="width:100%;">
  <div class="card card-custom gutter-b" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">İzin Talepleri
                <div class="text-muted pt-2 font-size-sm">Personele ait izin talepleri listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
          <table class="table table-striped- table-hover table-checkable standard-datatable">
            <thead>
              <tr>
                <th>İzin Türü</th>
                <th>İzin Talebi (Gün)</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Talep Tarihi</th>
                <th>Statü</th>
                <th style="text-align:right"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($permissions as $d)
              <tr>
                <td>
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="{{ $d->description }}">
                    {{ $d->category->name }}
                  </span>
                </td>
                <td>
                  <div style="display:flex; align-items:center;">
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Kalan gün sayısı, ilgili izne ait gün düşülmeden hesaplanmaktadır.">
                      {{ round($d->days) }}
                       / 
                      @if($d->type == '1')
                      {{ round($authenticated->totalpermission()-$d->total()) }}
                      @else 
                      {{ round($d->category->max_count-$d->total()) }}
                      @endif
                      gün
                    </span>
                  </div>
                </td>
                <td>
                  <div class="kt-user-card-v2">
                    <div class="kt-user-card-v2__details">
                      <a class="kt-user-card-v2__name">{{ date('d M Y', strtotime($d->start_at)) }}</a>
                      <span class="kt-user-card-v2__email">{{ date('H:i', strtotime($d->start_at)) }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="kt-user-card-v2">
                    <div class="kt-user-card-v2__details">
                      <a class="kt-user-card-v2__name">{{ date('d M Y', strtotime($d->end_at)) }}</a>
                      <span class="kt-user-card-v2__email">{{ date('H:i', strtotime($d->end_at)) }}</span>
                    </div>
                  </div>
                </td>
                <td>
                    {{ date('d M Y', strtotime($d->created_at)) }}
                </td>
                <td>
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">
                    {{ $d->status }}
                  </span>
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
            </tfoot>
          </table>
    </div>
  </div>

  <div class="card card-custom gutter-b" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">Masraf Talepleri
                <div class="text-muted pt-2 font-size-sm">Personele ait masraf talebi listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable">
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Talep Edilen Tutar</th>
            <th>Tarih</th>
            <th>Statü</th>
            <th style="text-align:right"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($earnests as $d)
          <tr>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info"  data-skin="light" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="{{ $d->reason }}">
                {{ $d->category }}
              </span>
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{ number_format($d['price'], 2, ",", ".") }} TL</span>
              </td>
            <td>
              {{ date('d M Y', strtotime($d->created_at)) }}
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">{{ $d->status }}</span>
            </td>
            <td style="text-align:right">
              @if($d->status=='Bekliyor')
              <button class="btn btn-success btn-icon icon-sm btn-sm avans-guncelle"
              data-id="{{ $d->id }}"
              data-value="Onaylandı"
              >
                <i class="fa fa-check"></i>
              </button>
              <button class="btn btn-danger btn-icon btn-sm avans-guncelle"
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
    </div>
  </div>

  <div class="card card-custom" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">Vize Evrak Talepleri
                <div class="text-muted pt-2 font-size-sm">Personele ait vize evrak talebi listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable">
            <thead>
              <tr>
                <th>Vize Türü</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Talep Tarihi</th>
                <th>Ülke</th>
                <th>Statü</th>
                <th style="text-align:right"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($visas as $d)
              <tr>
                <td>
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info"  data-skin="light" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="{{ $d->description }}">
                    {{ $d->type }}
                  </span>
                </td>
                <td>
                  <div class="kt-user-card-v2">
                    <div class="kt-user-card-v2__details">
                      <a class="kt-user-card-v2__name">{{ date('d M Y', strtotime($d->start_at)) }}</a>
                      <span class="kt-user-card-v2__email">{{ date('H:i', strtotime($d->start_at)) }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="kt-user-card-v2">
                    <div class="kt-user-card-v2__details">
                      <a class="kt-user-card-v2__name">{{ date('d M Y', strtotime($d->end_at)) }}</a>
                      <span class="kt-user-card-v2__email">{{ date('H:i', strtotime($d->end_at)) }}</span>
                    </div>
                  </div>
                </td>
                <td>
                    {{ date('d M Y', strtotime($d->created_at)) }}
                </td>
                <td>
                  {{ $d->country }}
                </td>
                <td>
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">
                    {{ $d->status }}
                  </span>
                </td>
                <td style="text-align:right">
                      @if($d->status=='Bekliyor')
                        <button class="btn btn-success btn-icon icon-sm btn-sm vize-guncelle"
                        data-id="{{ $d->id }}"
                        data-value="teslim_edildi"
                        >
                          <i class="fa fa-check"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                </td>
              </tr>
              @endforeach
            </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
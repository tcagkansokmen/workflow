@extends('hr.employee.detail')

@section('inside')
<div style="width:100%;">
  <div class="card card-custom gutter-b" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">Personelin Eğitimleri
                <div class="text-muted pt-2 font-size-sm">Personelin tamamladığı eğitimlerin listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
      <table class="table table-stripe standard-datatable">
        <thead>
          <tr>
            <th>Eğitim</th>
            <th>Eğitim Tarihleri</th>
            <th>Katılım</th>
            <th>İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($education as $d)
          <tr>
            <td>
              <div class="kt-user-card-v2">
                <div class="kt-user-card-v2__details">
                  <a class="kt-user-card-v2__name">
                    {{ $d->education->name }}
                  </a>
                  <span class="kt-user-card-v2__email">{{ $d->education->type }}</span>
                </div>
              </div>
            </td>
            <td style="white-space:nowrap">
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">
                {{ date('d.m.Y H:i', strtotime($d->education->start_at)) }}
              </span>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">
                {{ date('d.m.Y H:i', strtotime($d->education->end_at)) }}
              </span>
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">
                {{ $d->status }}
              </span>
            </td>
            <td style="text-align:right">
              @if($d->status=='Bekliyor')
              <button class="btn btn-success btn-icon icon-sm btn-sm egitim-guncelle"
              data-id="{{ $d->id }}"
              data-value="Onaylandı"
              >
                <i class="fa fa-check"></i>
              </button>
              <button class="btn btn-danger btn-icon btn-sm egitim-guncelle"
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
            <h3 class="card-label">Personelin Sertifikaları
                <div class="text-muted pt-2 font-size-sm">Personelin sahip olduğu sertifikaların listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="#"  
            class="btn btn-light-info btn-sm btn-bold btn-upper call-bdo-modal" 
            style="margin-top:20px;"
            data-size="medium" 
            data-url="{{ route('single-certificate', ['id' => $detail->id]) }}" 
            ><i class="la la-plus"></i> Sertifika Ekle</a>
        </div>
    </div>
    <div class="card-body">
      <table class="table table-stripe standard-datatable">
        <thead>
          <tr>
            <th>Sertifika Adı</th>
            <th>Kayıt Tarihi</th>
            <th>Son Geçerlilik Tarihi</th>
            <th style="text-align:right;">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($certificates as $d)
          <tr>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">
              {{ $d->certificate->name }}
              </span>
            </td>
            <td style="white-space:nowrap">
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">
                {{ date('d M Y', strtotime($d->start_at)) }}
              </span>
            </td>
            <td>
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">
                {{ date('d M Y', strtotime($d->end_at)) }}
              </span>
            </td>
            <td style="text-align:right">
              <a class="btn btn-icon btn-light btn-hover-danger btn-sm sorgula"
              href="{{ route('delete-employee-certificate', ['id' => $d->id]) }}"
              title="sil"
              data-value="Reddedildi"
              >
                <i class="flaticon2-trash icon-md text-danger"></i>
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
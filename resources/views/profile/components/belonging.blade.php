@extends('profile.detail')

@section('inside')
<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">Zimmetler
              <div class="text-muted pt-2 font-size-sm">Personelin zimmetleri</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>
  <div class="card-body">
    <table class="table table-striped- table-hover table-checkable standard-datatable" >
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Zimmet Adı</th>
          <th>Seri No</th>
          <th>Açıklama</th>
          <th>Teslim Tarihi</th>
          <th>İade Tarihi</th>
          <th>İşlem</th>
        </tr>
      </thead>
      <tbody>
        @foreach($belongings as $d)
        <tr
        @isset($d->end_at)
        style="opacity:0.55"
        @endisset
        >
          <td>
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">
              {{ $d->category }}
            </span>
          </td>
          <td>
              {{ $d->name }}
          </td>
          <td>
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">
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
          <td>
            <a href="{{ route('belonging-detail', ['id' => $d->id]) }}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Güncelle">
              <i class="la la-edit"></i>
            </a>
          </td>
        </tr>
        @endforeach
      </tfoot>
    </table>
  </div>
</div>
@endsection
@extends('hr.employee.detail')

@section('inside')

<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
          {{ date('Y') }} {{ $quarter }}
        </a>&nbsp;&nbsp;
        <a href="#" class="btn btn-light-danger btn-sm  btn-bold">
          {{ $avg ?? '0.00' }} Ortalama
        </a>
      </div>
  </div>
  <div class="card-body">
    <table class="table table-stripe standard-datatable">
      <thead>
        <tr>
          <th>Proje Adı</th>
          <th>Şirket Adı</th>
          <th>Manager</th>
          <th>Puan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ratings as $d)
          <tr>
            <td style="85px;">
            <div class="kt-user-card-v2">
              <div class="kt-user-card-v2__details">
                <a class="kt-user-card-v2__name" href="{{ route('proje-detay', ['id' => $d->project->id]) }}">{{ $d->project->name }}</a>
                <span class="kt-user-card-v2__email">{{ date("d.m.Y", strtotime($d->project->created_at)) }}</span>
              </div>
            </div></td>
            <td>
            <div class="kt-user-card-v2">

              @if($d->project->firm->logo)
                <a href="{{ route('firm-detail', ['id' => $d->project->firm->id]) }}" class=" kt-media kt-margin-r-5 t-5" >
                    <img src="{{ Storage::url('uploads/company') }}/{{ $d->project->firm->logo }}" alt="image">
                </a>
              @else
                <span class="kt-media kt-media--md kt-media--danger kt-margin-r-5 t-5">
                    <span>{{ strtoupper(substr($d->project->firm->title, 0, 2)) }}</span>
                </span>
              @endif
              <div class="kt-user-card-v2__details">
                <a class="kt-user-card-v2__name" href="{{ route('firm-detail', ['id' => $d->project->firm->id]) }}">{{ $d->project->firm->title }}</a>
                <span class="kt-user-card-v2__email">{{ $d->project->firm->holding->title ?? '' }}</span>
              </div>
            </div>
            </td>
            <td>
            {{ $d->project->manager->name }} {{ $d->project->manager->surname }} 
            </td>
            <td>
              <span class="btn btn-label-brand btn-sm btn-bold btn-upper">{{ $d->puan }}</span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
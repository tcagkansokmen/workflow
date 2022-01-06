{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
.select2{
  width:100% !important;
}
table td{
  vertical-align:middle !important;
}
</style>
<style>
.file_side {
position: relative;
overflow: hidden;
display: inline-block;
cursor:pointer;
}

.file_side input[type=file] {
font-size: 100px;
position: absolute;
left: 0;
top: 0;
opacity: 0;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap pt-6 pb-0">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>

  <form class="form general-form form--label-right" method="POST" action="{{ route('save-end-year-rating') }}" >
  @csrf
    <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
    <div class="card-body">
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Değerlendirme Adı</label>
        <div class="col-lg-6">
          <input type="text" name="title" class="form-control" value="{{ $detail->title ?? '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Değerlendirme Dönemi (Yıl)</label>
        <div class="col-lg-6">
            <input type="number" name="year" class="form-control" value="{{ $detail->year ?? '' }}">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Kısa Açıklama</label>
        <div class="col-lg-6">
          <input type="text" name="description" class="form-control" value="{{ $detail->description ?? '' }}">
        </div>
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-9 col-xl-9 offset-lg-3">
            <div class="d-flex">
            <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </form>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
@endsection

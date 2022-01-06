@extends('hr.employee.detail')

@section('inside')
<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">Yeni Zimmet
              <div class="text-muted pt-2 font-size-sm">Personelin zimmetleri</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>
  <div class="card-body">
    <form class="kt-form general-form kt-form--label-right" method="POST" action="{{ route('update-belonging') }}" >
      @csrf
      <input type="hidden" name="personel_id" value="{{ $detail->id ?? '' }}">
      <input type="hidden" name="id" value="{{ $belonging->id ?? '' }}">
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Kategori</label>
        <div class="col-lg-6">
          <select name="category" id="" class="select2-standard form-control">
            <option value="">Seçiniz</option>
            <option 
            @isset($belonging->category)
              @if($belonging->category == 'Bilgisayar')
                selected
              @endif
            @endisset
            value="Bilgisayar">Bilgisayar</option>
            <option
            @isset($belonging->category)
              @if($belonging->category == 'Araba')
                selected
              @endif
            @endisset
            value="Araba">Araba</option>
            <option
            @isset($belonging->category)
              @if($belonging->category == 'Telefon')
                selected
              @endif
            @endisset
            value="Telefon">Telefon</option>
            <option
            @isset($belonging->category)
              @if($belonging->category == 'Diğer')
                selected
              @endif
            @endisset
            value="Diğer">Diğer</option>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Ürün Adı</label>
        <div class="col-lg-6">
          <input type="text" name="name" class="form-control" value="{{ $belonging->name ?? '' }}">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Seri Numarası</label>
        <div class="col-lg-6">
          <input type="text" name="serial_no" class="form-control" value="{{ $belonging->serial_no ?? '' }}">
        </div>
      </div>


      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Teslim Tarihi</label>
        <div class="col-lg-6">
          <input class="form-control pick-date" type="text" required name="start_at" value="{{ isset($belonging->start_at) ? date('d-m-Y', strtotime($belonging->start_at)) : '' }}" placeholder="Teslim Tarihi">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">İade Tarihi</label>
        <div class="col-lg-6">
          <input class="form-control pick-date" type="text" name="end_at" value="{{ isset($belonging->end_at) ? date('d-m-Y', strtotime($belonging->end_at)) : '' }}" placeholder="İade Tarihi">
        </div>
      </div>


        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Açıklama</label>
          <div class="col-lg-6">
            <textarea name="description" id="" cols="30" rows="10" class="form-control">{{ $belonging->description ?? '' }}</textarea>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-xl-3">
          </div>
          <div class="col-lg-9 col-xl-9">
              <button type="submit" class="btn btn-success" style="margin-left:15px;">Kaydet</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection
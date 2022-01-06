@extends('hr.employee.detail')

@section('inside')

<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-6">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
  </div>
  <div class="card-body">
    <form class="kt-form general-form kt-form--label-right" method="POST" action="{{ route('save-personel-account', ['id' => $detail->id]) }}">
      @csrf
       <div class="card-body">
        <div class="kt-section kt-section--first">
          <div class="kt-section__body">
            <div class="row">
              <label class="col-xl-3"></label>
              <div class="col-lg-9 col-xl-6">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">Kullanıcı Adı</label>
              <div class="col-lg-9 col-xl-6">
                <input class="form-control" name="username" type="text" value="{{ $detail->username ?? \Str::slug($detail->name." ".$detail->surname, '.') }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
              <div class="col-lg-9 col-xl-6">
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                  <input type="email" name="email" class="form-control" value="{{ $detail->email ? $detail->email : \Str::slug($detail->name." ".$detail->surname, '.').'@bdo.com.tr' }}" placeholder="Email" aria-describedby="basic-addon1">
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">Departman</label>
              <div class="col-lg-9 col-xl-6">
                <select name="department_id" id="" class="select2-standard form-control disabled" disabled>
                  <option value="">Seçiniz</option>
                  @foreach($departments as $d)
                    <option value="{{ $d->id }}"
                    @if($detail->department_id == $d->id)
                      selected
                    @endif
                    >{{ $d->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">Pozisyon</label>
              <div class="col-lg-9 col-xl-6">
                <select name="title" id="" class="select2-standard form-control disabled" disabled>
                  <option value="">Seçiniz</option>
                  @foreach($titles as $d)
                    <option value="{{ $d->id }}"
                    @if($detail->title == $d->title)
                      selected
                    @endif
                    >{{ $d->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
        </div>
        <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>
        <div class="kt-section kt-section--first">
          <div class="kt-section__body">
            <div class="row">
              <label class="col-xl-3"></label>
              <div class="col-lg-9 col-xl-6">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label">Yeni Giriş Parolası</label>
              <div class="col-lg-9 col-xl-6">
                <input class="form-control" type="text" name="password">
              </div>
            </div>
            <div class="form-group row kt-margin-t-10 kt-margin-b-10">
              <label class="col-xl-3 col-lg-3 col-form-label"></label>
              <div class="col-lg-9 col-xl-6">
                @if($detail->is_active)
                <button type="button" class="btn btn-light-danger btn-bold btn-sm kt-margin-t-5 kt-margin-b-5">Hesabı Dondur</button>
                @else 
                <button type="button" class="btn btn-light-success btn-bold btn-sm kt-margin-t-5 kt-margin-b-5">Hesabı Aktif Et</button>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__foot">
        <div class="kt-form__actions">
          <div class="row">
            <div class="col-lg-3 col-xl-3">
            </div>
            <div class="col-lg-9 col-xl-9">
              <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
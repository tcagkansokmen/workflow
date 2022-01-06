@extends('hr.employee.detail')

@section('inside')
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

<div class="card card-custom" style="width:100%;">
  <div class="card-header flex-wrap pt-6 pb-0">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        <ul class="nav nav-bold nav-pills">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#personal_info">Personel Bilgileri</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#agi_info">AGİ Bilgileri</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#belgeler">Belge & Dokümanlar</a>
          </li>
        </ul>
      </div>
  </div>
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="personal_info" role="tabpanel">
        <form class="form general-form form--label-right" method="POST" action="{{ route('save-employee-information', ['id' => $detail->id]) }}">
          @csrf
          <div class="portlet__body">
            <div class="section section--first">
              <div class="section__body">
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Fotoğraf</label>
                  <div class="col-lg-9 col-xl-6">
                  <div class="image-input image-input-outline" id="kt_image_4" style="margin:10px 0px 30px;">
                    @isset($detail->avatar)
                      <div class="image-input-wrapper" style="background-image: url({{ Storage::url('uploads/users') }}/{{ $detail->avatar ?? 'avatar.png' }}); background-size:contain; background-position:50% 50%;"></div>
                    @else 
                      <div class="image-input-wrapper"></div>
                    @endisset
                      <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Fotoğraf">
                        <i class="fa fa-pen icon-sm text-muted"></i>
                        <input type="file" name="kt_user_add_user_avatar" accept=".png, .jpg, .jpeg"/>
                        <input type="hidden" name="profile_avatar_remove"/>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">İsim</label>
                  <div class="col-lg-6 col-xl-6">
                    <input class="form-control" type="text" name="name" value="{{ $detail->name ?? '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Soyisim</label>
                  <div class="col-lg-6 col-xl-6">
                    <input class="form-control" type="text" name="surname" value="{{ $detail->surname ?? '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">TC Kimlik</label>
                  <div class="col-lg-6 col-xl-6">
                    <input class="form-control" type="number" maxlength="11" name="tc_no" value="{{ $detail->tc_no ?? '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Doğum Tarihi / Yeri</label>
                  <div class="col-lg-3 col-xl-3">
                    <input class="form-control pick-date date-format" type="text" name="birthdate" value="{{ isset($detail->birthdate) ? date('d-m-Y', strtotime($detail->birthdate)) : '' }}">
                  </div>
                  <div class="col-sm-3">
                    <input class="form-control" type="text" name="birth_place" value="{{ $detail->birth_place ?? '' }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Medeni Durum</label>
                  <div class="col-lg-6 col-xl-6">
                    <select name="marital_status" id="" class="select2">
                      <option value="">Seçiniz</option>
                      <option value="Evli"
                      @isset($detail->marital_status)
                      @if($detail->marital_status == "Evli")
                          selected
                        @endif
                      @endisset
                      >Evli</option>
                      <option value="Bekar"
                      @isset($detail->marital_status)
                      @if($detail->marital_status == "Bekar")
                          selected
                        @endif
                      @endisset
                      >Bekar</option>
                      <option value="Belirtilmemiş"
                      @isset($detail->marital_status)
                      @if($detail->marital_status == "Belirtilmemiş")
                          checked
                        @endif
                      @endisset
                      >Belirtilmemiş</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Cinsiyet</label>
                  <div class="col-lg-6 col-xl-6">
                    <div class="radio-inline">
                      <label class="radio radio--success">
                        <input type="radio" name="gender" value="Erkek"
                            @isset($detail->gender)
                            @if($detail->gender == "Erkek")
                              checked
                              @endif
                            @endisset
                          >
                        <span></span> Erkek
                      </label>
                      <label class="radio radio--success">
                        <input type="radio" name="gender" value="Kadın"
                          @isset($detail->gender)
                          @if($detail->gender == "Kadın")
                            checked
                            @endif
                          @endisset
                        >
                        <span></span> Kadın
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">Engelli Durumu</label>
                  <div class="col-lg-6 col-xl-6">
                    <div class="checkbox-inline mt-0">
                      <label class="checkbox checkbox--success">
                        <input type="checkbox" name="is_disabled" value="1"
                            @isset($detail->is_disabled)
                              @if($detail->is_disabled == "1")
                                checked
                              @endif
                            @endisset
                          >
                        <span></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-xl-3"></label>
                  <div class="col-lg-6 col-xl-6">
                    <h3 class="section__title section__title-sm">İletişim Bilgileri:</h3>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-3 col-lg-3 col-form-label">İletişim Telefon</label>
                  <div class="col-lg-6 col-xl-6">
                      <input type="text" name="phone" class="form-control phone" value="{{ $detail->phone ?? '' }}" >
                  </div>
                </div>
                <div class="form-group form-group-last row">
                  <label class="col-xl-3 col-lg-3 col-form-label">İletişim Adresi</label>
                  <div class="col-lg-6 col-xl-6">
                      <input type="text" name="address" class="form-control" value="{{ $detail->address ?? '' }}">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="portlet__foot">
            <div class="form__actions">
              <div class="row">
                <div class="col-lg-3 col-xl-3">
                </div>
                <div class="col-lg-6 col-xl-9">
                  <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="tab-pane fade" id="agi_info" role="tabpanel">
        <form class="form general-form form--label-right" method="POST" action="{{ route('personel-family', ['id' => $detail->id]) }}">
          @if($detail->marital_status)
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Eşi Çalışıyor mu?</label>
            <div class="col-lg-6 col-xl-6">
              <div class="checkbox-inline mt-0">
                <label class="checkbox checkbox--success">
                  <input type="checkbox" name="marital_job" value="1"
                      @isset($detail->marital_job)
                        @if($detail->marital_job == "1")
                          checked
                        @endif
                      @endisset
                    >
                  <span></span>
                </label>
              </div>
            </div>
          </div>
          @endif
          @csrf
          <div class="repeater" style="margin-bottom:55px;">
            <div class="form-group form-group-last row ">
            <label class="col-xl-2 col-lg-2 col-form-label">Çocuklar</label>
              <div data-repeater-list="family" class="col-lg-9">
                
                @if(count($personel_family))
                @foreach($personel_family as $p)
                <div data-repeater-item class="form-group row align-items-center">
                  <div class="col-md-3">
                  <input type="hidden" name="id" value="{{ $p->id }}">
                    <div class="form__group--inline">
                      <div class="form__control">
                        <input type="text" class="form-control" name="name" value="{{ $p->name ?? '' }}" placeholder="Çocuğun ismi">
                      </div>
                    </div>
                    <div class="d-md-none margin-b-10"></div>
                  </div>
                  <div class="col-md-3">
                  <input type="hidden" name="id" value="{{ $p->id }}">
                    <div class="form__group--inline">
                      <div class="form__control">
                        <input type="text" class="form-control date-format" value="{{ isset($p->birthdate) ? date('d-m-Y', strtotime($p->birthdate)) : '' }}" name="birthdate" placeholder="Doğum Tarihi">
                      </div>
                    </div>
                    <div class="d-md-none margin-b-10"></div>
                  </div>
                  <div class="col-md-3">
                      <div class="checkbox-inline mt-0">
                        <label class="checkbox checkbox--success">
                          <input type="checkbox" name="is_education" value="1"
                              @isset($p->is_education)
                                @if($detail->is_education == "1")
                                  checked
                                @endif
                              @endisset
                            > 
                          <span></span> Okuyor
                        </label>
                      </div>
                  </div>
                  <div class="col-md-2">
                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-light-danger btn-bold btn-icon">
                      <i class="la la-trash-o"></i>
                      
                    </a>
                  </div>
                </div>
                @endforeach
                @else
                <div data-repeater-item class="form-group row align-items-center">
                <div class="col-md-3">
                  <div class="form__group--inline">
                    <div class="form__control">
                      <input type="text" class="form-control" name="name"  placeholder="Çocuğun ismi">
                    </div>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                  <div class="col-md-3">
                    <div class="form__group--inline">
                      <div class="form__control">
                        <input type="text" class="form-control date-format" name="birthdate" placeholder="Tarih">
                      </div>
                    </div>
                    <div class="d-md-none margin-b-10"></div>
                  </div>
                  <div class="col-md-3">
                      <div class="checkbox-inline mt-0">
                        <label class="checkbox checkbox--success">
                          <input type="checkbox" name="is_education" value="1">
                          <span></span> Okuyor
                        </label>
                      </div>
                  </div>
                  <div class="col-md-2">
                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-light-danger btn-bold btn-icon">
                      <i class="la la-trash-o"></i>
                      
                    </a>
                  </div>
                </div>
                @endisset
              </div>
            </div>
            <div class="form-group form-group-last row">
            <label class="col-xl-2 col-lg-2 col-form-label"></label>
              <div class="col-lg-4">
                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-light-info">
                  <i class="la la-plus"></i> Yeni Ekle
                </a>
              </div>
            </div>
          </div>
          <div class="portlet__foot">
            <div class="form__actions">
              <div class="row">
                <div class="col-lg-6 col-xl-9 offset-lg-2">
                  <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="tab-pane fade" id="belgeler" role="tabpanel">
        <form class="form general-form form--label-right" method="POST" action="{{ route('personel-files', ['id' => $detail->id]) }}">
          @csrf
        <div class="repeater" style="margin-bottom:55px;">
          <div class="form-group form-group-last row ">
            <div data-repeater-list="file" class="col-lg-9">
              
              @if(count($user_documents))
              @foreach($user_documents as $p)
              <div data-repeater-item class="form-group row align-items-center">
                <div class="col-md-3">
                <input type="hidden" name="id" value="{{ $p->id }}">
                  <div class="form__group--inline">
                    <div class="form__control">
                      <select name="title" id="" class="custom-select">
                        <option value="">Seçiniz</option>
                        <option value="Resmi Evrak" {{ $p->title == 'Resmi Evrak' ? 'selected' : '' }}>Resmi Evrak</option>
                        <option value="Eğitim Belgesi" {{ $p->title == 'Eğitim Belgesi' ? 'selected' : '' }}>Eğitim Belgesi</option>
                        <option value="İkamet Belgesi" {{ $p->title == 'İkamet Belgesi' ? 'selected' : '' }}>İkamet Belgesi</option>
                      </select>
                    </div>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-3">
                <input type="hidden" name="id" value="{{ $p->id }}">
                  <div class="form__group--inline">
                    <div class="form__control">
                      <input type="text" class="form-control" name="description" value="{{ $p->description ?? '' }}" placeholder="Dosya Adı">
                    </div>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-4">
                  <div class="file_side">
                        <a target="_blank" href="{{ Storage::url('uploads/users') }}/{{ $p->file }}" class="custom-file-upload btn btn-bold btn-md btn-light-info" style="margin-bottom:0;">
                        <i class="la la-search"></i> Görüntüle
                        </a>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-2">
                  <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-light-danger btn-bold btn-icon">
                    <i class="la la-trash-o"></i>
                    
                  </a>
                </div>
              </div>
              @endforeach
              @endisset
              <div data-repeater-item class="form-group row align-items-center">
                <div class="col-md-3">
                  <div class="form__group--inline">
                    <div class="form__control">
                      <select name="title" id="" class="custom-select">
                        <option value="">Seçiniz</option>
                        <option value="Resmi Evrak">Resmi Evrak</option>
                        <option value="Eğitim Belgesi">Eğitim Belgesi</option>
                        <option value="İkamet Belgesi">İkamet Belgesi</option>
                      </select>
                    </div>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-3">
                  <div class="form__group--inline">
                    <div class="form__control">
                      <input type="text" class="form-control" name="description" placeholder="Dosya Adı">
                    </div>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-4">
                  <div class="file_side">
                  <input type="hidden" class="file_input" name="file_input" >
                          <label for="file-upload" class="custom-file-upload btn btn-bold btn-md btn-light-info" style="margin-bottom:0;">
                          <i class="la la-plus"></i> Dosya Ekle
                      </label>
                      <input id="file-upload" type="file" accept=".pdf"/>
                  </div>
                  <div class="d-md-none margin-b-10"></div>
                </div>
                <div class="col-md-2">
                  <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-light-danger btn-bold btn-icon">
                    <i class="la la-trash-o"></i>
                    
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group form-group-last row">
            <div class="col-lg-4">
              <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-light-info">
                <i class="la la-plus"></i> Yeni Ekle
              </a>
            </div>
          </div>
        </div>
          <div class="portlet__foot">
            <div class="form__actions">
              <div class="row">
                <div class="col-lg-6 col-xl-9">
                  <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="card-footer">
  
  </div>
</div>

@endsection
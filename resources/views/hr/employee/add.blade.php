{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
table td{
  vertical-align:middle !important;
}
</style>

<div class="card card-custom card-stretch">
  <div class="card-header py-3">
    <div class="card-title align-items-start flex-column">
      <h3 class="card-label font-weight-bolder text-dark">{{ $page_title }}</h3>
      <span class="text-muted font-weight-bold font-size-sm mt-1">{{ $page_description }}</span>
    </div>
  </div>
  <div class="card-body">
    <form class="general-form" method="POST" action="{{ route('save-employee') }}" >
      @CSRF
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
        <label class="col-xl-3 col-lg-3 col-form-label">Unvan</label>
        <div class="col-lg-6 col-xl-6">
          <select name="title" id="" class="select2-standard form-control">
            <option value="">Seçiniz</option>
            @foreach($title as $t)
              <option value="{{ $t->id }}">{{ $t->title }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Departman</label>
        <div class="col-lg-6 col-xl-6">
          <select name="department_id" id="" class="select2-standard form-control">
            <option value="">Seçiniz</option>
            @foreach($departments as $t)
              <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">* Çalışma Şekli</label>
        <div class="col-lg-3">
          <select name="type" id="" class="select2-standard form-control">
            <option value="">Seçiniz</option>
            <option value="Tam Zamanlı"
              @isset($detail->type)
                @if($detail->type == "Tam Zamanlı")
                  selected
                @endif
              @endisset
              >Tam Zamanlı</option>
            <option value="Proje Bazlı"
              @isset($detail->type)
                @if($detail->type == "Proje Bazlı")
                  selected
                @endif
              @endisset
              >Proje Bazlı</option>
            <option value="Part-time"
              @isset($detail->type)
                @if($detail->type == "Part-time")
                  selected
                @endif
              @endisset
              >Part-time</option>
          </select>
        </div>
      </div>
      <div class="form-group row" style="margin-top:55px;">
        <label class="col-xl-3 col-lg-3 col-form-label">İşe Başlama Tarihi</label>
        <div class="col-lg-9 col-xl-6">
          <input type="text" class="pick-date form-control"  name="check_in_date">
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
          <select name="marital_status" id="" class="select2-standard">
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
          <div class="kt-radio-inline">
            <label class="kt-radio kt-radio--success">
              <input type="radio" name="gender" value="Erkek"
                  @isset($detail->gender)
                  @if($detail->gender == "Erkek")
                    checked
                    @endif
                  @endisset
                > Erkek
              <span></span>
            </label>
            <label class="kt-radio kt-radio--success">
              <input type="radio" name="gender" value="Kadın"
                @isset($detail->gender)
                @if($detail->gender == "Kadın")
                  checked
                  @endif
                @endisset
              > Kadın
              <span></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Engelli Durumu</label>
        <div class="col-lg-6 col-xl-6">
          <div class="kt-checkbox-inline mt-0">
            <label class="kt-checkbox kt-checkbox--success">
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
          <h3 class="kt-section__title kt-section__title-sm">İletişim Bilgileri:</h3>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">İletişim Telefon</label>
        <div class="col-lg-6 col-xl-6">
            <input type="text" name="phone" class="form-control phone" value="{{ $detail->phone ?? '' }}" >
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Personel E-posta</label>
        <div class="col-lg-6 col-xl-6">
            <input type="email" name="email" class="form-control" value="{{ $detail->phone ?? '' }}" >
            <span>Henüz mail adresi tanımlanmamış kullanıcılar için, geçici olarak hususi mail adresleri kullanılabilir.</span>
        </div>
      </div>
      <div class="form-group form-group-last row">
        <label class="col-xl-3 col-lg-3 col-form-label">İletişim Adresi</label>
        <div class="col-lg-6 col-xl-6">
            <input type="text" name="address" class="form-control" value="{{ $detail->address ?? '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Mobil Operatör (E-imza için)</label>
        <div class="col-lg-6 col-xl-6">
          <select name="marital_status" id="" class="select2-standard form-control">
            <option value="">Seçiniz</option>
            <option value="Turkcell"
            @isset($detail->mobil_operator)
            @if($detail->mobil_operator == "Turkcell")
                selected
              @endif
            @endisset
            >Turkcell</option>
            <option value="Vodafone"
            @isset($detail->mobil_operator)
            @if($detail->mobil_operator == "Vodafone")
                selected
              @endif
            @endisset
            >Vodafone</option>
            <option value="Türk Telekom"
            @isset($detail->mobil_operator)
            @if($detail->mobil_operator == "Türk Telekom")
                selected
              @endif
            @endisset
            >Türk Telekom</option>
          </select>
        </div>
      </div>

      <div class="card-footer">
          <button type="submit" class="btn btn-primary mr-2">Kaydet</button>
      </div>
    </form>
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
  $('[name=kt_user_add_user_avatar]').change(function () {
    var file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
      $('.image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
    }
  });

  $('.pick-date').datepicker({
      todayHighlight: true,
      autoclose: true,
      format: 'dd-mm-yyyy'
  });
  $(".date-format").inputmask("99-99-9999");

  $("body").on('change', '.change-payment', function(){
    var id = $(this).attr('data-user');
    var month = $(this).attr('data-month');
    var year = $(this).attr('data-year');
    var val = $(this).val();
      $.ajax({
        url: "/insan-kaynaklari/personeller/maas-update",
        data: "id=" + id + "&month=" + month + "&year="+year + "&val="+val,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          if(data.status){
							toastr.success(data.message);
          }else{
            swal.fire({
              "title": "",
              "text": data.message,
              "type": "warning",
              "confirmButtonClass": "btn btn-secondary"
            });
          }
        }
      });
  });
});

</script>

@endsection


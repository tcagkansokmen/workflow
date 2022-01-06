<style>
.bootstrap-timepicker-widget,
.select2-container,
.swal2-container{
  z-index:99999 !important;
}
.select2{
  width:100%;
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
<form class="kt-form new-form-2" method="POST" action="{{ route('save-define-as-personel') }}" >
@csrf
<input type="hidden" name="candidate_id" value="{{ $candidate->id ?? '' }}">
   <div class="card-body">
    <div class="kt-section kt-section--first">
      <div class="kt-section__body">
        <h5>{{ $candidate->name }} {{ $candidate->surname }}</h5>
        
        <div class="form-group row" style="margin-top:55px;">
          <label class="col-xl-3 col-lg-3 col-form-label">İşe Başlama Tarihi</label>
          <div class="col-lg-9 col-xl-6">
            <input type="text" class="pick-date form-control"  name="check_in_date">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">Departman</label>
          <div class="col-lg-9 col-xl-6">
            <select name="department_id" id="" class="select2">
              <option value="">Seçiniz</option>
              @foreach($departments as $d)
                <option value="{{ $d->id }}"
                @if($demand->department_id == $d->id)
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
            <select name="position_id" id="" class="select2">
              <option value="">Seçiniz</option>
              @foreach($titles as $d)
                <option value="{{ $d->id }}"
                @if($demand->position_id == $d->id)
                  selected
                @endif
                >{{ $d->title }}</option>
              @endforeach
            </select>
          </div>
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
        <div class="col-lg-6 col-xl-6" style="display:flex; justify-content:space-between">
          <button type="submit" onclick="yeni_fonksiyon()" class="btn btn-success" >Personel Kaydını Onayla</button>
        </div>
      </div>
    </div>
  </div>
</form>
<script>
$(".select2").select2();
</script>
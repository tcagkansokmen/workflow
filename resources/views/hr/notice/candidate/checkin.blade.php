<form class="kt-form new-form" method="POST" action="{{ route('aday-kaydi-tamamla') }}" >
@csrf
<input type="hidden" name="candidate_id" value="{{ $detail->id ?? '' }}">
   <div class="card-body">
    <div class="kt-section kt-section--first">
      <div class="kt-section__body">
        <h5>{{ $detail->name }} {{ $detail->surname }}</h5>
        <p>Kalan süreçte, personele ait evraklar sisteme tanımlanıp IT tarafından personelin kullanıcı tanımlamaları gerçekleştirilip sisteme entegre edilecektir</p>

        <div class="form-group row" style="margin-top:20px;">
          <label class="col-xl-3 col-lg-3 col-form-label">* İşe başlama tarihi</label>
          <div class="col-lg-6">
              <input class="form-control pick-date" type="text" required name="check_in_date" value="{{ isset($detail->check_in_date) ? date('d-m-Y', strtotime($detail->check_in_date)) : '' }}" placeholder="Başlangıç">
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="kt-portlet__foot">
    <div class="kt-form__actions">
      <div class="row">
        <div class="col-lg-9 col-xl-9">
        </div>
        <div class="col-lg-3 col-xl-3" style="display:flex; justify-content:space-between">
            <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet & Duyur</button>
        </div>
      </div>
    </div>
  </div>
</form>

		<script src="/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $('.pick-date').datepicker({
      todayHighlight: true,
      autoclose: true,
      format: 'dd-mm-yyyy'
  });
});
</script>

<style>
.bootstrap-timepicker-widget,
.select2-container,
.swal2-container{
  z-index:99999 !important;
}
</style>
<form class="kt-form new-form" method="POST" action="{{ route('certificate-update') }}" >
@csrf
<input type="hidden" name="id" value="{{ $user_id }}">
   <div class="card-body">
    <div class="kt-section kt-section--first">
      <div class="kt-section__body">
        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Sertifika</label>
          <div class="col-lg-5">
            @component('components.forms.select', [
                'required' => true,
                'name' => 'certificate_id',
                'value' => $detail->certificate_id ?? '',
                'values' => $certificates ?? array(),
                'class' => 'select2-standard'
            ])
            @endcomponent
          </div>
        </div>
                    
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Kayıt Tarihi</label>
          <div class="col-lg-6">
              <input class="form-control pick-date" type="text" required name="start_at">
          </div>
        </div>
                    
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Son Geçerlilik Tarihi</label>
          <div class="col-lg-6">
              <input class="form-control pick-date" type="text" required name="end_at">
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
        <div class="col-lg-9 col-xl-9" style="display:flex; justify-content:space-between">
          <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet</button>&nbsp;

        </div>
      </div>
    </div>
  </div>
</form>

		<script src="/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js" type="text/javascript"></script>
<script>
function ok(){
  $('.new-form').ajaxSubmit({ 
      beforeSubmit:  function(){
          $(".formprogress").show();
      },
      error: function(){
        swal.fire({
          "title": "",
          "text": "Kaydedilemedi",
          "type": "warning",
          "confirmButtonClass": "btn btn-secondary"
        });

      },
      dataType:  'json', 
      success:   function(item){
          if(item.status){
							window.location.href = item.redirect;
          }else{
              swal.fire({
                  "title": "Dikkat",
                  "type": "warning",
                  "html": item.message,
                  "confirmButtonClass": "btn btn-secondary"
              });
          }
      }
  }); 
}
$(document).ready(function(){
  $(".select2-standard").select2();

var datesToDisable = $('.pick-date').data("datesDisabled");
if(datesToDisable){
    datesToDisable = datesToDisable.split(',');
}else{
    datesToDisable = false;
}

$(".pick-date").inputmask("99-99-9999");
$('.pick-date').datepicker({
    orientation: "bottom right",
    allowInputToggle: true,
    todayHighlight: true,
    autoclose: true,
    format: 'dd-mm-yyyy',
    language: 'tr'
}).on("show", function(event) {
        if(datesToDisable){
            $(".day").each(function(index, element) {
                var el = $(element);
                var dat = $(this).attr('data-date');
                var date = new Date(parseInt(dat));
                var month = (date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1);
                var year = date.getFullYear();
                
                var hideMonth = $.grep( datesToDisable, function( n, i ) {
                    if(n.substr(3, 4) == year && n.substr(0, 2) == month){
                        el.addClass('disabled');
                    }
                });
            });
        }
    });
  
});
</script>

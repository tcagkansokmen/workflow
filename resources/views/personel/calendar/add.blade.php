<style>
.bootstrap-timepicker-widget,
.select2-container,
.swal2-container{
  z-index:99999 !important;
}
</style>
<form class="new-form" method="POST" action="{{ route('save-calendar') }}" >
@csrf
<input type="hidden" name="id" value="{{ $calendar->id ?? '' }}">
<input type="hidden" name="is_past" value="{{ $is_past ?? '' }}">
  <div class="portlet__body">
    <div class="section section--first">
      <div class="section__body">
        @isset($calendar->is_allowed)
          @if($calendar->is_allowed == 2)
          <div class="alert alert-outline-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            </button>
              <strong>Dikkat!</strong> Bu aktivite yönetici tarafından Reddedildi.
          </div>
          @endif
        @endisset

        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Gerçekleşen Etkinlik mi?</label>
          <div class="col-lg-4">
            <div class="checkbox-inline">
              <label class="checkbox checkbox--bold checkbox--success mt-5">
                <input type="checkbox" name="is_done" value="1"
                @if(isset($calendar['real_start_at']))
                  @if($calendar['real_start_at'])
                    checked
                  @endif
                @endif>
                <span></span> Evet
              </label>
            </div>
          </div>
        </div>

        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Firma</label>
          <div class="col-lg-5">
            @component('components.forms.select', [
                'required' => false,
                'name' => 'firm_id',
                'value' => isset($calendar) ? $calendar->project->firm_id : '',
                'values' => $firms ?? array(),
                'class' => 'select2-standard pick-firm',
                'attribute' => ''
            ])
            @endcomponent
            
          </div>
        </div>

        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Proje</label>
          <div class="col-lg-5">
            @component('components.forms.select', [
                'required' => false,
                'name' => 'project_id',
                'value' => isset($calendar) ? $calendar->project_id : '',
                'values' => $projects ?? array(),
                'class' => 'select2-standard  pick-contract',
                'attribute' => ''
            ])
            @endcomponent
          </div>
        </div>


        @if($authenticated->isManager()||$authenticated->isPartner())
          @if(Request::get('allow_person'))
            <div class="form-group row ">
              <label class="col-xl-3 col-lg-3 col-form-label">* Personel</label>
              <div class="col-lg-5">
                @component('components.forms.select', [
                    'required' => false,
                    'name' => 'user_id',
                    'value' => isset($users) ? $calendar->user_id : '',
                    'values' => $projects ?? array(),
                    'class' => 'select2-standard  pick-contract',
                    'attribute' => ''
                ])
                @endcomponent
              </div>
            </div>
          @endif
        @endif

        <div class="form-group row ">
          <label class="col-xl-3 col-lg-3 col-form-label">* Ofis içinde mi?</label>
          <div class="col-lg-4">
            <div class="radio-inline">
              <label class="radio radio--bold radio--success">
                <input type="radio" name="is_office" value="Evet"
                @if(isset($calendar['is_office']))
                  @if($calendar['is_office'])
                    checked
                  @endif
                @endif>
                <span></span> Evet
              </label>
              <label class="radio radio--bold radio--success">
                <input type="radio" name="is_office" value="Hayır"
                @if(isset($calendar['is_office']))
                  @if(!$calendar['is_office'])
                    checked
                  @endif
                @endif>
                <span></span> Hayır
              </label>
            </div>
          </div>
        </div>
                    
        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label mb-5">* Başlangıç/Bitiş</label>
          <div class="col-lg-3 mb-5">

          @if(isset($calendar)&&isset($calendar->real_start_at))
              <input class="form-control pick-date" data-dates-disabled="{{ Auth::user()->disabledMonths() }}" type="text" required name="start_at" value="{{ isset($calendar->real_start_at) ? date('d-m-Y', strtotime($calendar->real_start_at)) : date('d-m-Y', strtotime($dates['real_start_at'])) }}"
          @else 
              <input class="form-control pick-date" data-dates-disabled="{{ Auth::user()->disabledMonths() }}" type="text" required name="start_at" value="{{ isset($calendar->start_at) ? date('d-m-Y', strtotime($calendar->start_at)) : date('d-m-Y', strtotime($dates['start_at'])) }}"
          @endif
          @isset($calendar)
            {{ ($calendar->is_allowed==1 || $calendar->is_allowed==2) ? 'disabled' : '' }} 
          @endisset placeholder="Başlangıç">
          </div>
          <div class="col-lg-3 mb-5">
            <div class="input-group timepicker">
              <input 
                class="form-control pick-time" 
                readonly 
                placeholder="Başlangıç Saati" 
                name="start_time" 
                type="text" 
                value="{{ isset($calendar->real_start_at) ? date('H:i', strtotime($calendar->real_start_at)) : ( isset($calendar->start_at) ? date('H:i', strtotime($calendar->start_at)) : '09:00' ) }}"
                @isset($calendar)
                  {{ ($calendar->is_allowed==1 || $calendar->is_allowed==2) ? 'disabled' : '' }} 
                @endisset  />
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="la la-clock-o"></i>
                  </span>
                </div>
              </div>
          </div>
          <div class="col-lg-3 mb-5">
            <div class="input-group timepicker">
              <input 
                class="form-control pick-time" 
                readonly 
                placeholder="Başlangıç Saati" 
                name="end_time" 
                type="text" 
                value="{{ isset($calendar->real_end_at) ? date('H:i', strtotime($calendar->real_end_at)) : ( isset($calendar->end_at) ? date('H:i', strtotime($calendar->end_at)) : '09:00' ) }}"
                @isset($calendar)
                  {{ ($calendar->is_allowed==1 || $calendar->is_allowed==2) ? 'disabled' : '' }} 
                @endisset  />
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="la la-clock-o"></i>
                  </span>
                </div>
              </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-xl-3 col-lg-3 col-form-label">* Açıklama</label>
          <div class="col-lg-9">
              <input class="form-control" type="text" required name="name" value="{{ $calendar->name ?? '' }}"
          @isset($calendar)
                  {{ ($calendar->is_allowed==1 || $calendar->is_allowed==2) ? 'disabled' : '' }} 
          @endisset placeholder="Açıklama">
          </div>
        </div>

        @isset($calendar)
          @if($calendar->is_allowed == 1)
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label mb-5">* Gerçekleşen Tarih</label>
              <div class="col-lg-3 mb-5">
                  <input class="form-control pick-date" data-dates-disabled="{{ Auth::user()->disabledMonths() }}" type="text" required name="real_start_at" value="{{ $calendar->real_start_at > 0 ? date('d-m-Y', strtotime($calendar->real_start_at)) : date('d-m-Y', strtotime($calendar->start_at)) }}" placeholder="Başlangıç">
              </div>
              <div class="col-lg-3 mb-5">
                <div class="input-group timepicker">
                  <input 
                    class="form-control pick-time" 
                    readonly 
                    placeholder="Başlangıç Saati" 
                    name="real_start_time" 
                    type="text" 
                    value="{{ $calendar->real_start_at > 0 ? date('H:i', strtotime($calendar->real_start_at)) : date('H:i', strtotime($calendar->start_at)) }}"  />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="la la-clock-o"></i>
                      </span>
                    </div>
                  </div>
              </div>
              <div class="col-lg-3 mb-5">
                <div class="input-group timepicker">
                  <input 
                    class="form-control pick-time" 
                    readonly 
                    placeholder="Başlangıç Saati" 
                    name="real_end_time" 
                    type="text" 
                    value="{{ $calendar->real_end_at > 0 ? date('H:i', strtotime($calendar->real_end_at)) : date('H:i', strtotime($calendar->end_at)) }}" />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="la la-clock-o"></i>
                      </span>
                    </div>
                  </div>
              </div>
            </div>
          @endif
        @endisset


        </div>
      </div>
    </div>
  </div>
  <div class="portlet__foot">
    <div class="form__actions">
      <div class="row">
        <div class="col-lg-3 col-xl-3">
        </div>
        <div class="col-lg-9 col-xl-9" style="display:flex; justify-content:space-between">
          @isset($calendar)
            @if($calendar->is_allowed==1)
              <div class="alert alert-outline-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                </button>
                  <strong>Dikkat!</strong> Onaylanmış aktivite üstünde düzenleme yapamazsınız!
              </div>
              <!--<button type="submit" onclick="ok()" class="btn btn-success" >Gerçekleşen Aktiviteyi Kaydet</button>&nbsp;-->
            @elseif($calendar->is_allowed!=1)
              <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet</button>&nbsp;
            @else
              <button type="button" class="btn btn-success disabled" >Kaydet</button>&nbsp;
            @endisset
          @else 
            <button type="submit" onclick="ok()" class="btn btn-success" >Kaydet</button>&nbsp;
          @endisset

          @isset($calendar)
            @if($calendar->is_allowed==0)
              <button type="button" onclick="dateSil({{ $calendar->id ?? '' }})" data-id="" class="btn btn-danger" >Etkinliği Sil</button>&nbsp;
            @endif
          @endisset

        </div>
      </div>
    </div>
  </div>
</form>

<script>
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
  // minimum setup
  $('.pick-time').timepicker({
      minuteStep: 15,
      defaultTime: '09:00',           
      showSeconds: false,
      showMeridian: false,
      snapToStep: true
  }).on('changeTime.timepicker', function(e) {
    var name = $(e.target).attr('name');

    if(name == 'start_time'){
      $("[name=end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }

    if(name == 'real_start_time'){
      $("[name=real_end_time]").timepicker('setTime', e.time.value+':'+e.time.hours);
    }
  });
  
});
</script>

{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
.fc-event, .fc-list-item{
  cursor:pointer;
}
.fc-past{
    background:#fafafa;
    cursor:not-allowed;
    color:rgba(0,0,0,0.35);
    font-weight:normal;
}
.fc-day-number{
    color:#111;
}
.fc-past .fc-day-number{
    color:rgba(0,0,0,0.45);
}
.fc-today{
    background:none !important;
}
.fc-today .fc-day-number{
    width:28px;
    height:28px;
    line-height:28px;
    padding:0;
    background:#eb4d3e;
    color:#fff;
    border-radius:50%;
    display:inline-block;
    text-align:center;
}
.fc-sat { color:rgba(0,0,0,0.55); background:#f5f5f5;
    font-weight:bold; }
.fc-sun { color:rgba(0,0,0,0.55); background:#f5f5f5;
    font-weight:bold;  }
.fc-day-grid-event .fc-content{
    white-space:normal;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        @if($authenticated->isManager()||$authenticated->isPartner())
        <a href="{{ route('calendar-team') }}" class="btn btn-primary">Ekip Görünümü</a>&nbsp;&nbsp;
        @endif
        <a href="{{ route('calendar-confirmation-table') }}" class="btn btn-dark">Dönem Kapatma</a>&nbsp;&nbsp;
    </div>
  </div>

  <div class="card-body">
    <div id="kt_calendar" data-id="{{ Auth::user()->holidays() }}" style="width:100%;"></div>
  </div>
</div>

@endsection

@section('styles')
<style>
.nowrap{
  white-space:nowrap;
}
</style>
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script>
$("body").on('change', '.pick-firm', function(event){
  var thi = $(this);
  var a = $(this).val();
  
  $.ajax({
    type: "GET",
          url: "{{ route('get-project') }}",
    dataType: 'json',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: 'firm_id='+a,
    error: function(){
    },
    beforeSubmit: function(){
    },
    success: function(result){
      $(".pick-contract").html('<option>Seçiniz</option>');
      $.each(result, function(i, item) {
          $('.pick-contract').append('<option value="'+item.id+'">'+item.name+'</option>');
      });
        $(".select2-standard").select2();
    }
  });    
});

</script>
<script>
var todayDate = moment().startOf('day');
var YM = todayDate.format('YYYY-MM');
var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
var TODAY = todayDate.format('YYYY-MM-DD');
var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

var calendarEl = document.getElementById('kt_calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],

    isRTL: KTUtil.isRTL(),
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    locale: 'tr',
    lang: 'tr',
    firstDay: 1,

    height: 800,
    contentHeight: 750,
    aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

    views: {
        dayGridMonth: { buttonText: 'aylık' },
        timeGridWeek: { buttonText: 'haftalık' },
        timeGridDay: { buttonText: 'günlük' },
        listDay: { buttonText: 'liste' },
        listWeek: { buttonText: 'liste' }
    },

    defaultView: 'dayGridMonth',
    defaultDate: TODAY,

    editable: true,
    eventLimit: 4, // allow "more" link when too many events
    navLinks: true,
    disableDragging: true,
    events: '{{ route("calendar-json") }}' ,
    eventTimeFormat: { // like '14:30:00'
        hour: '2-digit',
        minute: '2-digit',
        meridiem: false
    },
    displayEventEnd:true,
    eventClick: function(info) {
        console.log(info);
        bdoModalRequest('{{ route("update-calendar") }}/'+info.event.extendedProps.myId, 'medium');
        //alert(info.event.extendedProps.myId);
    },
    dateClick: function(info) { 
        var name = $(info.dayEl);
        if(name.hasClass('fc-past')){
            bdoModalRequest('{{ route("add-calendar") }}?date='+info.dateStr+'&is_past=1', 'medium');
        }else{
            bdoModalRequest('{{ route("add-calendar") }}?date='+info.dateStr, 'medium');
        }
    },
    eventRender: function(info) {
        var element = $(info.el);

        console.log(info.event.extendedProps);
        
        if(info.event.extendedProps.my_status == "danger"){
        //element.find('.fc-content').prepend('<i class="fa fa-exclamation-triangle" style="color:#fff; margin-right:5px;"></i>');
        }
        

        if (info.event.extendedProps && info.event.extendedProps.description) {
            if (element.hasClass('fc-day-grid-event')) {
                element.data('content', info.event.extendedProps.name);
                element.data('placement', 'top');
                KTApp.initPopover(element);
            } else if (element.hasClass('fc-time-grid-event')) {
                element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.name + '</div>'); 
            } else if (element.find('.fc-list-item-title').lenght !== 0) {
                element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.name + '</div>');
            }
        } 
        element.append('</a>');

        
        element.find(".closeon").on('click', function() {
            //alert(info.event.extendedProps.myId);
            console.log('delete');
        });

    }
});

calendar.render();
</script>
<script>
    function dateSil(a){
        var x = confirm("Emin misiniz? Bu işlemin geri dönüşü yoktur!");
        if (x){
          
          $.ajax({
            type: "GET",
            url: '{{ route("delete-calendar") }}/'+a,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(){
            },
            beforeSubmit: function(){
            },
            success: function(result){
                  closeModal();
                  calendar.refetchEvents();
            }
          });
        }
      }
  $(document).ready(function(){
      
    $(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
  });
$("body").on('click', '.custom-file-upload', function(){
    $(this).next().trigger('click');
});
$("body").on('change', '.file_side input[type="file"]', function(event){
    var thi = $(this);
    var a = $(this).val();
    $(this).prev().addClass("active").html(a);

        var files = event.target.files;
        
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    $.ajax({
        type: "POST",
        url: "/sozlesmeler/upload",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(item){
            thi.closest(".file_side").find(".file_input").val(item.file);
            thi.prev().html(item.file);
        }
    });

});

$("body").on('change', '.firma-sec', function(event){
    var thi = $(this);
    var a = $(this).val();

    $.ajax({
        type: "GET",
        url: "/sozlesmeler/getir",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'firm_id='+a,
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(result){
            $(".sozlesme-sec").html('<option>Seçiniz</option>');
            $.each(result, function(i, item) {
                console.log(item);
                $('.sozlesme-sec').append('<option value="'+item.project.id+'">'+item.project.name+'</option>');
            });
            $(".select2").select2();
        }
    });
    
});

$("body").on('change', '.sozlesme-sec', function(event){
    var thi = $(this);
    var a = $(this).val();

    $.ajax({
        type: "GET",
        url: "/sozlesmeler/personel-getir",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'contract_id='+a,
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(result){
            $(".odeme-sec").html('<option>Seçiniz</option>');
            $.each(result, function(i, item) {
                $('.odeme-sec').append('<option value="'+item.id+'">'+item.last_date+'</option>');
            });
            $(".select2").select2();
        }
    });
});
</script>
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
                    $(".formprogress").hide();
                    closeModal();
                    calendar.refetchEvents();
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
    </script>
@endsection

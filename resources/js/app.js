require('./bootstrap');
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

function formatlar(){
  $(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
  $('.date-format').datepicker({
      orientation: "bottom right",
      allowInputToggle: true,
      format: 'dd-mm-yyyy',
      language: 'tr'
  }); // minimum setup for modal demo
  $(".date-format").inputmask("99-99-9999");

  $('.date-format').datepicker({
    orientation: "bottom right",
    allowInputToggle: true,
    format: 'dd-mm-yyyy',
    language: 'tr'
  }); // minimum setup for modal demo
  $(".date-format").inputmask("99-99-9999");

  $('[name=end_at]').datepicker({
    orientation: "bottom right",
    allowInputToggle: true,
    format: 'dd-mm-yyyy',
    language: 'tr'
  });

  $('[name=start_at]').datepicker({
    orientation: "bottom right",
    allowInputToggle: true,
    format: 'dd-mm-yyyy',
    language: 'tr'
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('[name=end_at]').datepicker('setStartDate', minDate);
    $('[name=end_at]').datepicker('setDate', minDate); // <--THIS IS THE LINE ADDED
  });
  $("[name=start_at]").inputmask("99-99-9999");
  $("[name=end_at]").inputmask("99-99-9999");
  

  $('.tc-no').inputmask('99999999999');
  $('.tax_no').inputmask('999 999 999 99');
  $(".phone").inputmask({
      "mask": "(599) 999-9999",
      definitions: {'5': {validator: "[1-9]"}},
      "showMaskOnHover": false
  });
  $('.select2-standard').select2();
}
formatlar();

$('.general-form').ajaxForm({
  beforeSubmit:  function(formData, jqForm, options){
      var val = null;
  },
  error: function(){
      $(".formprogress").hide();
      $(".my-loader").removeClass('active');
        swal.fire({
          text: "Dikkat! Sistemsel bir hata nedeniyle kaydedilemedi!",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Tamam",
          customClass: {
            confirmButton: "btn font-weight-bold btn-light-primary"
          }
        }).then(function() {
          KTUtil.scrollTop();
        });
  },
  dataType:  'json',
  success:   function(item){
    $(".my-loader").removeClass('active');
    $(".formprogress").hide();
    console.log(item);
    if(item.status){
        swal.fire({
        html: item.message,
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Tamam",
        customClass: {
          confirmButton: "btn font-weight-bold btn-light-primary"
        }
      }).then(function() {
          if(item.redirect){
              window.location.href = item.redirect;
          }else if(item.modal){
              $('.modal').modal('hide');
          }else if(item.modal){

          }else{
              location.reload();
          }
      });
    }else{
      $('.is-invalid').removeClass('is-invalid').closest('.form-group').find('.invalid-feedback').hide();
      $('.is-invalid').removeClass('is-invalid').closest('.form-group').removeClass('.invalid-select');
      $.each(item.errors, function(key, value) {
        if(!key.includes('.')){
          $("[name="+key+"]").addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
          $.each(value, function(k, v) {
            $("[name="+key+"]").closest('.form-group').addClass('invalid-select').find('.invalid-feedback').append(v + "<br>");
          });
        }
      });

      swal.fire({
        html: item.message,
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Tamam",
        customClass: {
          confirmButton: "btn font-weight-bold btn-light-primary"
        }
      }).then(function() {
        KTUtil.scrollTop();
      });
    }
  }
});

function cb(start, end, label) {
var title = '';
var range = '';

if ((end - start) < 100 || label == 'Bugün') {
    title = 'Bugün:';
    range = start.format('MMM D');
} else if (label == 'Yesterday') {
    title = 'Dün:';
    range = start.format('MMM D');
} else {
    range = start.format('MMM D') + ' - ' + end.format('MMM D');
}

$('#kt_dashboard_daterangepicker_date').html(range);
$('#kt_dashboard_daterangepicker_title').html(title);
}

/* Tarih değiştirici */
function tarihDegistirici(){
if ($('#main_date_changer').length == 0) {
return;
}

var picker = $('#main_date_changer');
var start = moment();
var end = moment();


picker.daterangepicker({
startDate: start,
endDate: end,
opens: 'left',
applyClass: 'btn-primary',
cancelClass: 'btn-light-primary',
language: 'tr',
ranges: {
    'Bugün': [moment(), moment()],
    'Dün': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Son 7 Gün': [moment().subtract(6, 'days'), moment()],
    'Son 30 Gün': [moment().subtract(29, 'days'), moment()],
    'Bu Ay': [moment().startOf('month'), moment().endOf('month')],
    'Geçen Ay': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Bu Yıl': [moment().startOf('year').startOf('month'), moment().endOf('month')]
},
ranges: {
   'Bugün': [moment(), moment()],
   'Dün': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
   'Son 7 gün': [moment().subtract(6, 'days'), moment()],
   'Son 30 gün': [moment().subtract(29, 'days'), moment()],
   'Bu ay': [moment().startOf('month'), moment().endOf('month')],
   'Geçen ay': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
   'Bu Yıl': [moment().startOf('year').startOf('month'), moment().endOf('month')]
},
"locale": {
  "format": "DD/MM/YYYY",
  "separator": " - ",
  "applyLabel": "Uygula",
  "cancelLabel": "Vazgeç",
  "fromLabel": "Dan",
  "toLabel": "a",
  "customRangeLabel": "Tarih Aralığı Seç",
  "daysOfWeek": [
      "Pt",
      "Sl",
      "Çr",
      "Pr",
      "Cm",
      "Ct",
      "Pz"
  ],
  "monthNames": [
      "Ocak",
      "Şubat",
      "Mart",
      "Nisan",
      "Mayıs",
      "Haziran",
      "Temmuz",
      "Ağustos",
      "Eylül",
      "Ekim",
      "Kasım",
      "Aralık"
  ],
  "firstDay": 1
}
}, cb);

picker.on('apply.daterangepicker', function(ev, picker) {
var url = new URL(window.location.href);
url.searchParams.set('start_at',picker.startDate.format('YYYY-MM-DD'));
url.searchParams.set('end_at',picker.endDate.format('YYYY-MM-DD'));
window.location.href = url.href;

});
}

tarihDegistirici();

{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      @php
        $current_year = Request::get('year') ? Request::get('year') : date('Y');
        $current_month = Request::get('month') ? Request::get('month') : str_replace('0', '', date('m'));
      @endphp
      <div class="card-toolbar">
          <form action="{{ route('costs') }}">
            <div class="form-group" style="margin-bottom:0; display:flex; width:350px;">
              <select name="year" id="" class="select2-standard form-control">
                @foreach(range(date('Y'), date('Y')-5) as $y)
                    <option value="{{ $y }}" {{ $current_year==$y ? 'selected' : '' }} >{{ $y }}</option>
                @endforeach
              </select>&nbsp;&nbsp;
              <select name="month" id="" class="select2-standard form-control">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ $current_month==$month ? 'selected' : '' }}  >{{ $month }}. Ay</option>
                @endforeach
              </select>&nbsp;&nbsp;
              <button class="btn btn-light-success" type="submit">Filtrele</button>
            </div>
          </form>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <a href="{{ route('add-cost') }}" class="btn btn-primary font-weight-bolder new-cost">
            <span class="svg-icon svg-icon-md">
              {{ Metronic::getSVG("media/svg/icons/Navigation/Plus.svg", "svg-icon-1x") }}
            </span>Yeni Masraf</a>
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable" id="teklif-listesi">
      </table>
    </div>
  </div>
</div>

<!-- Modal-->
<div class="modal fade" id="cost_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Masraf Girişi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
              <form class="datatable-refresh-form cost-form-area" method="post" action="{{ route('save-cost') }}">

              </form>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
.nowrap{
  white-space:nowrap;
}
  
</style>
@endsection
@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

  {{-- page scripts --}}
  <script>
  $('body').on('click', '.new-cost', function(e){
    e.preventDefault();

    var url = $(this).attr('href');

    $.ajax({
      url: url,
      type: 'get',
      dataType: 'html',
      success: function(result){
        $('.cost-form-area').html(result)
        $('#cost_modal').modal('show')
      }
    })
  });

  $('.select2-standard').select2({
  placeholder: "Seçiniz",
  allowClear: true,
  });
  var qrform = $('#teklif-listesi').DataTable({
      responsive: true,
      "processing": true,
      "serverSide": true,
      "ajax": "{{ route('costs-json') }}?{!! \Request::getQueryString() !!}",
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      "order": [[ 0, "desc" ]],
      "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip()

      },
      columns: [
        { data: 'doc_date', name: 'doc_date', title: 'Tarih', "render": function (data, type, row) {
        return '<span class="label label-lg font-weight-bold  label-light-primary label-inline mb-2" style="white-space:nowrap;">' + row.date_formatted + '</span>';
        },
        },
        { data: 'customer.title', name: 'customer.title', title: 'Müşteri', "render": function (data, type, row) {
                return '<div class="d-flex align-items-center" style="max-width:220px;">\
                        <span class="symbol symbol-45 symbol-light-dark ">\
                          '+( row.customer.logo ? '<img src="https://bkmcdn.s3.eu-central-1.amazonaws.com/uploads/company/'+row.customer.logo+'" style="object-fit:contain;" />' : '<span class="symbol-label font-size-h5">'+ data.charAt(0) + ' ' ) +'</span>\
                        </span>\
                        <div class="ml-4">\
                        <div class="text-dark-75 font-weight-bolder font-size-md mb-0">\
                            ' + data + '\
                        </div>\
                        <a class="text-muted font-weight-bold text-hover-primary">' + (row.project_id ? row.project.title : '') + "</a>\
                        <div>\
                    </div>\
                    </div>\
                </div>"
            }
        },
        { data: 'expense_doc_type.name', name: 'expense_doc_type.name', title: 'Gider Türü', "render": function (data, type, row) {
          return '<div class="d-flex align-items-center" style="max-width:220px;">\
            <div>\
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.expense.name + '</div>\
                <a class="text-muted font-weight-bold text-hover-primary">' + ( row.expense_doc_type ? row.expense_doc_type.name : '' ) + '</a>\
            </div>\
            </div>';
            },
        },
        { data: 'description', name: 'description', title: 'Bilgiler', "render": function (data, type, row) {

          return '<div class="d-flex align-items-center" style="max-width:220px; flex-direction:column">\
            <div>\
                <a class="text-dark font-weight-bold text-hover-primary">' + ( row.firm ? row.firm : '' ) + '</a>\
            </div>\
            <div>\
                <a class="text-muted font-weight-bold text-hover-primary">' + ( row.doc_no ? row.doc_no : '' ) + '</a>\
                  <i class="fa fa-pen"  data-skin="light" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+data+'"></i>\
            </div>\
            </div>';
            },
        },
        { data: 'price', name: 'price', title: 'Tutar', "render": function (data, type, row) {
        return '<span class="label label-lg font-weight-bold  label-light-info label-inline mb-2" style="white-space:nowrap;">' + formatMoney(data) + ' TL</span>';
        },
        },
        { data: 'status', name: 'status', title: 'Durum', className: 'nowrap', "render": function (data, type, row) {
            if(row.status == 'Onaylandı'){
              return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">' + data + '</span>'
            }else if(row.status == 'Reddedildi'){
              return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">' + data + '</span>'
            }else {
              return '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-primary">' + data + '</span>'
            }
        }
        },
        { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
            var editme = '<a class="btn btn btn-icon btn-light btn-hover-primary btn-sm new-cost"  href="{{ route("update-cost") }}/'+data+'" data-toggle="tooltip" data-theme="light" title="Düzenle"> <i class="flaticon2-writing icon-md text-primary"></i></a>&nbsp;';
            var detailme ="";
            if(row.file){
            detailme = '<a \
              data-fancybox data-caption="'+ row.customer.title +' ' + row.project.name + ' "\
              href="{{ Storage::url("uploads/cost") }}/' + row.file +  '"  class="btn btn btn-icon btn-light btn-hover-info btn-sm" target="_blank" data-toggle="tooltip" data-theme="light" title="Dosyayı Gör"><i class="far fa-file-alt icon-md text-info"></i>\
              </a>&nbsp;';
            }

            var deleteme = "";

            deleteme = '<a href="{{ route("delete-cost") }}/'+data+'" class="btn btn-icon btn-light btn-hover-danger btn-sm sorgula" data-toggle="tooltip" data-theme="light" title="Sil">\
            <i class="flaticon2-trash icon-md text-danger"></i>\
            </a>';

            var result = row.detail_allowed&&row.file ? detailme : '';
            result += row.edit_allowed ? editme : '';
            result += row.delete_allowed ? deleteme : '';

            return '<div style="white-space:nowrap">'+result+'</div>';
        }
      },
      { data: 'doc_no', name: 'doc_no', visible: false },
      { data: 'expense.name', name: 'expense.name', visible: false },
      ],
      buttons: [
      'print',
      'copyHtml5',
      'excelHtml5',
      { extend: 'csvHtml5', text: 'CSV'  },
      'pdfHtml5',
      ]
  });
  </script>
  <script>
    $('.datatable-refresh-form').ajaxForm({
        beforeSubmit:  function(formData, jqForm, options){
        },
        error: function(){
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
          $(".formprogress").hide();
          if(item.status){
            $("[name=doc_no]").val('')
            $("[name=description]").val('')
            $("[name=price]").val('')
            $('.custom-file-upload').html('Dosya Ekle')
            $('.file_input').val('')
            toastr.success(item.message);
            qrform.ajax.reload();
            $('.modal').modal('hide')
          }else{
            $('.is-invalid').removeClass('is-invalid').closest('.form-group').find('.invalid-feedback').hide();
            $('.is-invalid').removeClass('is-invalid').closest('.form-group').removeClass('.invalid-select');
            $.each(item.errors, function(key, value) {
              $("[name="+key+"]").addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
              $.each(value, function(k, v) {
                $("[name="+key+"]").closest('.form-group').addClass('invalid-select').find('.invalid-feedback').append(v + "<br>");
              });
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
    </script>
  <script>
  $("body").on('click', '.sorgula', function(e){
      e.preventDefault();
      var thi = $(this);
      var href = $(this).attr('href');
      swal.fire({
          title: "Emin misiniz?",
          text: "Bu işlemi yapmak istediğinizi onaylayın.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Evet!",
          cancelButtonText: "Hayır!",
          reverseButtons: true
      }).then(function(result) {
          if (result.value) {
              $.ajax({
                  url: href,
                  dataType: 'json',
                  type: 'get',
                  success: function(data){
                      if(data.status){
                          qrform.ajax.reload();
                      }else{
                          swal.fire(
                              "Dikkat",
                              data.message,
                              "error"
                          )
                      }
                  }
              });
          } else if (result.dismiss === "cancel") {

          }
      });
  });
  </script>
  <script>
  $("body").on('change', '.pick-customer', function(){
      var val = $(this).val();
      $.ajax({
          url: '{{ route("projects-list") }}',
          data: 'query=' + val,
          dataType: 'json',
          success: function(response){
              $(".pick-project").html('<option value="">Seçiniz</option>')
              $.each(response, function(i, item) {
                  $(".pick-project").append('<option value="'+item.value+'">' + item.name + '</option>')
              });
              var flg = 0;
              $('.getting-projects').select2();
              $('.getting-projects').on("select2:open", function () {
                  flg++;
                  if (flg == 1) {
                      $('.add-new-project').remove();
                      $(".select2-results").append("<div class='select2-results__option add-new-project'>\
                      <a href='#' class='btn btn-sm font-weight-bolder btn-light-danger btn-block'><span class='flaticon2-plus'></span> Yeni Proje Ekle</a>\
                      </div>");
                  }
              });
          }
      })
  });

  $("body").on('click', '.custom-file-upload', function(){
    $(this).next().trigger('click');
  });
  $("body").on('change', '.file_side input[type="file"]', function(event){
  console.log('çalıştı');
    var thi = $(this);
    var a = $(this).val();
    $(this).prev().addClass("active").html(a);
    
    $("button[type=submit]").addClass('disabled');

      var files = event.target.files;
      
    var data = new FormData();
    $.each(files, function(key, value)
    {
      data.append(key, value);
    });

    $.ajax({
      type: "POST",
      url: "{{ route('cost-upload') }}",
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
        $("button[type=submit]").removeClass('disabled');
        thi.closest(".file_side").find(".file_input").val(item.file);
        thi.prev().html(item.file);
      }
    });

  });
</script>

<script>
$('body').on('change', '[name=is_food]', function(){
  $("[name=doc_no]").val('')
  $("[name=description]").val('')
  $("[name=price]").val('')
  $('.custom-file-upload').html('Dosya Ekle')
  $('.file_input').val('')
  $('.date-format').val('')
  $('.multi-date-format').val('')
  if($(this).is(':checked')){
    $('.not-food').addClass('d-none')
    $('.only-food').removeClass('d-none')
  }else{
    $('.not-food').removeClass('d-none')
    $('.only-food').addClass('d-none')
  }
})
</script>
@endsection

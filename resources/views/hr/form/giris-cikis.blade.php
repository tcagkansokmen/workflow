{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="kt-portlet" style="margin-top:25px;">
   <div class="card-body">
    <div class="kt-widget kt-widget--user-profile-3">
      <div class="kt-widget__top">
        @isset($form->event->logo)
          <div class="kt-widget__media">
            <img src="{{ Storage::url('uploads/event/') }}{{ $form->event->logo }}" alt="image" style="height:120px; border:1px solid #ddd; object-fit:contain;">
          </div>
        @else
        <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-bolder kt-font-light kt-hidden">
          {{ strtoupper(substr($form->event->title, 0, 1)) }}
        </div>
        @endif
        <div class="kt-widget__content">
          <div class="kt-widget__head">
            <div class="kt-widget__user">
              <a href="#" class="kt-widget__username">
                {{ $form->title }}
              </a>
              @if(strtotime(date("Y-m-d")) > strtotime($form->end_at))
              <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-danger">{{ $form->event->title }}</span>
              @else 
              <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success">{{ $form->event->title }}</span>
              @endif
            </div>
            <div class="kt-widget__action">
              @if(Request::user()->isAttendee())
              <a href="{{ route('form-detail', ['form_id' => $form->id]) }}" target="_blank" class="btn btn-info btn-sm btn-upper"><i class="fa fa-search"></i> Form Detayları</a>
              @endisset
              <a href="{{ route('form-contacts', ['form_id' => $form->id]) }}" class="btn btn-label-brand btn-sm btn-upper">Kişi Listesi</a>
            </div>
          </div>
          <div class="kt-widget__subhead">
            <a href="#"><i class="flaticon2-calendar-3"></i>{{ date('d.m.Y', strtotime($form->start_at)) }} - {{ date('d.m.Y', strtotime($form->end_at)) }}  </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="kt-portlet" style="margin-top:25px;">
   <div class="card-body">
  <form class="kt-form qr-form" method="POST" action="{{ route('qr-reader') }}" >
    <input type="hidden" name="turnike" value="Manuel">
    <input type="hidden" name="form_id" value="{{ $form->id }}">
    @csrf
    <div class="form-group row">
      <div class="col-lg-3">
          <input class="form-control" type="text" name="qr" >
      </div>
      <div class="col-lg-3">
          <select name="type" id="" class="custom-select">
            <option value="Giriş">Giriş</option>
            <option value="Çıkış">Çıkış</option>
          </select>
      </div>
      <div class="col-lg-2">
          <button class="btn btn-success btn-block" type="submit">Kaydet</button>
      </div>
    </div>
  </form>
  <div class="tablo">
    <table class="table table-striped- table-hover table-checkable" id="qr-form">
      <thead>
        <tr>
          <th>İsim</th>
          <th>Turnike</th>
          <th>İşlem</th>
          <th>Tarih</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  </div>
</div>

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')

<script>
		var qrform = $('#qr-form').DataTable({
			responsive: true,
			// Pagination settings
        "processing": true,
        "serverSide": true,
        "ajax": "/form/giris-cikis/{{ $form->id }}/json",
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
        <'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        pageLength: 100,
        "language": {
        "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
        },
        columns: [
            { data: 'name', name: 'form_contacts.name' },
            { data: 'turnike', name: 'turnike' },
            { data: 'type', name: 'type', "render": function (data, type, row) {
              return '<span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-'+ (data == "Giriş" ? "success" : "danger" ) + '">' + data +'</span>';
          }
        },
            { data: 'created_at', name: 'created_at' }
        ],
        buttons: [
          'print',
          'copyHtml5',
          'excelHtml5',
          { extend: 'csvHtml5', text: 'Excel'  },
          'pdfHtml5',
        ]
    });
  $(document).ready(function(){
    
  $('.qr-form').ajaxForm({ 
    beforeSubmit:  function(){
      $(".qr-form button[type=submit]").attr('disabled', true);
        $(".formprogress").show();
    },
    error: function(){
      $(".qr-form button[type=submit]").removeAttr('disabled');
      swal.fire({
        "title": "",
        "text": "Kaydedilemedi",
        "type": "warning",
        "confirmButtonClass": "btn btn-secondary"
      });

    },
    dataType:  'json', 
    success:   function(item){
      $(".qr-form button[type=submit]").removeAttr('disabled');
        $(".formprogress").hide();
        if(item.status){
          $(".qr-form input[name=qr]").val('').focus();
          qrform.fnDraw();

        }else{
          swal.fire({
            "title": "",
            "text": item.message,
            "type": "warning",
            "confirmButtonClass": "btn btn-secondary"
          });

        }
    }
}); 
}); 
</script>
@endsection
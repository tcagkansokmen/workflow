{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
table td{
  vertical-align:middle !important;
}
</style>
<div class="container">
  <div class="card card-custom">
    <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">Aktivite Onay Listesi</h3>
      </div>
      <div class="card-toolbar">
        <form action="{{ route('accept-calendar') }}" class="mr-5">
          <div class="form-group" style="margin-bottom:0; display:flex;">
            <div class="form-group mr-2 mb-0">
              <select name="year" id="" class="select2-standard form-control" style="width:100px;">
                @foreach(range(date('Y'), date('Y')-5) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mr-2 mb-0">
              <select name="month" id="" class="select2-standard form-control" style="width:100px;">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ date('m')==$month ? 'selected' : '' }} >{{ $month }}. Ay</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mr-2 mb-0">
              <select name="status" id="" class="select2-standard form-control" style="width:100px;">
                  <option value="all">Tümü</option>
                  <option value="0">Bekleyen</option>
                  <option value="1">Onaylanan</option>
                  <option value="1">Reddedilen</option>
              </select>
            </div>
            <button class="btn btn-success" type="submit">Filtrele</button>
          </div>
        </form>
          <button href="#" class="btn btn-light-success btn-bold kt-margin-l-10 kt-hidden tumunu-onayla" >
            Tümünü Onayla
          </button>&nbsp;
      </div>
    </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable" id="kt_table_1">
        <thead>
          <tr>
            <th style="max-width:55px !important">
              <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                <input type="checkbox" class="selectall" value="1">
                <span></span>
              </label>
            </th>
            <th>Personel</th>
            <th>Proje</th>
            <th>Açıklama</th>
            <th>Nerede</th>
            <th>Tarihi</th>
            <th>Durum</th>
            <th class="align-right">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr class="tr-{{ $d->id }}">
          <td>
              <label class="kt-checkbox kt-checkbox--bold kt-checkbox--info">
                <input type="checkbox" name="ids[]" class="selectself" value="{{ $d->id }}">
                <span></span>
              </label>
          </td>
          <!--
            <td style="max-width:55px !important">
              @if($d['start_at'] == '0000-00-00 00:00:00')
              <span
                class="btn btn-outline-hover-info btn-elevate btn-circle btn-icon btn-sm" 
                data-skin="dark" 
                data-toggle="kt-tooltip" 
                data-placement="top" title="" data-original-title="Planlanmamış 'gerçekleşen aktivite' girişi">
              <i class="flaticon2-exclamation"></i>
              </span>
              @endif
            </td>
            -->
            <td>
            <div class="d-flex align-items-center">
              <div class="symbol symbol-light-dark flex-shrink-0">
                  @if($d->user->avatar)
                    <a href="#" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >
                      <img src="{{ Storage::url('uploads/users') }}/{{ $d->user->avatar }}" alt="image">
                    </a>
                  @else 
                    <span class="symbol-label font-size-h5 kt-margin-r-5 t-5">
                      <span>{{ strtoupper(substr($d->user->name, 0, 2)) }}</span>
                    </span>
                  @endif
                </div>
                <div class="ml-4">
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->user->name }}</div>
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ $d->user->title }}</a>
                </div>
              </div>
            </td>
            <td>
            <div class="d-flex align-items-center">
              <div class="symbol symbol-light-dark flex-shrink-0">
              @if($d->firm->logo)
                  <a href="{{ route('firm-detail', ['id' => $d->firm->id]) }}" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >
                    <img src="{{ Storage::url('uploads/company') }}/{{ $d->firm->logo }}" alt="image">
                  </a>
                @else 
                  <span class="symbol-label font-size-h5 kt-margin-r-5 t-5">
                    <span>{{ strtoupper(substr($d->firm->title, 0, 2)) }}</span>
                  </span>
                @endif
              </div>
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->firm->shortcode }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ $d->project->name }}</a>
              </div>
            </div>
            </td>
            <td>
              {{ $d->name }}
            </td>
            <td>

              @if($d->is_office == 'Evet')
                <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Ofiste</span>
              @else
                <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">Ofis Dışı</span>
              @endif

            </td>
            <td>
              @if($d['start_at'] == '0000-00-00 00:00:00')
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ date('d.m.Y', strtotime($d->real_start_at)) }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ date('H:i', strtotime($d->real_start_at)) }} - {{ date('H:i', strtotime($d->real_end_at)) }}</a>
              </div>
              @else 
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ date('d.m.Y', strtotime($d->start_at)) }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ date('H:i', strtotime($d->start_at)) }} - {{ date('H:i', strtotime($d->end_at)) }}</a>
              </div>
              @endif
            </td>
            <td class="status-side">
              @if($d->is_allowed == '1')
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Onaylandı</span>
              @elseif($d->is_allowed == '2')
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">Reddedildi</span>
              @else
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">Beklemede</span>
              @endif
            </td>
            <td style="white-space:nowrap">
              <button class="btn btn-outline-success btn-icon btn-sm takvim-onayla" data-id="{{ $d->id }}" data-status="1"
              @if($d->is_allowed == '1')
                disabled
              @endif>
                <i class="fa fa-check"></i>
              </button>
              <button class="btn btn-outline-danger btn-icon btn-sm takvim-onayla" data-id="{{ $d->id }}" data-status="2"
              @if($d->is_allowed == '2')
                disabled
              @endif>
                <i class="fa fa-times"></i>
              </button>
            </td>
          </tr>
          @endforeach
          @if(!count($data))
          <tr>
            <td colspan="9">Onayınızı bekleyen aktivite bulunmamaktadır.</td>
          </tr>
          @endif
        </tbody>
      </table>

      <!--end: Datatable -->
    </div>
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
  $('body').on('change', '.selectall', function(){
    var thi = $(this);
    if(!thi.is(':checked')){
      console.log('1');
      $('.selectself').removeAttr('checked');
    }else{
      console.log('2');
      $('.selectself').attr('checked', true);
    }
  });

  $('body').on('change', '.selectself, .selectall', function(){
    var a = $('.selectself:checked');

    if(a.length){
      $('.tumunu-onayla').removeClass('kt-hidden');
    }else{
      $('.tumunu-onayla').addClass('kt-hidden');
    }
  });
});
$(document).ready(function(){
  $('body').on('click', '.tumunu-onayla', function(){
    $('.my-loader').addClass('active');
    var ar = [];
    $('.selectself:checked').each(function(index){
      var val = $(this).val();
      ar.push(val);
    });

      $.ajax({
        type: "POST",
        url: "{{ route('update-calendar-status') }}",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'ids='+ar+"&status=1",
        error: function(){
          $('.my-loader').removeClass('active');
        },
        beforeSubmit: function(){
        },
        success: function(result){

          $.each(ar, function( index, val ) {
            var thi = $('.tr-'+val).find('.takvim-onayla');
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Onaylandı</span>';
            thi.closest("tr").find('.status-side').html(status);
          });
          $('.my-loader').removeClass('active');
        }
      });
   
  });
  $("body").on('click', '.takvim-onayla', function(event){
    var thi = $(this);
    var a = $(this).attr('data-status');
    var id = $(this).attr('data-id');
    
    var r = confirm("Emin misiniz?");
    if (r == true) {
      $.ajax({
        type: "POST",
        url: "{{ route('update-calendar-status') }}",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'id='+id+"&status="+a,
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(result){

          if(a == "1"){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Onaylandı</span>';
          }else if(a == "2"){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">Reddedildi</span>';
          }else{
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">Beklemede</span>';
          }

          thi.closest("tr").find('.status-side').html(status);
        }
      });
    }

        
  });
});
</script>
@endsection

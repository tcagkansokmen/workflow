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
          <h3 class="card-label">Masraf Onay Listesi</h3>
      </div>
      <div class="card-toolbar">
          @if($authenticated->isAccountant())
          <a href="{{ route('cost-waiting-approval') }}?status=Bekliyor" class="btn btn-light-info btn-bold btn-icon-h mr-3" >
            Ödeme Bekleyenler
          </a>&nbsp;
          <a href="{{ route('cost-waiting-approval') }}?status=odenen" class="btn btn-light-success btn-bold btn-icon-h mr-3" >
            Ödenenler
          </a>&nbsp;
          @else 
          <a href="{{ route('cost-waiting-approval') }}?status=Bekliyor" class="btn btn-light-info btn-bold btn-icon-h mr-3" >
            Onay Bekleyenler
          </a>&nbsp;
          <a href="{{ route('cost-waiting-approval') }}?status=onaylanan" class="btn btn-light-success btn-bold btn-icon-h mr-3" >
            Onaylananlar
          </a>&nbsp;
          <a href="{{ route('cost-waiting-approval') }}?status=reddedilen" class="btn btn-light-danger btn-bold btn-icon-h mr-3" >
            Reddedilenler
          </a>&nbsp;
          @endif

          <button href="#" class="btn btn-success btn-bold mr-3 hidden tumunu-onayla" >
            Seçilenleri Onayla
          </button>&nbsp;
      </div>
    </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="kt_table_1">
        <thead>
          <tr>
            <th style="max-width:55px !important">
              <label class="checkbox checkbox--bold checkbox--success">
                <input type="checkbox" class="selectall" value="1">
                <span></span>
              </label>
            </th>
            <th>Personel</th>
            <th>Türü</th>
            <th>Tutar</th>
            <th>Tarihi</th>
            <th>Dosya</th>
            <th>Durum</th>
            <th class="align-right">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr class="tr-{{ $d->id }}">
          <td>
              <label class="checkbox checkbox--bold checkbox--info">
                <input type="checkbox" name="ids[]" class="selectself" value="{{ $d->id }}">
                <span></span>
              </label>
          </td>
            <td>
            <div class="d-flex align-items-center">
              <div class="symbol symbol-light-dark flex-shrink-0">
                @if($d->user->avatar)
                  <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >
                    <img src="{{ Storage::url('uploads/users') }}/{{ $d->user->avatar }}" alt="image">
                  </a>
                @else 
                  <span class="symbol-label font-size-h5 kt-margin-r-5 t-5">
                    <span>{{ strtoupper(substr($d->user->name, 0, 2)) }}</span>
                  </span>
                @endif
              </div>
              <div class="ml-4">
                <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->user->name }} {{ $d->user->surname }}</a>
                <a href="{{ route('personel-detail', ['id' => $d->user->id]) }}" class="text-muted font-weight-bold d-block  text-hover-primary">{{ $d->user->title }}</a>
              </div>
            </div>
            </td>
            <td>
              {{ $d->expense->name }}
                  <i class="fa fa-pen"  data-skin="light" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $d->description }}"></i>
            </td>
            <td>
              {{ money_formatter($d->price) }}TL
            </td>
            <td>
              {{ date('d.m.Y', strtotime($d->doc_date)) }}
            </td>
            <td>
              @if($d->file)
              <a 
              data-fancybox data-caption="{{ $d->customer['title'] }} - {{ $d->project['name'] }}"
              href="{{ Storage::url('uploads/cost') }}/{{ $d->file }}" class="btn btn-bold btn-sm btn-font-sm  btn-light-warning" target="_blank">
                <i class="flaticon-file-1"></i>
              </a>
              @endif
            </td>
            <td class="status-side">
              @if($d->status == 'Onaylandı')
                @if($authenticated->isAccountant())
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
                @else 
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
                @endif
              @elseif($d->status == 'ödendi')
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
              @elseif($d->status == 'Reddedildi')
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">{{ $d->status }}</span>
              @else
            <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
              @endif
            </td>
            <td style="white-space:nowrap">
              @if($authenticated->group_id==1&&$d->status!='ödendi')
              <button class="btn btn-outline-success btn-icon btn-sm masraf-onayla" data-id="{{ $d->id }}" data-status="Onaylandı"
              @if($d->status == 'Onaylandı')
                disabled
              @endif
              >
                <i class="fa fa-check"></i>
              </button>
              <button class="btn btn-outline-danger btn-icon btn-sm masraf-onayla" data-id="{{ $d->id }}" data-status="Reddedildi"
              @if($d->status == 'Reddedildi')
                disabled
              @endif
              >
                <i class="fa fa-times"></i>
              </button>
              @elseif($authenticated->isAccountant())
              <button class="btn btn-outline-success btn-icon btn-sm masraf-onayla" data-id="{{ $d->id }}" data-status="ödendi"
              @if($d->status == 'ödendi')
                disabled
              @endif
              >
                <i class="fa fa-check"></i>
              </button>
              @endif
            </td>
          </tr>
          @endforeach
          @if(!count($data))
          <tr>
            <td colspan="11">Onayınızı bekleyen masraf bulunmamaktadır.</td>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
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
      $('.tumunu-onayla').removeClass('hidden');
    }else{
      $('.tumunu-onayla').addClass('hidden');
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
        url: "{{ route('update-cost-status') }}",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: 'ids='+ar+"&status=Onaylandı",
        error: function(){
          $('.my-loader').removeClass('active');
        },
        beforeSubmit: function(){
        },
        success: function(result){
          toastr.success('Başarılıyla güncellediniz');
          $.each(ar, function( index, val ) {
            var thi = $('.tr-'+val).find('.masraf-onayla');
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">Onaylandı</span>';
            thi.closest("tr").find('.status-side').html(status);
          });
          $('.my-loader').removeClass('active');
        }
      });
   
  });

  $("body").on('click', '.masraf-onayla', function(event){
    var thi = $(this);
    var a = $(this).attr('data-status');
    var id = $(this).attr('data-id');
    
    var r = confirm("Emin misiniz?");
    if (r == true) {
      $.ajax({
        type: "POST",
        url: "{{ route('update-cost-status') }}",
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
          toastr.success('Başarılıyla güncellediniz');
          if(a == "Onaylandı"){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">'+a+'</span>';
          }else if(a == "Reddedildi"){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">'+a+'</span>';
          }else if(a == "ödendi"){
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">'+a+'</span>';
          }else{
            var status = '<span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">'+a+'</span>';
          }

          thi.closest("tr").find('.status-side').html(status);
        }
      });
    }

        
  });
});
</script>
@endsection

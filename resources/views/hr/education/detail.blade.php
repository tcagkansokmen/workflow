{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
.nowrap{
  white-space:nowrap;
}
</style>
<div class="card card-custom gutter-b">
  <div class="card-body">
    <div class="d-flex">
      <!--begin: Pic-->
      <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
        <div class="symbol symbol-50 symbol-lg-120 symbol-light-danger">
          <span class="font-size-h1 symbol-label font-weight-boldest call-bdo-modal"
            data-size="medium" 
            data-url="{{ route('education-total', ['id' => $detail->id]) }}" 
            style="font-size:35px; flex-direction:column; cursor:pointer;">
              {{ $stars ? round($stars, 2) : 0.00 }}
            <span style="font-size:14px; font-weight:normal;">Başarı Oranı</span>
          </span>
        </div>
      </div>
      <!--end: Pic-->
      <!--begin: Info-->
      <div class="flex-grow-1">
        <!--begin: Title-->
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div class="mr-3">
            <!--begin::Name-->
            <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $detail->name }}
            <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
            <!--end::Name-->
            <!--begin::Contacts-->
            <div class="d-flex flex-wrap my-2">
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ $detail->category->name }} - {{ $detail->type }}
              </a>
              @if($detail->point)
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                KGK Kredisi: <span class="text-primary">{{ $detail->point }}</span>
              </a>
              @endif
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ $detail->category->name }} - {{ $detail->type }}
              </a>
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ $detail->foundation }}
              </a>
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                {{ $detail->address }}
              </a>
            </div>
            <!--end::Contacts-->
          </div>
          <div class="my-lg-0 my-1">
          </div>
        </div>
        <!--end: Title-->
        <!--begin: Content-->
        <div class="d-flex align-items-center flex-wrap justify-content-between">
          <div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5"></div>
          <div class="d-flex flex-wrap align-items-center py-2">
            <div class="d-flex align-items-center mr-10">
              <div class="mr-6">
                <div class="font-weight-bold mb-2">Başlangıç Tarihi</div>
                <span class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold">{{ \Carbon\Carbon::parse($detail->start_at)->formatLocalized('%d %B %Y') }}</span>
              </div>
              <div class="">
                <div class="font-weight-bold mb-2">Bitiş Tarihi</div>
                <span class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold">{{ \Carbon\Carbon::parse($detail->end_at)->formatLocalized('%d %B %Y') }}</span>
              </div>
            </div>
            @if(count($users))
            <div class="flex-grow-1 flex-shrink-0 w-150px w-xl-300px mt-4 mt-sm-0">
              <span class="font-weight-bold">Katılım</span>
              <div class="progress progress-xs mt-2 mb-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ round($katilan/count($users)*100) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <span class="font-weight-bolder text-dark">{{ round($katilan/count($users)*100, 2) }}%</span>
            </div>
            @endif
          </div>
        </div>
        <!--end: Content-->
      </div>
      <!--end: Info-->
    </div>
  </div>
</div>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">Eğitime Katılım Listesi
              <div class="text-muted pt-2 font-size-sm">Katıldı/katılmadı durumlarını değiştirebilirsiniz</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="teklif-listesi">
            <thead>
              <tr>
                <th>İsim</th>
                <th>Departman</th>
                <th>Katılım Durumu</th>
                <th>Değerlendirme</th>
                <th class="align-right">İşlem</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $d)
                <tr>
                  <td>
                    <div class="ml-4">
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->user->name ?? '' }} {{ $d->user->surname ?? '' }}</div>
                      <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ $d->user->title ?? '' }}</a>
                    </div>
                  </td>
                  <td>
                    {{ $d->user->department->name }}
                  </td>
                  <td>
                    @if($d->status == 'katıldı')
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-success">{{ $d->status }}</span>
                    @elseif($d->status == 'katılmadı')
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">{{ $d->status }}</span>
                    @else 
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-info">{{ $d->status }}</span>
                    @endif
                  </td>
                  <td>
                    <span class="btn btn-bold btn-sm btn-font-sm  btn-light-danger">{{ $d->rating ? $d->rating : 0 }}</span>
                  </td>
                  <td class="align-right">
                    @if($d->status=='bekleniyor')
                      @if($authenticated->power('education', 'durum'))
                        <a href="#" class="btn btn-sm btn-outline-success katilim-durumu" data-id="{{ $d->id }}" data-status="katıldı">
                          Katıldı
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-danger katilim-durumu" data-id="{{ $d->id }}" data-status="katılmadı">
                          Katılmadı
                        </a>
                      @endif
                    @elseif($d->status=='katıldı')
                        @if($authenticated->power('education', 'point'))
                        <a href="#"  
                        class="btn btn-sm btn-dark btn-icon btn-icon-md call-bdo-modal" 
                        data-size="medium" 
                        data-url="{{ route('education-rating-detail', ['id' => $d->user_id, 'education_id' => $d->education_id]) }}" 
                        ><i class="la la-search"></i></a>
                        @endif
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

  {{-- page scripts --}}
  <script>
    $("body").on('click', '.teklif-guncelle', function() {
        var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
        if (r == true) {
            var id = $(this).attr('data-id');
            var value = $(this).attr('data-value');
            $.ajax({
                url: "{{ route('update-notice-status') }}",
                data: "id=" + id + "&value=" + value,
                dataType: 'json',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function() {},
                beforeSubmit: function() {},
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
    $("body").on('click', '.teklif-guncelle-2', function() {
        var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
        if (r == true) {
            var id = $(this).attr('data-id');
            var value = $(this).attr('data-value');
            $.ajax({
                url: "/insan-kaynaklari/teklif/update-state",
                data: "id=" + id + "&value=" + value,
                dataType: 'json',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function() {},
                beforeSubmit: function() {},
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
  </script>
  <script>
  $('.select2-standard').select2({
  placeholder: "Seçiniz",
  allowClear: true,
  });
  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
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

$("body").on('click', '.katilim-durumu', function(e){
  e.preventDefault();
  var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
  if (r == true) {
    var id = $(this).attr('data-id');
    var value = $(this).attr('data-status');
      $.ajax({
        url: "{{ route('update-education-status') }}",
        data: "id=" + id + "&value=" + value,
        dataType: 'json',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(){
        },
        beforeSubmit: function(){
        },
        success: function(data){
          location.reload();
        }
      });
  }
});
</script>
@endsection

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
          <span class="font-size-h1 symbol-label font-weight-boldest" style="font-size:35px; flex-direction:column;">
            {{ count($detail->candidates) }}
            <span style="font-size:14px; font-weight:normal;">Başvuru</span>
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
            <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $detail->title }}
            <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
            <!--end::Name-->
            <!--begin::Contacts-->
            <div class="d-flex flex-wrap my-2">
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
              <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"></rect>
                    <path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"></path>
                    <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"></circle>
                  </g>
                </svg>
                <!--end::Svg Icon-->
              </span>{{ $detail->type }}</a>
              <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
              <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/General/Lock.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <mask fill="white">
                      <use xlink:href="#path-1"></use>
                    </mask>
                    <g></g>
                    <path d="M7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 Z M12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L15,10 L15,8 C15,6.34314575 13.6568542,5 12,5 Z" fill="#000000"></path>
                  </g>
                </svg>
                <!--end::Svg Icon-->
              </span>{{ $detail->position->title }}</a>
              <a href="#" class="text-muted text-hover-primary font-weight-bold">
              <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Map/Marker2.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"></rect>
                    <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"></path>
                  </g>
                </svg>
                <!--end::Svg Icon-->
              </span>{{ $detail->department->name }}</a>
            </div>
            <!--end::Contacts-->
          </div>
          <div class="my-lg-0 my-1">

          @if($authenticated->power('notice', 'delete'))
              @if($detail->status != 'kapatıldı' && $detail->status != 'iptal edildi') @if($detail->status == 'talep edildi')
                <a href="{{ route('update-notice', ['id' => $detail->id, 'ik' => 1]) }}" class="btn btn-outline-success  btn-sm btn-upper " data-id="{{ $detail->id }}" data-value="Kabul Edildi">İlan Oluştur</a>&nbsp; @endif 
              @if($detail->status == 'ilan yayınlandı')
                <button type="button" class="btn btn-outline-success  btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="başvurular tamamlandı">Başvuruları Tamamla</button>&nbsp;
                <button type="button" class="btn btn-outline-danger btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="iptal edildi"> İlanı Çek</button>&nbsp; 
              @endif 
              @if($detail->status == 'başvurular tamamlandı')
                <button type="button" class="btn btn-outline-warning btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="mülakat süreci">Mülakat Sürecini Başlat</button>&nbsp;
                <button type="button" class="btn btn-outline-danger btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="iptal edildi"> İlanı Çek</button>&nbsp; @endif @if($detail->status == 'mülakat süreci')
                <button type="button" class="btn btn-outline-success btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="kapatıldı">İlanı Kapat</button>&nbsp;
                <button type="button" class="btn btn-outline-danger btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="iptal edildi">İlanı Çek</button>&nbsp; 
              @endif 
            @endif
            @if($detail->status == 'kapatıldı')
              <button type="button" class="btn btn-outline-success  btn-sm btn-upper teklif-guncelle" data-id="{{ $detail->id }}" data-value="ilan yayınlandı">İlanı Yeniden Aç</button>&nbsp;
            @endif
          @endif

            <a href="#" class="btn btn-sm btn-light-success font-weight-bolder text-uppercase mr-3 d-none">Button</a>
            <a href="#" class="btn btn-sm btn-info font-weight-bolder text-uppercase d-none">Button</a>
          </div>
        </div>
        <!--end: Title-->
        <!--begin: Content-->
        <div class="d-flex align-items-center flex-wrap justify-content-between">
          <div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">İlana ait tüm detaylara ve ilan linkine aşağıdaki bağlantıdan ulaşabilirsiniz.<br>
          <a href="{{ $detail->url }}" target="_blank">İlan Bağlantısı için Tıklayın</a></div>
          <div class="d-flex flex-wrap align-items-center py-2">
            <div class="d-flex align-items-center mr-10">
              <div class="mr-6">
                <div class="font-weight-bold mb-2">Yayın Tarihi</div>
                <span class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold">{{ \Carbon\Carbon::parse($detail->notice_date)->formatLocalized('%d %B %Y') }}</span>
              </div>
              <div class="">
                <div class="font-weight-bold mb-2">Son Tarih</div>
                <span class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold">{{ \Carbon\Carbon::parse($detail->notice_end_date)->formatLocalized('%d %B %Y') }}</span>
              </div>
            </div>
            @if($toplam)
            <div class="flex-grow-1 flex-shrink-0 w-150px w-xl-300px mt-4 mt-sm-0">
              <span class="font-weight-bold">Olumlu Aday Durumu</span>
              <div class="progress progress-xs mt-2 mb-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $toplam_olumlu/$toplam*100 }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <span class="font-weight-bolder text-dark">{{ $toplam_olumlu/$toplam*100 }}%</span>
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
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
        @if($authenticated->power('candidate', 'add'))
          @if($detail->status != 'kapatıldı')
            <a href="{{ route('add-candidate', ['demand_id' => $detail->id]) }}" class="btn btn-light-info btn-icon-sm">Yeni Aday Ekle</a>
          @endif
        @endif
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable" id="teklif-listesi">
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
  var qrform = $('#teklif-listesi').DataTable({
      responsive: true,
      "processing": true,
      "serverSide": true,
      "ajax": "{{ route('single-notice-json', ['id' => $detail->id]) }}?{!! \Request::getQueryString() !!}",
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
          { data: 'id', name: 'id', title: '#', order: 'desc', visible: false },
          { data: 'name', name: 'name', title: 'Talep Eden', "render": function (data, type, row) {
              return '<div class="d-flex align-items-center" style="max-width:220px;">\
                <div class="symbol symbol-light-danger flex-shrink-0">\
                  ' + ( row.photo ? '<a href="#" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >\
                      <img src="{{ Storage::url("uploads/candidate") }}/'+row.photo+'" alt="image">\
                  </a>' : '<span class="symbol-label font-size-h5 kt-margin-r-5 t-5">\
                    <span>' + row.name.charAt(0) + row.surname.charAt(0) + '</span>\
                </span>' ) + '\
                </div>\
                <div class="ml-4">\
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.name + ' ' + row.surname + '</div>\
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.demand.position.title + "</a>\
                </div>\
                </div>"
              }
          },
          { data: 'email', name: 'email', title: 'İletişim', "render": function (data, type, row) {
                  return '<div class="d-flex align-items-center" style="max-width:220px;">\
                    <div class="ml-4">\
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + data + '</div>\
                      <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.phone + "</a>\
                    </div>\
                  </div>"
              },
          },
          { data: 'city', name: 'city', title: 'Mülakat', "render": function (data, type, row) {
              if(row.interviews.length){
                  return '<div class="d-flex align-items-center" style="max-width:220px;">\
                    <div class="ml-4">\
                      <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' + row.interviews[0].start_at + '</div>\
                      <a href="#" class="text-muted font-weight-bold text-hover-primary">' + row.interviews[0].end_at + "</a>\
                    </div>\
                  </div>"
              }else{
                return 'Belirlenmedi'
              }
                  
              },
          },
          { data: 'city', name: 'city', title: 'Mülakat Durumu', "render": function (data, type, row) {

            if(row.interviews.length){
              if(row.interviews[0].status=='olumlu_mulakat'){
                return '<span class="label label-lg font-weight-bold label-light-success label-inline">' + row.interviews[0].status + '</span>'
              }else if(row.interviews[0].status=='olumsuz_mulakat'){
                return '<span class="label label-lg font-weight-bold label-light-danger label-inline">' + row.interviews[0].status + '</span>'
              }else if(row.interviews[0].status=='mulakat_tarihi'){
                return '<span class="label label-lg font-weight-bold label-light-warning label-inline">' + row.interviews[0].status + '</span>'
              }else {
                return '<span class="label label-lg font-weight-bold label-light-primary label-inline">' + row.interviews[0].status + '</span>'
              }
            }
            return '<span class="label label-lg font-weight-bold label-light-dark label-inline">Mülakat Yok</span>'
            },
          },
          { data: 'cv', name: 'cv', title: 'CV', "render": function (data, type, row) {

              if(row.cv){
                return '<a target="_blank" href="{{ Storage::url("uploads/candidate") }}/'+row.cv+'" class="label label-lg font-weight-bold label-light-dark label-inline">Görüntüle</a>'
              }
              return ''
              },
          },
          { data: 'id', name: 'id', title: 'İşlem', "render": function(data, type, row) {
              var editme = '<a href="{{ route("update-candidate") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-info btn-sm" data-toggle="tooltip" data-theme="light" title="Adayı Düzenle">\
              <i class="flaticon2-sheet icon-md text-info"></i>\
              </a>&nbsp;';

              var detailme = '<a href="{{ route("candidate-detail") }}/'+data+'" class="btn btn btn-icon btn-light btn-hover-warning btn-sm" data-toggle="tooltip" data-theme="light" title="Detay"><i class="fas fa-chart-line icon-md text-warning"></i></a>&nbsp;';

              var result = row.detail_allowed ? detailme : '';
              result += row.edit_allowed ? editme : '';

              return '<div style="white-space:nowrap">'+result+'</div>';
          }
        },
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
@endsection

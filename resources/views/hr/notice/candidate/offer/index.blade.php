{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="container">
  <!--begin::Profile Overview-->
  <div class="d-flex flex-row">
    <!--begin::Aside-->
      @include('hr.notice.candidate.left')
    <!--end::Aside-->
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
      <!--begin::Advance Table: Widget 7-->
      <div class="card card-custom">
        <div class="card-header flex-wrap pt-2 pb-2">
            <div class="card-title">
                <h3 class="card-label">Teklifler
                </h3>
            </div>
            <div class="card-toolbar">
              @if($authenticated->power('candidate', 'add'))
                  @if(aday_step($detail->status) < 5 || $detail->status == 'teklif_red')
                      <a href="{{ route('add-candidate-offer', ['candidate_id' => $detail->id]) }}" class="btn btn-success">Yeni Teklif Ekle</a>
                  @endif
              @endif
            </div>
        </div>
        <div class="card-body">
          <div class="timeline timeline-3">
            <div class="timeline-items">
              @foreach($offers as $a)
                <div class="timeline-item">
                  <div class="timeline-media">
                    <i class="{{ aday_icon($a->status) }} text-{{ aday_class($a->status) }}"></i>
                  </div>
                  <div class="timeline-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class="mr-2">
                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">
                        {{ date('d M Y', strtotime($a->offer_date)) }} Tarihli Teklif
                        </a>
                        <span class="label label-light-{{ aday_class($a->new_status) }} font-weight-bolder label-inline ml-2">
                        {{ aday_statu($a->status) }}
                        </span>
                      </div>
                      @if($loop->last && aday_step($a->status) < 6)
                      <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="İşlemler">
                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="ki ki-more-hor font-size-lg text-primary"></i>
                        </a>
                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                          <!--begin::Navigation-->
                          <ul class="navi navi-hover">
                              @if(aday_step($a->status) < 5)
                              <li class="navi-item">
                                  <a href="{{ route('update-candidate-offer', ['id' => $a->id]) }}" class="navi-link">
                                      <i class="navi-link-icon flaticon-edit mr-5"></i>
                                      <span class="navi-text">Düzenle</span>
                                  </a>
                              </li>
                              @endif
                              <li class="navi-item">
                                  <a
                                  href="#"
                                  data-id="{{ $a->id }}"
                                  data-value="teklif_kabul"
                                  class="navi-link teklif-guncelle-2">
                                      <i class="navi-link-icon flaticon2-check-mark mr-5"></i>
                                      <span class="navi-text">Teklif Onaylandı</span>
                                  </a>
                              </li>
                              <li class="navi-item">
                                  <a
                                  href="#"
                                  data-id="{{ $a->id }}"
                                  data-value="teklif_red"
                                  class="navi-link teklif-guncelle-2">
                                      <i class="navi-link-icon flaticon-close mr-5"></i>
                                      <span class="navi-text">Teklif Reddedildi</span>
                                  </a>
                              </li>
                          </ul>
                          <!--end::Navigation-->
                        </div>
                      </div>
                        @endif

                    </div>
                    <p>
                      @if(aday_step($a->status) > 2)
                      <span class="label label-light-primary label-inline font-weight-lighter mr-2">{{ money_formatter($a->price) }} TL</span>
                      @endif
                      {{ $a->notes }}
                    </p>
                  </div>
                </div>
              @endforeach

                @if(!count($offers))
                <p>Adaya ait teklif bilgisi bulunamadı. Sağ üstteki "Yeni Teklif Ekle" tuşuna basarak bir teklif belirleyebilirsiniz.</p>
                @endif

            </div>
          </div>
        </div>
        <!--end::Body-->
      </div>
      <!--end::Advance Table Widget 7-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Profile Overview-->
  </div>

  @endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
  <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
  <script>
  
  $("body").on('click', '.teklif-guncelle-2', function() {
      var r = confirm("Emin misiniz? Bunun geri dönüşü yok!");
      if (r == true) {
          var id = $(this).attr('data-id');
          var value = $(this).attr('data-value');
          $.ajax({
              url: "{{ route('update-candidate-offer-status') }}",
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
@endsection

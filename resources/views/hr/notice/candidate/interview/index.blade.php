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
                <h3 class="card-label">Mülakatlar
                </h3>
            </div>
            <div class="card-toolbar">
              @if($authenticated->power('candidate', 'add'))
                  @if(aday_step($detail->status) < 6)
                      <a href="{{ route('add-interview', ['candidate_id' => $detail->id]) }}" class="btn btn-success">Yeni Mülakat Ekle</a>
                  @endif
              @endif
            </div>
        </div>
        <div class="card-body">
          <div class="timeline timeline-3">
            <div class="timeline-items">
              @foreach($interview as $a)
                <div class="timeline-item">
                  <div class="timeline-media">
                    <i class="{{ aday_icon($a->status) }} text-{{ aday_class($a->status) }}"></i>
                  </div>
                  <div class="timeline-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class="mr-2">
                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">
                        {{ count($interview)-$loop->iteration+1 }}. Mülakat
                        </a>
                        <span class="text-muted ml-2">{{ date('d M Y H:i', strtotime($a->start_at)) }}</span>
                        <span class="label label-light-{{ aday_class($a->new_status) }} font-weight-bolder label-inline ml-2">
                        {{ aday_statu($a->status) }}
                        </span>
                      </div>
                      <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="İşlemler">
                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="ki ki-more-hor font-size-lg text-primary"></i>
                        </a>
                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                          <!--begin::Navigation-->
                          <ul class="navi navi-hover">
                              <li class="navi-item">
                                <a href="{{ route('update-interview', ['id' => $a->id]) }}" class="navi-link">
                                  <span class="navi-text">
                                    <span class="">Düzenle</span>
                                  </span>
                                </a>
                              </li>
                              <li class="navi-item">
                                <a href="{{ route('rate-interview', ['id' => $a->id]) }}" class="navi-link">
                                  <span class="navi-text">
                                    <span class="">Değerlendir</span>
                                  </span>
                                </a>
                              </li>
                          </ul>
                          <!--end::Navigation-->
                        </div>
                      </div>

                    </div>
                    <p>
                      @if(aday_step($a->status) > 2)
                        <div class="symbol symbol-light-danger mr-3">
                            <span class="symbol-label font-size-h5">{{ isset($a->avgRating->aggregate) ? round($a->avgRating->aggregate, 1) : '0.0' }}</span>
                        </div>
                      @endif
                      {{ $a->notes }}
                    </p>
                        @if(count($a->perfections))
                        <hr>
                        <div class="timeline-v1__item-body padding-t-10 padding-b-10">
                            <div class="timeline-v1__item-title">
                                <h5>Yetkinlikler</h5>
                            </div>
                            <p>Mülakata ait notlara erişmek için imleci puanın üstüne getirin.</p>
                            <div class="widget4">
                                @foreach($a->perfections as $d)
                                <div class="widget4__item d-flex mb-5" style="align-items:center;">
                                  <div class="symbol symbol-light-danger mr-3">
                                      <span class="symbol-label font-size-h5">{{ round($d->rating, 1) }}</span>
                                  </div>
                                  <span style="font-weight:bold;">{{ $d->perfection->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(count($a->documents))
                        <hr>
                        <div class="timeline-v1__item-body padding-t-10 padding-b-10">
                            <div class="widget4">
                                @foreach($a->documents as $d)
                                <div class="widget4__item d-flex mb-5" style="align-items:center;">
                                  <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                      <span class="svg-icon svg-icon-xl svg-icon-primary">
                                        <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Home/Library.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                          </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                      </span>
                                    </span>
                                  </div>
                                    <a style="font-weight:bold;" href="{{ Storage::url('uploads/candidate') }}/{{ $d->file }}" target="_blank" class="widget4__title">{{ $d->title }} </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          @if(!count($interview))
          <p>Adaya ait mülakat bilgisi bulunamadı. Sağ üstteki "Yeni Mülakat Ekle" tuşuna basarak bir mülakat tarihi belirleyebilirsiniz.</p>
          @endif
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
@endsection

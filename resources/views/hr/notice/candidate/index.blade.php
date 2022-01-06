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
        <div class="card-body">
          <div class="timeline timeline-3">
            <div class="timeline-items">
                @foreach($logs as $l)
                  <div class="timeline-item">
                    <div class="timeline-media">
                      <i class="flaticon2-notification fl text-primary"></i>
                    </div>
                    <div class="timeline-content">
                      <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="mr-2">
                          <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">
                            @if($l->interview)
                              Mülakat
                            @elseif($l->offer)
                              Teklif
                            @else 
                              Bildirim
                            @endif
                          </a>
                          <span class="text-muted ml-2">{{ date('d,M H:i', strtotime($l['created_at'])) }}</span>
                          <span class="label label-light-{{ aday_class($l->new_status) }} font-weight-bolder label-inline ml-2">{{ aday_statu($l->new_status) }}</span>
                        </div>
                      </div>
                      <p>
                        @if(aday_step($l->new_status) == 1)
                        Adaya ait kayıt <strong>{{ $l->user->name }} {{ $l->user->surname }}</strong> tarafından oluşturuldu.
                        @elseif(aday_step($l->new_status) == 2)
                        <strong>{{ date('d M Y', strtotime($l->interview->start_at)) }}</strong> günü saat <strong>{{ date('H:i', strtotime($l->interview->start_at)) }}</strong> için mülakat randevusu oluşturuldu.
                        @elseif(aday_step($l->new_status) == 3)
                        Gerçekleştirilen mülakat {{ $l->user->title }} {{ $l->user->name }} {{ $l->user->surname }} tarafından gerçekleştirilmiştir. Mülakata ilişkin detaylar için <a href="{{ route('rate-interview', ['id' => $l->interview->id]) }}" style="font-weight:bold; text-decoration:underline;">tıklayınız.</a>
                        @elseif(aday_step($l->new_status) == 4)
                        Adaya, <strong>{{ $l->user->name }} {{ $l->user->surname }}</strong> tarafından {{ $l->offer->price }} TL teklif gönderildi.
                        @elseif(aday_step($l->new_status) == 5)
                        Verilen teklif aday tarafından yanıtlandı.
                        @elseif(aday_step($l->new_status) == 6 && $l->new_status == "kabul_edildi")
                        Adayın iş başvuru süreci <strong>{{ $l->user->name }} {{ $l->user->surname }}</strong> tarafından <strong>olumlu</strong> tamamlanmıştır.
                        @elseif(aday_step($l->new_status) == 6 && $l->new_status == "Reddedildi")
                        Adayın başvurusu, <strong>{{ $l->user->name }} {{ $l->user->surname }}</strong> tarafından <strong>olumsuz</strong> sonuçlanmıştır.
                        @endif
                      </p>
                    </div>
                  </div>
                @endforeach
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
@endsection

@extends('hr.candidate.detail')

@include('common.subheader', 
  [
    'title' => 'Başlık', 
    'subtitle' => 'Altbaşlık',
    'url' => route('ilan-detay', ['id' => $detail->demand_id])
  ]
)

@section('inside')
<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">Aday Bilgileri <small>personele ait hesap bilgilerini görütnüleyebilirsiniz.</small></h3>
          </div>
          <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
            </div>
          </div>
        </div>
           <div class="card-body">
            <div class="row">
              <div class="col-xl-1">
              </div>
              <div class="col-xl-10">
                <div class="kt-timeline-v1 kt-timeline-v1--justified">
                  <div class="kt-timeline-v1__items">
                    <div class="kt-timeline-v1__marker"></div>
                    @foreach($logs as $l)
                    <div class="kt-timeline-v1__item 
                    @if($loop->first)
                      kt-timeline-v1__item--first
                    @endif">
                      <div class="kt-timeline-v1__item-circle">
                        <div class="kt-bg-danger"></div>
                      </div>
                      <span class="kt-timeline-v1__item-time kt-font-brand" style="width:220px;">
                        {{ date('d,M H:i', strtotime($l['created_at'])) }}
                      </span>
                      <div class="kt-timeline-v1__item-content">
                        <div class="kt-timeline-v1__item-title" style="display:flex; justify-content:space-between">
                          @if($l->interview)
                            Mülakat
                          @elseif($l->offer)
                            Teklif
                          @else 
                            Bildirim
                          @endif
                          <span class="kt-badge kt-badge--{{ aday_class($l->new_status) }} kt-badge--inline">
                            {{ aday_statu($l->new_status) }}
                          </span>
                        </div>
                        <div class="kt-timeline-v1__item-body">
                          <p>
                            @if(aday_step($l->new_status) == 1)
                            Adaya ait kayıt <strong>{{ $l->user->name }} {{ $l->user->surname }}</strong> tarafından oluşturuldu.
                            @elseif(aday_step($l->new_status) == 2)
                            <strong>{{ date('d M Y', strtotime($l->interview->start_at)) }}</strong> günü saat <strong>{{ date('H:i', strtotime($l->interview->start_at)) }}</strong> için mülakat randevusu oluşturuldu.
                            @elseif(aday_step($l->new_status) == 3)
                            Gerçekleştirilen mülakat {{ $l->user->title }} {{ $l->user->name }} {{ $l->user->surname }} tarafından gerçekleştirilmiştir. Mülakata ilişkin detaylar için <a href="{{ route('mulakat-degerlendir', ['id' => $l->interview->id]) }}" style="font-weight:bold; text-decoration:underline;">tıklayınız.</a>
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
                        <div class="kt-timeline-v1__item-actions">
                          <button type="button" class="btn btn-{{ aday_class($l->status) }} btn-sm"><span class="{{ aday_icon($l->status) }}"></span> {{ aday_statu($l->status) }}</button>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <div class="row">
                  <div class="col kt-align-center">
                  </div>
                </div>
              </div>
              <div class="col-xl-1">
              </div>
            </div>
          </div>
          <div class="kt-portlet__foot">
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
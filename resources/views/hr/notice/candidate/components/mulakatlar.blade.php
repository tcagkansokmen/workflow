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
            @if($authenticated->power('candidate', 'mulakat'))
                @if(aday_step($detail->status) < 6)
                    <a href="{{ route('mulakat-ekle', ['candidate_id' => $detail->id]) }}" class="btn btn-success">Yeni Mülakat Ekle</a>
                @endif
            @endif
            </div>
          </div>
        </div>
           <div class="card-body">
            <div class="kt-notes">
                <div class="kt-notes__items">
                @foreach($interview as $a)
                    <div class="kt-notes__item">
                        <div class="kt-notes__media">
                            <span class="kt-notes__icon">
                              <i class="{{ aday_icon($a->status) }} kt-font-{{ aday_class($a->status) }}"></i>
                            </span>
                        </div>
                        <div class="kt-notes__content">
                            <div class="kt-notes__section">
                                <div class="kt-notes__info" style="width:100%;">
                                    <a href="#" class="kt-notes__title">{{ count($interview)-$loop->iteration+1 }}. Mülakat</a>
                                    <span class="kt-notes__desc">{{ date('d M Y H:i', strtotime($a->start_at)) }}</span>
                                    <span class="kt-badge kt-badge--{{ aday_class($a->status) }} kt-badge--inline" style="float:right;">
                                        {{ aday_statu($a->status) }}</span>
                                </div>
                                <div class="kt-notes__dropdown">
                                    @if(aday_step($detail->status) < 4)
                                    <a href="#" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a href="{{ route('mulakat-duzenle', ['id' => $a->id]) }}" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon-edit"></i>
                                                    <span class="kt-nav__link-text">Düzenle</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="{{ route('mulakat-degerlendir', ['id' => $a->id]) }}" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                    <span class="kt-nav__link-text">Değerlendir</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <span class="kt-notes__body">
                                @if(aday_step($a->status) > 2)
                                <span class="kt-media kt-media--md  kt-media--info kt-margin-r-5 kt-margin-t-5" > 
                                    <span style="font-size:22px;">{{ isset($a->avgRating->aggregate) ? round($a->avgRating->aggregate, 1) : '0.0' }}</span>
                                </span>
                                @endif
                                {{ $a->notes }}
                            </span>
                            @if(count($a->perfections))
                            <hr>
                            <div class="kt-timeline-v1__item-body kt-padding-t-10 kt-padding-b-10">
                                <div class="kt-timeline-v1__item-title">
                                    <h5>Yetkinlikler</h5>
                                </div>
                                <p>Mülakata ait notlara erişmek için imleci puanın üstüne getirin.</p>
                                <div class="kt-widget4">
                                    @foreach($a->perfections as $d)
                                    <div class="kt-widget4__item">
                                        <span class="kt-media kt-media--sm  kt-media--danger kt-margin-r-5 kt-margin-t-5"
                                        data-toggle="kt-tooltip" 
                                        data-placement="top"
                                        data-original-title="{{ $d->notes }}"
                                        style="cursor:pointer;"
                                        > 
                                            <span>{{ round($d->rating, 1) }}</span>
                                        </span>
                                        <a class="kt-widget4__title">{{ $d->perfection->name }} </a>
                                        <div class="kt-widget4__tools">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if(count($a->documents))
                            <hr>
                            <div class="kt-timeline-v1__item-body kt-padding-t-10 kt-padding-b-10">
                                <div class="kt-widget4">
                                    @foreach($a->documents as $d)
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__pic kt-widget4__pic--icon">
                                            <img src="/assets/media/files/doc.svg" alt="">
                                        </div>
                                        <a href="{{ Storage::url('uploads/candidate') }}/{{ $d->file }}" target="_blank" class="kt-widget4__title">{{ $d->title }} </a>
                                        <div class="kt-widget4__tools">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if(!count($interview))
                <p>Adaya ait mülakat bilgisi bulunamadı. Sağ üstteki "Yeni Mülakat Ekle" tuşuna basarak bir mülakat tarihi belirleyebilirsiniz.</p>
                @endif
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
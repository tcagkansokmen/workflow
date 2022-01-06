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
            @if($authenticated->power('candidate', 'teklif'))
                @if(aday_step($detail->status) < 5 || $detail->status == 'teklif_red')
                    <a href="{{ route('aday-teklif-ekle', ['candidate_id' => $detail->id]) }}" class="btn btn-success">Yeni Teklif Ekle</a>
                @endif
            @endif
            </div>
          </div>
        </div>
           <div class="card-body">
            <div class="kt-notes">
                <div class="kt-notes__items">
                @foreach($offers as $a)
                    <div class="kt-notes__item">
                        <div class="kt-notes__media">
                            <span class="kt-notes__icon">
                              <i class="{{ aday_icon($a->status) }} kt-font-{{ aday_class($a->status) }}"></i>
                            </span>
                        </div>
                        <div class="kt-notes__content">
                            <div class="kt-notes__section">
                                <div class="kt-notes__info">
                                    <a href="#" class="kt-notes__title">{{ date('d M Y', strtotime($a->offer_date)) }} Tarihli Teklif</a>
                                    <span class="kt-notes__desc"></span>
                                    <span class="kt-badge kt-badge--{{ aday_class($a->status) }} kt-badge--inline">{{ aday_statu($a->status) }}</span>
                                </div>
                                <div class="kt-notes__dropdown">
                                    @if($loop->last && aday_step($a->status) < 6)
                                    <a href="#" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <ul class="kt-nav">
                                            @if(aday_step($a->status) < 5)
                                            <li class="kt-nav__item">
                                                <a href="{{ route('aday-teklif-duzenle', ['id' => $a->id]) }}" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon-edit"></i>
                                                    <span class="kt-nav__link-text">Düzenle</span>
                                                </a>
                                            </li>
                                            @endif
                                            <li class="kt-nav__item">
                                                <a
                                                data-id="{{ $a->id }}"
                                                data-value="teklif_kabul"
                                                class="kt-nav__link teklif-guncelle-2">
                                                    <i class="kt-nav__link-icon flaticon2-check-mark"></i>
                                                    <span class="kt-nav__link-text">Teklif Onaylandı</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a
                                                data-id="{{ $a->id }}"
                                                data-value="teklif_red"
                                                class="kt-nav__link teklif-guncelle-2">
                                                    <i class="kt-nav__link-icon flaticon-close"></i>
                                                    <span class="kt-nav__link-text">Teklif Reddedildi</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <span class="kt-notes__body">
                              <span class="btn btn-bold btn-sm btn-label-info" style="margin-bottom:0;">{{ number_format($a->price, 0, ",", ".") }} TL</span>
                                {{ $a->notes }}
                            </span>
                        </div>
                    </div>
                @endforeach

                @if(!count($offers))
                <p>Adaya ait teklif bilgisi bulunamadı. Sağ üstteki "Yeni Teklif Ekle" tuşuna basarak bir teklif belirleyebilirsiniz.</p>
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
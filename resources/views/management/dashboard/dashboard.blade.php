{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <div class="row">
        <div class="col-md-4 col-sm-4">
            <div class="card card-custom gutter-b">
                <!--begin::Header-->
                <div class="card-header flex-wrap py-3">
                    <div class="card-title">
                        <h3 class="card-label">Satış Grafiği
                            <span class="d-block text-muted pt-2 font-size-sm">{{ $tarih_araligi }}</h3>
                    </div>
                    <div class="card-toolbar">
                    {{ money_formatter($total_sales_uc_aylik ?? 0) }}TL
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column p-0">
                    <!--begin::Chart-->
                    <div class="flex-grow-1">
                        <div id="aylik_sayis_grafigi" class="card-rounded-bottom" style="height: 150px"></div>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Items-->
                    <div class="mt-5 p-3">
                        @foreach($aylik_uc_firma as $af)
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center mr-2">
                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="symbol-label">
                                    @if($af->customer->logo)
                                        <img src="{{ Storage::url('uploads/company/') }}{{ $af->customer->logo }}" style="object-fit:contain;" class="h-50" alt="" />
                                    @else 
                                        {{ substr($af->customer->title, 0, 1) }}
                                    @endif
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">{{ $af->customer->code }}</a>
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="font-size-sm d-block text-muted font-weight-bold mt-1">{{ $af->customer->title }}</a>
                                </div>
                            </div>
                            <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">{{ money_formatter($af->total) }}TL</div>
                        </div>
                        @endforeach
                    </div>
                    <!--end::Widget Items-->
                </div>
                <!--end::Body-->
            </div>
            <div class="card card-custom gutter-b">
                <!--begin::Header-->
                <div class="card-header flex-wrap py-3">
                    <div class="card-title">
                        <h3 class="card-label">Yıllık Satış Grafiği
                            <span class="d-block text-muted pt-2 font-size-sm">{{ date('Y') }} yılına ait satış grafikleri</h3>
                    </div>
                    <div class="card-toolbar">
                    {{ money_formatter($total_sales ?? 0) }}TL
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column p-0">
                    <!--begin::Chart-->
                    <div class="flex-grow-1">
                        <div id="yillik_sayis_grafigi" class="card-rounded-bottom" style="height: 150px"></div>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Items-->
                    <div class="mt-5 p-3">
                        @foreach($yillik_uc_firma as $af)
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center mr-2">
                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="symbol-label">
                                    @if($af->customer->logo)
                                        <img src="{{ Storage::url('uploads/company/') }}{{ $af->customer->logo }}" style="object-fit:contain;" class="h-50" alt="" />
                                    @else 
                                        {{ substr($af->customer->title, 0, 1) }}
                                    @endif
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">{{ $af->customer->code }}</a>
                                    <a href="{{ route('bills', ['customer_id' => $af->customer->id]) }}" class="d-block font-size-sm text-muted font-weight-bold mt-1">{{ $af->customer->title }}</a>
                                </div>
                            </div>
                            <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">{{ money_formatter($af->total) }}TL</div>
                        </div>
                        @endforeach
                    </div>
                    <!--end::Widget Items-->
                </div>
                <!--end::Body-->
            </div>
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-3">
                    <div class="card-title">
                        <h3 class="card-label">Senelik Satış Grafiği
                            <span class="d-block text-muted pt-2 font-size-sm">{{ date('Y') }} yılına ait satış grafikleri</h3>
                    </div>
                    <div class="card-toolbar">
                    {{ money_formatter($total_sales ?? 0) }}TL
                    </div>
                </div>
                <!--begin::Body-->
                <div class="card-body d-flex flex-column p-0">
                    <div class="flex-grow-1">
                        <div id="yillik_satis_genel" class="card-rounded-bottom" style="height: 150px"></div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <div class="card card-custom gutter-b">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5" style="flex-direction:column">
                    <h3 class="card-title font-weight-bolder">MT Satış Performansı</h3>
                    <span class="text-muted font-weight-bold mt-2">{{ date('Y') }} yılına ait satış grafikleri</span>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column p-0">
                    <div class="flex-grow-1">
                        <div id="yillik_mt_genel" class="card-rounded-bottom" style="height: 150px"></div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ route('users') }}">
                        <div class="card card-custom bg-success gutter-b" style="height: 150px">
                            <div class="card-body">
                                {{ Metronic::getSVG("media/svg/icons/Layout/Layout-4-blocks.svg", "svg-icon svg-icon-3x svg-icon-white ml-n2") }}
                                <div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">{{ $users }}</div>
                                <span class="text-inverse-success font-weight-bold font-size-sm mt-1" style="white-space:nowrap;">Şirket Çalışanı</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('vehicles') }}">
                        <div class="card card-custom bg-success gutter-b" style="height: 150px">
                            <div class="card-body">
                                {{ Metronic::getSVG("media/svg/icons/Layout/Layout-4-blocks.svg", "svg-icon svg-icon-3x svg-icon-white ml-n2") }}
                                <div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">{{ $vehicles }}</div>
                                <span class="text-inverse-success font-weight-bold font-size-sm mt-1" style="white-space:nowrap;">Şirket Aracı</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card card-custom bg-light-success gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-success font-weight-bold">Çek Tahsilatları</div>
                                <div class="text-success font-weight-boldest font-size-h5">{{ money_formatter($received_cheque) }} TL</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-success font-size-sm">{{ $tarih_araligi }}</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card card-custom bg-light-danger gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-danger font-weight-bold">Çek Ödemeleri</div>
                                <div class="text-danger font-weight-boldest font-size-h5">{{ money_formatter($send_cheque) }} TL</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-danger font-size-sm">{{ $tarih_araligi }}</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card card-custom bg-light-primary gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-primary font-weight-bold">Yeni Müşteri</div>
                                <div class="text-primary font-weight-boldest font-size-h3">{{ $customers }} adet</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-primary font-size-sm">{{ $tarih_araligi }}</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('cost-waiting-payment') }}">
                        <div class="card card-custom bg-light-info gutter-b" style="height: 130px">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <!--begin::Stats-->
                                <div class="flex-grow-1 card-spacer-x pt-6">
                                    <div class="text-info font-weight-bold">Masraf Kapama</div>
                                    <div class="text-info font-weight-boldest font-size-h3">{{ $authenticated->bekleyenMasraflar() }} adet</div>
                                </div>
                                <!--end::Stats-->
                                <div class="card-spacer-x pt-0 pb-6">
                                    <span class="text-info font-size-sm">Onay Bekleyen</span>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-3">
                    <div class="card card-custom bg-white gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-inverse-white font-weight-bold">Günlük Ödemeler</div>
                                <div class="text-inverse-white font-weight-boldest font-size-h5">{{ money_formatter($gunluk_checque) }} TL</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-inverse-white font-size-sm">Bugün ödenmesi gereken tutar</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card card-custom bg-white gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-inverse-white font-weight-bold">Bekleyen Alacaklar</div>
                                <div class="text-inverse-white font-weight-boldest font-size-h5">{{ money_formatter($toplam_alacak) }} TL</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-inverse-white font-size-sm">Tüm zamanlara ait</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-sm-3">
                <a href="{{ route('offers', ['waiting' => 1]) }}">
                    <div class="card card-custom bg-warning gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-inverse-warning font-weight-bold">Teklif Onayı</div>
                                <div class="text-inverse-warning font-weight-boldest font-size-h3">{{ $authenticated->offerApproval() }}</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-inverse-warning font-size-sm">Yönetici onayı bekleyen teklifler</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </a>
                </div>
                <div class="col-sm-3">
                <a href="{{ route('bills', ['waiting' => 1]) }}">
                    <div class="card card-custom bg-dark gutter-b" style="height: 130px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column p-0">
                            <!--begin::Stats-->
                            <div class="flex-grow-1 card-spacer-x pt-6">
                                <div class="text-inverse-dark font-weight-bold">Fatura Onayı</div>
                                <div class="text-inverse-dark font-weight-boldest font-size-h3">{{ $authenticated->billApproval() }}</div>
                            </div>
                            <!--end::Stats-->
                            <div class="card-spacer-x pt-0 pb-6">
                                <span class="text-inverse-dark font-size-sm">Yönetici onayı bekleyen faturalar</span>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </a>
                </div>
            </div>

            <div class="row ">
                <div class="col-sm-2">
                    <a href="{{ route('permision-waiting-approval') }}">
                        <div class="card card-custom card-stretch bg-success" style="height: 125px">
                            <div class="card-body">
                                {{ Metronic::getSVG("media/svg/icons/Layout/Layout-4-blocks.svg", "svg-icon svg-icon-2x svg-icon-white ml-n2") }}
                                <div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">{{ $authenticated->izinTalepleri() }}</div>
                                <span class="text-inverse-success font-weight-bold font-size-sm mt-1" style="white-space:nowrap;">İzin Talebi</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('earnest-waiting-approval') }}">
                        <div class="card card-custom card-stretch bg-success" style="height: 125px">
                            <div class="card-body">
                                {{ Metronic::getSVG("media/svg/icons/Layout/Layout-4-blocks.svg", "svg-icon svg-icon-2x svg-icon-white ml-n2") }}
                                <div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">{{ $authenticated->avansTalepleri() }}</div>
                                <span class="text-inverse-success font-weight-bold font-size-sm mt-1" style="white-space:nowrap;">Masraf Talebi</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('need-waiting-approval') }}">
                        <div class="card card-custom card-stretch bg-success" style="height: 125px">
                            <div class="card-body">
                                {{ Metronic::getSVG("media/svg/icons/Layout/Layout-4-blocks.svg", "svg-icon svg-icon-2x svg-icon-white ml-n2") }}
                                <div class="text-inverse-success font-weight-bolder font-size-h2 mt-3">{{ $authenticated->bekleyenIhtiyaclar() }}</div>
                                <span class="text-inverse-success font-weight-bold font-size-sm mt-1" style="white-space:nowrap;">İhtiyaç Talebi</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('purchases', ['waiting' => 1])  }}">
                        <div class="card card-custom bg-primary card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <!--begin::Stats-->
                                <div class="flex-grow-1 card-spacer-x pt-6">
                                    <div class="text-inverse-primary font-weight-bold">Satın Alma Talepleri</div>
                                    <div class="text-inverse-primary font-weight-boldest font-size-h3">{{ $authenticated->purchaseApproval() }}</div>
                                </div>
                                <!--end::Stats-->
                                <div class="card-spacer-x pt-0 pb-6">
                                    <span class="text-inverse-primary font-size-sm">Onay bekleyen</span>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                    </a>
                </div>

                <div class="col-sm-3">
                    <a href="{{ route('expenses', ['waiting' => 1])  }}">
                        <div class="card card-custom bg-danger card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <!--begin::Stats-->
                                <div class="flex-grow-1 card-spacer-x pt-6">
                                    <div class="text-inverse-danger font-weight-bold">Tedarikçi Faturaları</div>
                                    <div class="text-inverse-danger font-weight-boldest font-size-h3">{{ $expense_total->adet }}</div>
                                </div>
                                <!--end::Stats-->
                                <div class="card-spacer-x pt-0 pb-6">
                                    <span class="text-inverse-danger font-size-sm">Onay bekleyen tedarikçi faturaları</span>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Keşif Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $exploration_total }} adet</span>
                                </div>
                            </div>
                            <div id="exploration_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Üretim Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $production_total }} adet</span>
                                </div>
                            </div>
                            <div id="production_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Montaj Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $assembly_total }} adet</span>
                                </div>
                            </div>
                            <div id="assembly_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Baskı Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $printing_total }} adet</span>
                                </div>
                            </div>
                            <div id="printing_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ew -->
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Brief Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $brief_total }} adet</span>
                                </div>
                            </div>
                            <div id="brief_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-xxl-6 col-md-6 col-sm-6">
                    <div class="card card-custom card-stretch {{ @$class }} gutter-b">
                        {{-- Body --}}
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex card-spacer flex-grow-1">
                                <div class="d-flex flex-column mr-2">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Teklif Durumu</a>
                                    <span class="text-muted font-weight-bold mt-2">{{ Request::get('start_at') ? $tarih_araligi : 'Tüm Zamanlar' }}</span>
                                </div>
                                <div class="d-flex flex-column text-right">
                                    <span class="text-dark-75 font-weight-bolder font-size-h5">{{ $offer_total }} adet</span>
                                </div>
                            </div>
                            <div id="offer_status" class="card-rounded-bottom"  style="height: 150px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
            <div class="col-sm-12">
                <h5 class="mt-5 mb-5">En yüksek ciro yapılan müşteriler</h5>
            </div>
                @foreach($yillik_uc_firma as $yu)
                    <div class="col-xl-4">
                        <!--begin::Mixed Widget 4-->
                        <div class="card card-custom bg-radial-gradient-danger gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 py-5">
                                <h3 class="card-title font-weight-bolder text-white">{{ $yu->customer->title }}</h3>
                                <div class="card-toolbar">
                                    <div class="dropdown dropdown-inline">
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <!--begin::Chart-->
                                <div data-id="{{ $yu->customer->id }}" id="customer_{{ $loop->index }}" style="height: 200px"></div>
                                <!--end::Chart-->
                                <!--begin::Stats-->
                                <div class="p-3 bg-white card-rounded flex-grow-1">
                                    <!--begin::Row-->
                                    <div class="row m-0 mb-5">
                                        <div class="col-6 ">
                                            <div class="font-size-sm text-muted font-weight-bold">Aylık Satış</div>
                                            @foreach($aylik_uc_firma as $au)
                                                @if($au->customer_id==$yu->customer_id)
                                            <div class="font-size-md font-weight-bolder">{{ money_formatter($au->total) }} TL</div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-6 6">
                                            <div class="font-size-sm text-muted font-weight-bold">Bekleyen</div>
                                            <div class="font-size-md font-weight-bolder">{{ money_formatter($yu->customer->not_paid->sum('price')) }} TL</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col-6 ">
                                            <div class="font-size-sm text-muted font-weight-bold">Yıllık Satış</div>
                                            <div class="font-size-md font-weight-bolder">{{ money_formatter($yu->total) }} TL</div>
                                        </div>
                                        <div class="col-6 6">
                                            <div class="font-size-sm text-muted font-weight-bold">Toplam Fatura</div>
                                            <div class="font-size-md font-weight-bolder">{{ $yu->customer->active_bills->count() }} adet</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Mixed Widget 4-->
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/config.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
    <script>
    function turkishDate(date){
        var arr = []
        arr['1'] = "Ocak"
        arr['2'] = "Şubat"
        arr['3'] = "Mart"
        arr['4'] = "Nisan"
        arr['5'] = "Mayıs"
        arr['6'] = "Haziran"
        arr['7'] = "Temmuz"
        arr['8'] = "Ağustos"
        arr['9'] = "Eylül"
        arr['10'] = "Ekim"
        arr['11'] = "Kasım"
        arr['12'] = "Aralık"

        return arr[date]
    }
    </script>
    <script>"use strict";
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#8950FC",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#3699FF",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#EEE5FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#E1F0FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                },
                "custom": {
                    "white": "#ffffff",
                    "primary": "#0a66e4",
                    "secondary": "#667c8e",
                    "success": "#f59212",
                    "info": "#f9de60",
                    "warning": "#f7b639",
                    "danger": "#e36674",
                    "light": "#a0bcd9",
                    "dark": "#5c5c5c"
                }
            },
            "font-family": "Poppins"
        };
    
    var widget_1 = function() {
        var element = document.getElementById("widget_1");
        var height = parseInt(KTUtil.css(element, 'height'));

        if (!element) {
            return;
        }

        var strokeColor = '#D13647';

        var options = {
            series: [{
                name: 'Yeni Projeler'
            }],
            chart: {
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    enabledOnSeries: undefined,
                    top: 5,
                    left: 0,
                    blur: 3,
                    color: strokeColor,
                    opacity: 0.5
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 0
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [strokeColor]
            },
            xaxis: {
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    },
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTAppSettings['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    },
                    formatter: function (value) {
                    return value + " adet";
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(val) {
                        return val + " adet"
                    }
                },
                marker: {
                    show: false
                }
            },
            colors: ['transparent'],
            markers: {
                colors: [KTAppSettings['colors']['theme']['light']['danger']],
                strokeColor: [strokeColor],
                strokeWidth: 3
            }
        };

        $.ajax({
            url: "{{ route('dashboard-projects') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series[0].data = item;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    widget_1();

    var exploration_status = function() {
        var element = document.getElementById("exploration_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('explorations') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],

                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],

                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],

                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-exploration-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                console.log('status')
                console.log(item)
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    exploration_status();

    var production_status = function() {
        var element = document.getElementById("production_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('productions') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-production-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                console.log('status')
                console.log(item)
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    production_status();
    
    var assembly_status = function() {
        var element = document.getElementById("assembly_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('assemblys') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-assembly-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    assembly_status();
    
    var printing_status = function() {
        var element = document.getElementById("printing_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('printings') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['info'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-printing-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    printing_status();

    var brief_status = function() {
        var element = document.getElementById("brief_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('assemblys') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-brief-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    brief_status();

    var offer_status = function() {
        var element = document.getElementById("offer_status");

        var options = {
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                events: {
                    click: function(event, chartContext, config){
                        var full_status = event.target.outerText
                        var status = full_status.split(' (')
                        window.location.href = "{{ route('assemblys') }}?status="+status[0]
                    },
                },
            },
            responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                position: 'bottom'
                }
            }
            }],
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(value) {
                    return value + ' adet';
                    }
                }
            },
            colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
            markers: {
                colors: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ],
                strokeColor: [
                KTAppSettings['colors']['theme']['base']['success'],
                KTAppSettings['colors']['theme']['base']['danger'],
                KTAppSettings['colors']['theme']['base']['primary'],
                KTAppSettings['colors']['theme']['base']['warning'],
                KTAppSettings['colors']['theme']['base']['info'],

                KTAppSettings['colors']['theme']['base']['dark'],
                KTAppSettings['colors']['custom']['primary'],
                KTAppSettings['colors']['custom']['success'],
                KTAppSettings['colors']['custom']['danger'],
                KTAppSettings['colors']['custom']['warning'],
                KTAppSettings['colors']['custom']['info'],

                KTAppSettings['colors']['custom']['dark'],
            ]
            }
        };

        $.ajax({
            url: "{{ route('get-offer-status') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series = item.series;
                options.labels = item.labels;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    offer_status();

    /* aylık satış */
    var aylik_sayis_grafigi = function() {
        var element = document.getElementById("aylik_sayis_grafigi");

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Satışlar'
            }],
            chart: {
                type: 'area',
                height: 150,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [KTAppSettings['colors']['theme']['base']['danger']]
            },
            xaxis: {
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTAppSettings['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(val) {
                        return formatMoney(val) + "TL"
                    }
                }
            },
            colors: [KTAppSettings['colors']['theme']['light']['danger']],
            markers: {
                colors: [KTAppSettings['colors']['theme']['light']['danger']],
                strokeColor: [KTAppSettings['colors']['theme']['base']['danger']],
                strokeWidth: 3
            }
        };

        $.ajax({
            url: "{{ route('monthly-sales-json') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series[0].data = item;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    aylik_sayis_grafigi();
    /* aylik_sayis_grafigi son */

    /* yillik satış */
    var yillik_sayis_grafigi = function() {
        var element = document.getElementById("yillik_sayis_grafigi");

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Satışlar'
            }],
            chart: {
                type: 'area',
                height: 150,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [KTAppSettings['colors']['theme']['base']['danger']]
            },
            xaxis: {
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    },
                    formatter: function(val) {
                        return turkishDate(val)
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTAppSettings['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    }
                }
            },
            yaxis: {
                labels: {
                    show: false,
                    style: {
                        colors: KTAppSettings['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTAppSettings['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTAppSettings['font-family']
                },
                y: {
                    formatter: function(val) {
                        return formatMoney(val) + "TL"
                    }
                }
            },
            colors: [KTAppSettings['colors']['theme']['light']['danger']],
            markers: {
                colors: [KTAppSettings['colors']['theme']['light']['danger']],
                strokeColor: [KTAppSettings['colors']['theme']['base']['danger']],
                strokeWidth: 3
            }
        };

        $.ajax({
            url: "{{ route('yearly-sales-json') }}?{!! \Request::getQueryString() !!}",
            dataType: 'json',
            type: 'get',
            success: function(item){
                options.series[0].data = item;
                var chart = new ApexCharts(element, options);
                chart.render();
            }
        });
    }
    yillik_sayis_grafigi();
    /* yillik_sayis_grafigi son */
    </script>
    <script>

        

$('[id^=customer_]').each(function(){
    var id = $(this).attr('data-id')
    var element_id = $(this).attr('id')
    var element = document.getElementById(element_id);

    var height = parseInt(KTUtil.css(element, 'height'));

    var options = {
        series: [{
            name: "Satışlar",
                            data: [35, 65, 75, 55, 45, 60, 55]
        }],
        chart: {
            type: "bar",
            height: height,
            toolbar: {
                show: !1
            },
            sparkline: {
                enabled: !0
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: ["30%"],
                endingShape: "rounded"
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        stroke: {
            show: !0,
            width: 1,
            colors: ["transparent"]
        },
        xaxis: {
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTAppSettings["font-family"]
                }
            }
        },
        yaxis: {
            min: 0,
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTAppSettings["font-family"]
                }
            }
        },
        fill: {
            type: ["solid"],
            opacity: [.25, 1]
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            active: {
                allowMultipleDataPointsSelection: !1,
                filter: {
                    type: "none",
                    value: 0
                }
            }
        },
        tooltip: {
            style: {
                fontSize: "12px",
                fontFamily: KTAppSettings["font-family"]
            },
            y: {
                formatter: function(val) {
                    return formatMoney(val) + "TL"
                }
            },
            marker: {
                show: !1
            }
        },
        colors: ["#ffffff"],
        grid: {
            borderColor: KTAppSettings.colors.gray["gray-200"],
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: !0
                }
            },
            padding: {
                left: 20,
                right: 20
            }
        }
    };

    $.ajax({
        url: "{{ route('customer-data') }}/"+id,
        dataType: 'json',
        type: 'get',
        success: function(item){
            options.series[0].data = item.series;
            options.labels = item.labels;
            var chart = new ApexCharts(element, options);
            chart.render();
        }
    });
})



var element2 = document.getElementById("yillik_satis_genel");
if (element2){
    var options2 = {
        series: [{
            name: "Tutar",
        }],
        chart: {
            type: "bar",
            height: 350,
            toolbar: {
                show: !1
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: ["30%"],
                endingShape: "rounded"
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        stroke: {
            show: !0,
            width: 2,
            colors: ["transparent"]
        },
        xaxis: {
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTApp["font-family"]
                },
                formatter: function(val) {
                    return turkishDate(val)
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTApp["font-family"]
                },
                    formatter: function(val) {
                        return formatMoney(val) + "TL"
                    }
            }
        },
        fill: {
            opacity: 1
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            active: {
                allowMultipleDataPointsSelection: !1,
                filter: {
                    type: "none",
                    value: 0
                }
            }
        },
        tooltip: {
            style: {
                fontSize: "12px",
                fontFamily: KTApp["font-family"]
            },
            y: {
                formatter: function(val) {
                    return formatMoney(val) + "TL"
                }
            }
        },
        colors: [KTAppSettings.colors.theme.base.success, KTAppSettings.colors.gray["gray-300"]],
        grid: {
            borderColor: KTAppSettings.colors.gray["gray-200"],
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: !0
                }
            }
        }
    };
    
    $.ajax({
        url: "{{ route('yillik-satis-genel') }}",
        dataType: 'json',
        type: 'get',
        success: function(item){
            options2.series[0].data = item.series;
            options2.labels = item.labels;
            var chart = new ApexCharts(element2, options2);
            chart.render();
        }
    });
    
}
    
var element = document.getElementById("yillik_mt_genel");
if (element){
    var options = {
        series: [{
            name: "Senelik Satış",
        }],
        chart: {
            type: "bar",
            height: 350,
            toolbar: {
                show: !1
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: ["30%"],
                endingShape: "rounded"
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        stroke: {
            show: !0,
            width: 2,
            colors: ["transparent"]
        },
        xaxis: {
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTApp["font-family"]
                },
                    formatter: function(val) {
                        return val
                    }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: KTAppSettings.colors.gray["gray-500"],
                    fontSize: "12px",
                    fontFamily: KTApp["font-family"]
                },
                    formatter: function(val) {
                        return formatMoney(val) + "TL"
                    }
            }
        },
        fill: {
            opacity: 1
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0
                }
            },
            active: {
                allowMultipleDataPointsSelection: !1,
                filter: {
                    type: "none",
                    value: 0
                }
            }
        },
        tooltip: {
            style: {
                fontSize: "12px",
                fontFamily: KTApp["font-family"]
            },
            y: {
                formatter: function(val) {
                    return formatMoney(val) + "TL"
                }
            }
        },
        colors: [KTAppSettings.colors.theme.base.danger, KTAppSettings.colors.gray["gray-300"]],
        grid: {
            borderColor: KTAppSettings.colors.gray["gray-200"],
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: !0
                }
            }
        }
    };
    
    $.ajax({
        url: "{{ route('yillik-mt-genel') }}",
        dataType: 'json',
        type: 'get',
        success: function(item){
            options.series[0].data = item.series;
            options.labels = item.labels;
            var chart = new ApexCharts(element, options);
            chart.render();
        }
    });
    
}
        
</script>
@endsection

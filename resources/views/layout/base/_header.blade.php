{{-- Header --}}
<div id="kt_header" class="header {{ Metronic::printClasses('header', false) }}" {{ Metronic::printAttrs('header') }}>

    {{-- Container --}}
    <div class="container-fluid d-flex align-items-center justify-content-between">
        @if (config('layout.header.self.display'))

            @php
                $kt_logo_image = 'logo-light.png';
            @endphp

            @if (config('layout.header.self.theme') === 'light')
                @php $kt_logo_image = 'wmc-logo-50.png' @endphp
            @elseif (config('layout.header.self.theme') === 'dark')
                @php $kt_logo_image = 'logo-light.png' @endphp
            @endif

            {{-- Header Menu --}}
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                @if(config('layout.aside.self.display') == false)
                    <div class="header-logo">
                        <a href="{{ url('/') }}">
                            <img alt="Logo" src="{{ asset('media/logos/'.$kt_logo_image) }}"/>
                        </a>
                    </div>
                @endif

                <div id="kt_header_menu" class="header-menu header-menu-mobile {{ Metronic::printClasses('header_menu', false) }}" {{ Metronic::printAttrs('header_menu') }}>
                    <ul class="menu-nav {{ Metronic::printClasses('header_menu_nav', false) }}">
                    
                    @if($authenticated->isAccountant())
                        <li class="menu-item menu-item-submenu menu-item-rel show-notifications" data-type="muhasebe" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                                <span class="menu-text">
                                <span class="label label-danger" style="margin-right:7px; color:#fff;">{{ $authenticated->muhasebeTalepleri() }}</span>
                                Muhasebe Onayında
                            </span>
                            </a>
                            <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                            <ul class="menu-subnav">
                                <li class="menu-item " aria-haspopup="true">
                                <a href="{{ route('cost-waiting-payment') }}" class="menu-link ">
                                    <span class="menu-link-icon mr-2 d-none">
                                    <span class="flaticon-coins" style="color:#0abb87";></span>
                                    </span>
                                    <span class="menu-text">
                                    <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->odenmeyenMasraflar() }}</span> Ödeme Bekleyen Masraflar
                                    </span>
                                </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                <a href="{{ route('earnest-waiting-approval') }}" class="menu-link ">
                                    <span class="menu-link-icon mr-2 d-none">
                                    <span class="flaticon-coins" style="color:#0abb87";></span>
                                    </span>
                                    <span class="menu-text">
                                    <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->odenmeyenAvanslar() }}</span> Masraf Talepleri
                                    </span>
                                </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                <a href="{{ route('maaslar-odeme-bekleyen') }}" class="menu-link ">
                                    <span class="menu-link-icon mr-2 d-none">
                                    <span class="flaticon-calendar-with-a-clock-time-tools" style="color:#0abb87";></span>
                                    </span>
                                    <span class="menu-text">
                                    <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->bekleyenMaaslar() }}</span> Maaş Ödemeleri
                                    </span>
                                </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    
                    @if($authenticated->isSatinAlma())
                        <li class="menu-item menu-item-submenu menu-item-rel show-notifications" data-type="satin_alma" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                                <span class="menu-text">
                                <span class="label label-danger" style="margin-right:7px; color:#fff;">{{ $authenticated->satinAlmaTalepleri() }}</span>
                                Satın Alma Onayında
                            </span>
                            </a>
                            <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                            <ul class="menu-subnav">
                                <li class="menu-item " aria-haspopup="true">
                                <a href="{{ route('need-waiting-approval') }}" class="menu-link ">
                                    <span class="menu-link-icon mr-2 d-none">
                                    <span class="flaticon-coins" style="color:#0abb87";></span>
                                    </span>
                                    <span class="menu-text">
                                    <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->alinmayanIhtiyaclar() }}</span> Bekleyen İhtiyaçlar
                                    </span>
                                </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                        
                        <li class="menu-item menu-item-submenu menu-item-rel show-notifications" data-type="onay_bekleyen" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                                <span class="menu-text">
                                <span class="label label-danger" style="margin-right:7px; ">{{ $authenticated->projectApproval() }}</span>
                                Onay Bekleyenler
                            </span>
                            </a>
                            <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                            <ul class="menu-subnav">
                            @if($authenticated->power('exploration', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('explorations', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->explorationApproval() }}</span> Keşif Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('production', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('productions', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->productionApproval() }}</span> Üretim Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('assembly', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('assemblys', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->assemblyApproval() }}</span> Montaj Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('printing', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('printings', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->printingApproval() }}</span> Baskı Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                                @if(
                                    (
                                        $authenticated->power('exploration', 'list')||
                                        $authenticated->power('production', 'list')||
                                        $authenticated->power('assembly', 'list')||
                                        $authenticated->power('printing', 'list')
                                    )&&(
                                        $authenticated->power('briefs', 'list')||
                                        $authenticated->power('offers', 'list')||
                                        $authenticated->power('contracts', 'list')||
                                        $authenticated->power('bills', 'list')
                                    )
                                )
                                <hr>
                            @endif
                            @if($authenticated->power('briefs', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('briefs', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-danger" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->briefApproval() }}</span> Brief Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('offers', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('offers', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-danger" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->offerApproval() }}</span> Teklif Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('contracts', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('contracts', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-danger" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->contractApproval() }}</span> Sözleşme Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('bills', 'list'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('bills', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-danger" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->billApproval() }}</span> Fatura Onayları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('purchase', 'edit'))
                                <hr>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('purchases', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-info" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->purchaseApproval() }}</span> Satınalma Talepleri
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($authenticated->power('expense', 'confirmation')||$authenticated->power('expense', 'paid'))
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('expenses', ['waiting' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-info" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->expenseApproval() }}</span> Tedarikçi Faturaları
                                        </span>
                                    </a>
                                </li>
                            @endif
                            </ul>
                        </li>

                        @if($authenticated->isAdmin())
                        <li class="menu-item menu-item-submenu menu-item-rel show-notifications" data-type="admins" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                                <span class="menu-text">
                                <span class="label label-danger" style="margin-right:7px; color:#fff;">{{ $authenticated->bekleyenTalepler() }}</span>
                                Yönetici Onayında
                            </span>
                            </a>
                            <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                            <ul class="menu-subnav">
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('permision-waiting-approval') }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->izinTalepleri() }}</span> İzin Talepleri
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('earnest-waiting-approval') }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->avansTalepleri() }}</span> Masraf Talepleri
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('cost-waiting-approval') }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->bekleyenMasraflar() }}</span> Onay Bekleyen Fişler
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('need-waiting-approval') }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-success" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->bekleyenIhtiyaclar() }}</span> Onay Bekleyen İhtiyaçlar
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        
                        @if($authenticated->power('vehicles', 'list'))
                        <li class="menu-item menu-item-submenu menu-item-rel show-notifications" data-type="vehicles" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                                <span class="menu-text">
                                <span class="label label-danger" style="margin-right:7px; color:#fff;">{{ $authenticated->aracTalepleri() }}</span>
                                Araç Uyarıları
                            </span>
                            </a>
                            <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                            <ul class="menu-subnav">
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('vehicles', ['care' => 1]) }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->waitingCare() }}</span> Bakımı Yaklaşanlar
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('vehicles', ['kasko' => 1]) }}" class="menu-link ">
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->waitingKasko() }}</span> Kaskosu Yaklaşanlar
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('vehicles', ['insurance' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->waitingInsurance() }}</span> Sigortası Yaklaşanlar
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item " aria-haspopup="true">
                                    <a href="{{ route('vehicles', ['loan' => 1]) }}" class="menu-link ">
                                        <span class="menu-link-icon mr-2 d-none">
                                        <span class="flaticon-coins" style="color:#0abb87";></span>
                                        </span>
                                        <span class="menu-text">
                                        <span class="label label-light-primary" style="margin-right:7px; color:#1e1e2d;">{{ $authenticated->waitingLoan() }}</span> Kiralık Bitiş Yaklaşanlar
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                        <a href="javascript:;" class="menu-link menu-toggle pulse pulse--brand" style="background: rgba(77, 89, 149, 0.06)">
                            <span class="menu-text">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon svg-icon--danger">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                            </svg>
                            Yeni Talep
                        </span>
                        </a>
                        <div class="menu-submenu menu-submenu--classic menu-submenu--left">
                        <ul class="menu-subnav">
                            <li class="menu-item " aria-haspopup="true">
                            <a href="{{ route('personel-izinler') }}" class="menu-link ">
                                <span class="menu-text">İzin Talepleri</span>
                            </a>
                            </li>
                            <li class="menu-item " aria-haspopup="true">
                            <a href="{{ route('personel-avanslar') }}" class="menu-link ">
                                <span class="menu-text">Masraf Talepleri</span>
                            </a>
                            </li>
                            <li class="menu-item " aria-haspopup="true">
                            <a href="{{ route('personel-ihtiyaclar') }}" class="menu-link ">
                                <span class="menu-text">İhtiyaç Talepleri</span>
                            </a>
                            </li>
                            <li class="menu-item " aria-haspopup="true">
                            <a href="{{ route('personel-zimmetler') }}" class="menu-link ">
                                <span class="menu-text">Zimmetler</span>
                            </a>
                            </li>
                        </ul>
                        </li>
                    </ul>
                </div>
            </div>

        @else
            <div></div>
        @endif

        @include('layout.partials.extras._topbar')
    </div>
</div>

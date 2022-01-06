{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="card card-custom gutter-b card-stretch">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder">Trends</h3>
                    <div class="card-toolbar">
                        <div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-clean btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ki ki-bold-more-hor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                <!--begin::Naviigation-->
                                <ul class="navi">
                                    <li class="navi-header font-weight-bold py-5">
                                        <span class="font-size-lg">Add New:</span>
                                        <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
                                    </li>
                                    <li class="navi-separator mb-3 opacity-70"></li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-shopping-cart-1"></i>
                                            </span>
                                            <span class="navi-text">Order</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="navi-icon flaticon2-calendar-8"></i>
                                            </span>
                                            <span class="navi-text">Members</span>
                                            <span class="navi-label">
                                                <span class="label label-light-danger label-rounded font-weight-bold">3</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="navi-icon flaticon2-telegram-logo"></i>
                                            </span>
                                            <span class="navi-text">Project</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="navi-icon flaticon2-new-email"></i>
                                            </span>
                                            <span class="navi-text">Record</span>
                                            <span class="navi-label">
                                                <span class="label label-light-success label-rounded font-weight-bold">5</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="navi-separator mt-3 opacity-70"></li>
                                    <li class="navi-footer pt-5 pb-4">
                                        <a class="btn btn-light-primary font-weight-bolder btn-sm" href="#">More options</a>
                                        <a class="btn btn-clean font-weight-bold btn-sm d-none" href="#" data-toggle="tooltip" data-placement="right" title="Click to learn more...">Learn more</a>
                                    </li>
                                </ul>
                                <!--end::Naviigation-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column">
                    <!--begin::Chart-->
                    <div class="flex-grow-1">
                        <div id="kt_mixed_widget_15_chart" style="height: 150px"></div>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Items-->
                    <div class="mt-5">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mr-2">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                    <div class="symbol-label">
                                        <img src="assets/media/svg/misc/006-plurk.svg" class="h-50" alt="" />
                                    </div>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Title-->
                                <div>
                                    <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Top Authors</a>
                                    <div class="font-size-sm text-muted font-weight-bold mt-1">Ricky Hunt, Sandra Trepp</div>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Label-->
                            <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">+82$</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Widget Item-->
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mr-2">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                    <div class="symbol-label">
                                        <img src="assets/media/svg/misc/015-telegram.svg" class="h-50" alt="" />
                                    </div>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Title-->
                                <div>
                                    <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Top Sales</a>
                                    <div class="font-size-sm text-muted font-weight-bold mt-1">PitStop Emails</div>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Label-->
                            <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">+82$</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Widget Item-->
                        <!--begin::Widget Item-->
                        <div class="d-flex align-items-center justify-content-between">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center mr-2">
                                <!--begin::Symbol-->
                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                    <div class="symbol-label">
                                        <img src="assets/media/svg/misc/003-puzzle.svg" class="h-50" alt="" />
                                    </div>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Title-->
                                <div>
                                    <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Top Engagement</a>
                                    <div class="font-size-sm text-muted font-weight-bold mt-1">KT.com</div>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Label-->
                            <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">+82$</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Widget Item-->
                    </div>
                    <!--end::Widget Items-->
                </div>
                <!--end::Body-->
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    
                </div>
            </div>
            <div class="col-lg-6 col-xxl-4">
                @include('management.dashboard.widgets._widget-1', ['class' => 'card-stretch gutter-b'])
            </div>

            <div class="col-lg-6 col-xxl-4">
                @include('management.dashboard.widgets._widget-2', ['class' => 'card-stretch gutter-b'])
            </div>

            <div class="col-lg-6 col-xxl-4">
                @include('management.dashboard.widgets._widget-3', ['class' => 'card-stretch card-stretch-half gutter-b'])
                @include('management.dashboard.widgets._widget-4', ['class' => 'card-stretch card-stretch-half gutter-b'])
            </div>

            <div class="col-lg-4 col-xxl-4">
                <div class="card card-custom {{ @$class }}">
                    {{-- Body --}}
                    <div class="card-body d-flex flex-column p-0">
                        <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                            <div class="d-flex flex-column mr-2">
                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Üretim Durumu</a>
                                <span class="text-muted font-weight-bold mt-2">Tarih aralığında başlayan üretimlerin durumu</span>
                            </div>
                            <div class="d-flex flex-column text-right">
                                <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $production_total }} adet</span>
                            </div>
                        </div>
                        <div id="production_status" class="card-rounded-bottom"  style="height: 150px">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-xxl-4">
                <div class="card card-custom {{ @$class }}">
                    {{-- Body --}}
                    <div class="card-body d-flex flex-column p-0">
                        <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                            <div class="d-flex flex-column mr-2">
                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Montaj Durumu</a>
                                <span class="text-muted font-weight-bold mt-2">Tarih aralığında başlayan montajların durumu</span>
                            </div>
                            <div class="d-flex flex-column text-right">
                                <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $assembly_total }} adet</span>
                            </div>
                        </div>
                        <div id="assembly_status" class="card-rounded-bottom"  style="height: 150px">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-xxl-4">
                <div class="card card-custom {{ @$class }}">
                    {{-- Body --}}
                    <div class="card-body d-flex flex-column p-0">
                        <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                            <div class="d-flex flex-column mr-2">
                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">Baskı Durumu</a>
                                <span class="text-muted font-weight-bold mt-2">Tarih aralığında başlayan baskıların durumu</span>
                            </div>
                            <div class="d-flex flex-column text-right">
                                <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $printing_total }} adet</span>
                            </div>
                        </div>
                        <div id="printing_status" class="card-rounded-bottom"  style="height: 150px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/config.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
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
                }
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
                }
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
                }
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
    </script>
@endsection

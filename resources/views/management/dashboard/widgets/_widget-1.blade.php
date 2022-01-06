{{-- Mixed Widget 1 --}}

<div class="card card-custom bg-gray-100 {{ @$class }}">
    {{-- Header --}}
    <div class="card-header border-0 bg-danger py-5">
        <h3 class="card-title font-weight-bolder text-white">Genel Veriler</h3>
        <div class="card-toolbar">
        </div>
    </div>
    {{-- Body --}}
    <div class="card-body p-0 position-relative overflow-hidden">
        {{-- Chart --}}
        <div id="widget_1" class="card-rounded-bottom bg-danger" style="height: 200px"></div>

        {{-- Stats --}}
        <div class="card-spacer mt-n25">
            {{-- Row --}}
            <div class="row m-0">
                <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
                    <span class="text-warning font-weight-boldest font-size-h3 mt-2 d-block">{{ $projects_total }} adet</span>
                    <a href="#" class="text-warning font-weight-bold font-size-h6">
                        Yeni Proje
                    </a>
                </div>
                <div class="col bg-light-primary px-6 py-8 rounded-xl mb-7">
                    <span class="text-primary font-weight-boldest font-size-h3 mt-2 d-block">{{ $production_total }} adet</span>
                    <a href="#" class="text-primary font-weight-bold font-size-h6 mt-2">
                        Yeni Üretim
                    </a>
                </div>
            </div>
            {{-- Row --}}
            <div class="row m-0">
                <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
                    <span class="text-danger font-weight-boldest font-size-h3 mt-2 d-block">{{ $assembly_total }} adet</span>
                    <a href="#" class="text-danger font-weight-bold font-size-h6 mt-2">
                        Yeni Montaj
                    </a>
                </div>
                <div class="col bg-light-success px-6 py-8 rounded-xl">
                    <span class="text-success font-weight-boldest font-size-h3 mt-2 d-block">{{ $printing_total }} adet</span>
                    <a href="#" class="text-success font-weight-bold font-size-h6 mt-2">
                        Yeni Baskı
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

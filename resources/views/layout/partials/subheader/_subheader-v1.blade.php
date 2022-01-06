{{-- Subheader V1 --}}

<div class="subheader py-2 {{ Metronic::printClasses('subheader', false) }}" id="kt_subheader">
    <div class="{{ Metronic::printClasses('subheader-container', false) }} d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

		{{-- Info --}}
        <div class="d-flex align-items-center flex-wrap mr-1">

			{{-- Page Title --}}
            <h5 class="text-dark font-weight-bold my-2 mr-5">
                {{ @$page_title }}

                @if (isset($page_description) && config('layout.subheader.displayDesc'))
                    <small>{{ @$page_description }}</small>
                @endif
            </h5>

            @if (!empty($page_breadcrumbs))
				{{-- Separator --}}
                <div class="subheader-separator subheader-separator-ver my-2 mr-4 d-none"></div>

				{{-- Breadcrumb --}}
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2">
                    <li class="breadcrumb-item"><a href="#"><i class="flaticon2-shelter text-muted icon-1x"></i></a></li>
                    @foreach ($page_breadcrumbs as $k => $item)
						<li class="breadcrumb-item">
                        	<a href="{{ url($item['page']) }}" class="text-muted">
                            	{{ $item['title'] }}
                        	</a>
						</li>
                    @endforeach
                </ul>
            @endif
        </div>

		{{-- Toolbar --}}
        <div class="d-flex align-items-center">
            <a href="#" class="btn btn-sm btn-light font-weight-bold mr-2" id="main_date_changer" data-toggle="tooltip" title="" data-placement="left" data-original-title="Rapor aralığını değiştir" >
                <span class="text-muted font-size-base font-weight-bold mr-2" id="kt_dashboard_daterangepicker_title">Seçili:</span>
                <span class="text-primary font-size-base font-weight-bolder" id="kt_dashboard_daterangepicker_date">{{ Request::get('start_at') ? \Carbon\Carbon::parse(Request::get('start_at'))->formatLocalized('%d %B')."-".\Carbon\Carbon::parse(Request::get('end_at'))->formatLocalized('%d %B %Y') : \Carbon\Carbon::now()->formatLocalized('%B %Y') }}</span>
            </a>
            <a href="{{ $redirect ?? url()->previous() }}" class="btn btn-sm btn-bold btn-light-info" >
                <i class="ki ki-long-arrow-back icon-sm"></i>&nbsp;&nbsp; Geri Dön
            </a>
        </div>

    </div>
</div>

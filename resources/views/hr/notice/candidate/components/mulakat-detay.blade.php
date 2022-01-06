@extends('hr.candidate.detail')

@include('common.subheader', 
  [
    'title' => 'Başlık', 
    'subtitle' => 'Altbaşlık', 
    'url' => route('mulakat', ['candidate_id' => $detail->id])
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

            <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">
              <div class="card">
                <div class="card-header" id="headingOne6">
                  <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="false" aria-controls="collapseOne6">
                    <i class="flaticon-pie-chart-1"></i> Product Inventory
                  </div>
                </div>
                <div id="collapseOne6" class="collapse" aria-labelledby="headingOne6" data-parent="#accordionExample6" style="">
                  <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="headingTwo6">
                  <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo6" aria-expanded="false" aria-controls="collapseTwo6">
                    <i class="flaticon2-notification"></i> Order Statistics
                  </div>
                </div>
                <div id="collapseTwo6" class="collapse" aria-labelledby="headingTwo6" data-parent="#accordionExample6">
                  <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="headingThree6">
                  <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree6" aria-expanded="false" aria-controls="collapseThree6">
                    <i class="flaticon2-chart"></i> eCommerce Reports
                  </div>
                </div>
                <div id="collapseThree6" class="collapse" aria-labelledby="headingThree6" data-parent="#accordionExample6">
                  <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>
                </div>
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
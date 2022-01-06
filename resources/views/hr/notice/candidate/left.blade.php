
<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
  <!--begin::Profile Card-->
  <div class="card card-custom card-stretch">
    <!--begin::Body-->
    <div class="card-body pt-4">
      <!--begin::User-->
      <div class="d-flex align-items-center">
        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
          @if($detail->photo)
          <div class="symbol-label mr-5" style="background-image:url('{{ Storage::url('uploads/candidate') }}/{{ $detail->photo }}')">
          </div>
          @else 
          <span class="symbol-label mr-5"> 
              <span>{{ strtoupper(substr($detail->name, 0, 1)) }}{{ strtoupper(substr($detail->surname, 0, 1)) }}</span>
          </span>
          @endif
        </div>
        <div>
          <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ $detail->name }} {{ $detail->surname }}</a>
          <div class="text-muted">{{ $detail->title }}</div>
          <div class="mt-2">
            <button type="button" class="btn btn-{{ aday_class($detail->status) }} btn-sm"><span class="{{ aday_icon($detail->status) }}"></span> {{ aday_statu($detail->status) }}</button>
          </div>
        </div>
      </div>
      <!--end::User-->
      <!--begin::Contact-->
      <div class="py-9">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">E-posta:</span>
          <a href="#" class="text-muted text-hover-primary">{{ $detail->email }}</a>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">Telefon:</span>
          <span class="text-muted">{{ $detail->phone }}</span>
        </div>
      </div>
      <!--end::Contact-->
      <!--begin::Nav-->
      <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
        <div class="navi-item mb-2">
          <a href="{{ route('candidate-detail', ['id' => $detail->id]) }}" class="navi-link py-4 active">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                {{ Metronic::getSVG("media/svg/icons/Design/Layers.svg", "svg-icon-2x ") }}
                <!--end::Svg Icon-->
              </span>
            </span>
            <span class="navi-text font-size-lg">Anasayfa</span>
          </a>
        </div>
        <div class="navi-item mb-2">
          <a href="{{ route('interview', ['candidate_id' => $detail->id]) }}" class="navi-link py-4 ">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                {{ Metronic::getSVG("media/svg/icons/Design/Layers.svg", "svg-icon-2x ") }}
                <!--end::Svg Icon-->
              </span>
            </span>
            <span class="navi-text font-size-lg">Mülakat Bilgileri</span>
          </a>
        </div>
        <div class="navi-item mb-2">
          <a href="{{ route('candidate-offer', ['candidate_id' => $detail->id]) }}" class="navi-link py-4 ">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                {{ Metronic::getSVG("media/svg/icons/Design/Layers.svg", "svg-icon-2x ") }}
                <!--end::Svg Icon-->
              </span>
            </span>
            <span class="navi-text font-size-lg">Teklif Bilgileri</span>
          </a>
        </div>
      </div>
      <!--end::Nav-->
          <a href="{{ Storage::url('uploads/candidate') }}/{{ $detail->cv }}" target="_blank" class="btn btn-light-success btn-block btn-lg btn-upper">özgeçmişi görüntüle</a>
          @if(aday_step($detail->status) == 5 && $detail->status == 'teklif_kabul')
          <a href="#"  
          class="btn btn-light-info btn-block btn-lg btn-upper call-bdo-modal" 
          style="margin-top:20px;"
          data-size="medium" 
          data-url="{{ route('define-as-personel', ['candidate_id' => $detail->id]) }}" 
          ><i class="la la-save"></i> personel olarak tanımla</a>
          @endif
          @if(aday_step($detail->status) < 6)
          <a href="#"  
          class="btn btn-danger btn-block btn-lg btn-upper aday-guncelle" 
          data-id="{{ $detail->id }}"
          data-value="Reddedildi"
          style="margin-top:20px;"><i class="la la-times"></i> adayı reddet</a>
          @endif
          @if($detail->user)
          <a href="{{ route('personel-detail', ['id' => $detail->user->id]) }}"  
          class="btn btn-info btn-block btn-lg btn-upper" 
          style="margin-top:20px;"><i class="la la-user"></i> Personel Bilgileri</a>
          @endif
    </div>
    <!--end::Body-->
  </div>
  <!--end::Profile Card-->
</div>
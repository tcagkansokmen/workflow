
<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px mr-10" id="kt_profile_aside">
  <!--begin::Profile Card-->
  <div class="card card-custom card-stretch">
    <!--begin::Body-->
    <div class="card-body pt-4">
      <!--begin::User-->
      <div class="d-flex align-items-center">
        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
          @if($detail->avatar)
          <div class="symbol-label mr-5" style="background-image:url('{{ Storage::url('uploads/users') }}/{{ $detail->avatar }}')">
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
            @if(!$detail->is_active)
              <span class="btn btn-sm btn-light-danger">Pasif Kullanıcı</span>
            @endif
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
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">TC Kimlik:</span>
          <a href="#" class="text-muted text-hover-primary">{{ $detail->tc_no }}</a>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">İşe Başlangıç:</span>
          <span class="text-muted">{{ date_formatter($detail->check_in_date) }}</span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">İşten Çıkış:</span>
          <span class="text-muted">{{ date_formatter($detail->check_out_date) }}</span>
        </div>
      </div>
      <!--end::Contact-->
      <!--begin::Nav-->
      <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
        <div class="navi-item mb-2">
          <a href="{{ route('employee-information', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/personel-bilgileri/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-sheet"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Personel Bilgileri</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('employee-account', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/hesap-bilgileri/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-user-1"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Hesap Bilgileri</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('employee-rating', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/degerlendirme/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-checking"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Proje Değerlendirme</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('employee-education', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/personel-egitimleri/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-group"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Eğitim & Sertifikalar</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('employee-finance', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/personel-finansal/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-files-and-folders"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Finansal Bilgiler</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('employee-demand', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/personel-talep/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-calendar-9"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">İzin ve Talepler</span>
          </a>
        </div>

        @if($authenticated->isIT()||$authenticated->isManager()||$authenticated->isPartner())
        <div class="navi-item mb-2">
          <a href="{{ route('employee-belonging', ['id' => $detail->id]) }}" class="navi-link py-4 {{ (request()->is('insan-kaynaklari/personeller/zimmet/*')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon-safe-shield-protection"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Zimmetler</span>
          </a>
        </div>
        @endif
      </div>

      @if($authenticated->power('employee', 'add'))
        @if($detail->is_active)
          <a href="{{ route('remove-employee', ['id' => $detail->id]) }}" data-id="{{ $detail->id }}" target="_blank" class="btn btn-light-danger btn-block btn-lg btn-upper sorgula">İşten Çıkarma/İstifa</a>
        @else 
          <a href="{{ route('activate-employee', ['id' => $detail->id]) }}" data-id="{{ $detail->id }}" target="_blank" class="btn btn-light-success btn-block btn-lg btn-upper sorgula">Aktif Et</a>
        @endif
      @endif

      <!--end::Nav-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Profile Card-->
</div>
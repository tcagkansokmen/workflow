
<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px mr-10" id="kt_profile_aside">
  <!--begin::Profile Card-->
  <div class="card card-custom card-stretch">
    <!--begin::Body-->
    <div class="card-body pt-4">
      <!--begin::User-->
      <div class="d-flex align-items-center">
        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
          {{ $authenticated->userAvatar('100') }}
        </div>
        <div>
          <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ $authenticated->name }} {{ $authenticated->surname }}</a>
          <div class="text-muted">{{ $authenticated->title }}</div>
          <div class="mt-2">
          </div>
        </div>
      </div>
      <!--end::User-->
      <!--begin::Contact-->
      <div class="py-9">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">E-posta:</span>
          <a href="#" class="text-muted text-hover-primary">{{ $authenticated->email }}</a>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">Telefon:</span>
          <span class="text-muted">{{ $authenticated->phone }}</span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">TC Kimlik:</span>
          <a href="#" class="text-muted text-hover-primary">{{ $authenticated->tc_no }}</a>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="font-weight-bold mr-2">İşe Başlangıç:</span>
          <span class="text-muted">{{ date_formatter($authenticated->start_at) }}</span>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div style="text-align:center; width:45%;">
          <span class="font-weight-bold mr-2">Kalan İzin Hakkı</span>
          <span class="btn btn-sm btn-block btn-light-success">{{ $authenticated->permissionsLeft() }} Gün</span>
        </div>
        <div style="text-align:center; width:45%;">
          <span class="font-weight-bold mr-2">Avans Durumu</span>
          @if($authenticated->earnestLeft()>0)
          <span class="btn btn-sm btn-block btn-light-info">{{ money_formatter($authenticated->earnestLeft()) }} TL</span>
          @else
          <span class="btn btn-sm btn-block btn-light-danger">{{ money_formatter($authenticated->earnestLeft()) }} TL</span>
          @endif
        </div>
      </div>
      <!--end::Contact-->
      <!--begin::Nav-->
      <div class="navi navi-bold navi-hover navi-active navi-link-rounded mt-10">
        <div class="navi-item mb-2">
          <a href="{{ route('user-dashboard') }}" class="navi-link py-4 {{ (request()->is('user/profil')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-sheet"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Personel Bilgileri</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('user-earnest') }}" class="navi-link py-4 {{ (request()->is('user/earnest')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-files-and-folders"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Avans Bilgileri</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('user-permission') }}" class="navi-link py-4 {{ (request()->is('user/permission')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-calendar-9"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">İzin Talepleri</span>
          </a>
        </div>

        <div class="navi-item mb-2">
          <a href="{{ route('user-belonging') }}" class="navi-link py-4 {{ (request()->is('user/belonging')) ? 'active' : '' }}">
            <span class="navi-icon mr-2">
              <span class="svg-icon">
                <span class="flaticon2-open-box"></span>
              </span>
            </span>
            <span class="navi-text font-size-lg">Zimmetler</span>
          </a>
        </div>

      </div>
      <!--end::Nav-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Profile Card-->
</div>

<!DOCTYPE html>
<html lang="tr" >
    <!--begin::Head-->
    <head>
      <meta charset="utf-8"/>
		  <title>{{ env('APP_NAME') }} - Yönetim Paneli</title>
      <meta name="description" content="Singin page example"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

      <!--begin::Fonts-->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>        <!--end::Fonts-->

      <link href="/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
      <link href="{{ env('APP_LANG') }}/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css"/>
      <link href="{{ env('APP_LANG') }}/css/style.bundle.css" rel="stylesheet" type="text/css"/>
      <!--end::Global Theme Styles-->

      <!--begin::Layout Themes(used by all pages)-->

      <link href="{{ env('APP_LANG') }}/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css"/>
      <link href="{{ env('APP_LANG') }}/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css"/>
      <link href="{{ env('APP_LANG') }}/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css"/>
      <link href="{{ env('APP_LANG') }}/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css"/>        <!--end::Layout Themes-->

        <link rel="shortcut icon" href="/favicon.png" />

            </head>
    <!--end::Head-->
<style>
.login.login-3 .login-aside {
  background-color: #ffffff;
  -webkit-box-shadow: 0px 0px 40px rgba(177, 187, 208, 0.15);
  box-shadow: 0px 0px 40px rgba(177, 187, 208, 0.15); }
  .login.login-3 .login-aside .wizard-nav {
    padding: 0; }
    .login.login-3 .login-aside .wizard-nav .wizard-steps {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center; }
      .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step {
        padding: 0.75rem 0;
        -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease;
        margin-bottom: 1.5rem; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step:last-child {
          margin-bottom: 0; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-wrapper {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-icon {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
          -webkit-box-pack: center;
          -ms-flex-pack: center;
          justify-content: center;
          -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease;
          width: 50px;
          height: 50px;
          border-radius: 50px;
          background-color: #F3F6F9;
          margin-right: 1.4rem; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-icon .wizard-check {
            display: none;
            font-size: 1.4rem; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-icon .wizard-number {
            font-weight: 600;
            color: #3F4254;
            font-size: 1.35rem; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-label {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-orient: vertical;
          -webkit-box-direction: normal;
          -ms-flex-direction: column;
          flex-direction: column;
          -webkit-box-pack: center;
          -ms-flex-pack: center;
          justify-content: center; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-label .wizard-title {
            color: #181C32;
            font-weight: 500;
            font-size: 1.4rem; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step .wizard-label .wizard-desc {
            color: #B5B5C3;
            font-size: 1.08rem;
            font-weight: 500; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="done"] .wizard-icon {
          -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease;
          background-color: #C9F7F5; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="done"] .wizard-icon .wizard-check {
            color: #1BC5BD;
            display: inline-block; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="done"] .wizard-icon .wizard-number {
            display: none; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="done"] .wizard-label .wizard-title {
          color: #B5B5C3; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="done"] .wizard-label .wizard-desc {
          color: #D1D3E0; }
        .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] {
          -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
          transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] .wizard-icon {
            -webkit-transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, -webkit-box-shadow 0.15s ease;
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, -webkit-box-shadow 0.15s ease;
            background-color: #C9F7F5; }
            .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] .wizard-icon .wizard-check {
              color: #1BC5BD;
              display: none; }
            .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] .wizard-icon .wizard-number {
              color: #1BC5BD; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] .wizard-label .wizard-title {
            color: #181C32; }
          .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step[data-wizard-state="current"] .wizard-label .wizard-desc {
            color: #B5B5C3; }
  .login.login-3 .login-aside .aside-img-wizard {
    min-height: 320px !important;
    background-size: 400px; }

.login.login-3 .login-content {
  background-color: #F3F5F9; }
  .login.login-3 .login-content .form-group .fv-help-block {
    font-size: 1.1rem !important;
    padding-top: 3px; }

@media (min-width: 992px) {
  .login.login-3 .login-aside {
    width: 100%;
    max-width: 600px; }
    .login.login-3 .login-aside .aside-img {
      min-height: 550px !important;
      background-size: 630px; }
  .login.login-3 .login-content .top-signup {
    max-width: 650px;
    width: 100%; }
  .login.login-3 .login-content .top-signin {
    max-width: 450px;
    width: 100%; }
  .login.login-3 .login-content .top-forgot {
    max-width: 450px;
    width: 100%; }
  .login.login-3 .login-content .login-form {
    width: 100%;
    max-width: 450px; }
    .login.login-3 .login-content .login-form.login-form-signup {
      max-width: 650px; } }

@media (min-width: 992px) and (max-width: 1399.98px) {
  .login.login-3 .login-aside {
    width: 100%;
    max-width: 400px; } }

@media (max-width: 991.98px) {
  .login.login-3 .login-aside .aside-img {
    min-height: 500px !important;
    background-size: 500px; }
  .login.login-3 .login-aside .login-logo {
    text-align: center; }
  .login.login-3 .login-aside .wizard-nav {
    padding: 0;
    -ms-flex-line-pack: center;
    align-content: center; }
    .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step {
      margin-bottom: .5rem; }
      .login.login-3 .login-aside .wizard-nav .wizard-steps .wizard-step:last-child {
        margin-bottom: 0; }
  .login.login-3 .login-content .top-signup {
    width: 100%;
    max-width: 400px; }
  .login.login-3 .login-content .top-signin {
    max-width: 400px;
    width: 100%; }
  .login.login-3 .login-content .top-forgot {
    max-width: 400px;
    width: 100%; }
  .login.login-3 .login-content .login-form {
    width: 100%;
    max-width: 400px; } }

@media (max-width: 575.98px) {
  .login.login-3 .login-aside .aside-img {
    min-height: 300px !important;
    background-size: 350px; } }


</style>
    <!--begin::Body-->
    <body  id="kt_body"  class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading"  >

    	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
<div class="login login-3 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
    <!--begin::Aside-->
    <div class="login-aside d-flex flex-column flex-row-auto">
        <!--begin::Aside Top-->
        <div class="d-flex flex-column-auto flex-column pt-15 px-30">
            <!--begin::Aside header-->
            <a href="#" class="login-logo py-6">
				<img src="{{ Storage::url('lastsis/uploads/logo/') }}{{ __('messages.logo') }}" class="max-h-70px" alt=""/>
			</a>
            <!--end::Aside header-->

            <!--begin: Wizard Nav-->
            <div class="wizard-nav pt-5 pt-lg-30">
                <!--begin::Wizard Steps-->
                <div class="wizard-steps">
                    <!--begin::Wizard Step 1 Nav-->
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-wrapper">
                            <div class="wizard-icon">
                                <i class="wizard-check ki ki-check"></i>
                                <span class="wizard-number">1</span>
                            </div>
                            <div class="wizard-label">
                                <h3 class="wizard-title">
                                    Kullanıcı Bilgileri
                                </h3>
                                <div class="wizard-desc">
                                    Sisteme giriş yapacak kullanıcı
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 1 Nav-->

                    <!--begin::Wizard Step 2 Nav-->
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-wrapper">
                            <div class="wizard-icon">
                                <i class="wizard-check ki ki-check"></i>
                                <span class="wizard-number">2</span>
                            </div>
                            <div class="wizard-label">
                                <h3 class="wizard-title">
                                    Firma Bilgileri
                                </h3>
                                <div class="wizard-desc">
                                    Sisteme kayıtlı firmanın bilgileri
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 2 Nav-->

                    <!--begin::Wizard Step 3 Nav
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-wrapper">
                            <div class="wizard-icon">
                                <i class="wizard-check ki ki-check"></i>
                                <span class="wizard-number">3</span>
                            </div>
                            <div class="wizard-label">
                                <h3 class="wizard-title">
                                    Ödeme Bilgileri
                                </h3>
                                <div class="wizard-desc">
                                    Abonelik satın alımı
                                </div>
                            </div>
                        </div>
                    </div>
                    end::Wizard Step 3 Nav-->

                </div>
                <!--end::Wizard Steps-->
            </div>
            <!--end: Wizard Nav-->
        </div>
        <!--end::Aside Top-->

        <!--begin::Aside Bottom-->
        <div class="aside-img-wizard d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center pt-2 pt-lg-5"
            style="background-position-y: calc(100% + 3rem); background-image: url({{ Storage::url('lastsis/uploads/logo/register.png') }});">
        </div>
        <!--end::Aside Bottom-->
    </div>
    <!--begin::Aside-->

    <!--begin::Content-->
    <div class="login-content flex-column-fluid d-flex flex-column p-10">
        <!--begin::Top-->
        <div class="text-right d-flex justify-content-center">
            <div class="top-signup text-right d-flex justify-content-end pt-5 pb-lg-0 pb-10">
    		</div>
		</div>
        <!--end::Top-->

        <!--begin::Wrapper-->
        <div class="d-flex flex-row-fluid flex-center">
            <!--begin::Signin-->
            <div class="login-form login-form-signup">
                <!--begin::Form-->
                <form class="form general-form" id="kt_login_signup_form" novalidate="novalidate" method="POST" action="{{ route('register-form') }}">
                  @csrf
                    <!--begin: Wizard Step 1-->
                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                        <!--begin::Title-->
                        <div class="pb-10 pb-lg-15">
                            <h3 class="font-weight-bolder text-dark display5">Hesap Oluşturun</h3>
                            <div class="text-muted font-weight-bold font-size-h4">
                                Zaten bir hesabınız mı var?
                                <a href="{{ route('login') }}" class="text-primary font-weight-bolder">Giriş Yapın</a>
                            </div>
                        </div>
                        <!--begin::Title-->
                        <div class="row">
                          <div class="col-sm-6">
                            <!--begin::Form Group-->
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-dark">İsim</label>
                                <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="name" placeholder="İsim" />
                            </div>
                            <!--end::Form Group-->
                          </div>
                          <div class="col-sm-6">
                            <!--begin::Form Group-->
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-dark">Soyisim</label>
                                <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="surname" placeholder="Soyisim" />
                            </div>
                            <!--end::Form Group-->
                          </div>
                        </div>


                        <!--begin::Form Group-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Telefon</label>
                            <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6 phone" name="phone" placeholder="Telefon" />
                        </div>
                        <!--end::Form Group-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">E-mail</label>
                            <input type="email" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="email" placeholder="E-mail" />
                        </div>
                          <div class="col-sm-12">
                            <p class="muted">Vermiş olduğunuz iletişim telefonu ve mail adresi, şifre sıfırlama ve hesap kurtama işlemleri ile birlikte iletişim bilgileriniz olarak kaydedilecektir. Doğru bilgi vermeniz hesap güvenliğiniz için önemlidir</p>
                          </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <!--begin::Form Group-->
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-dark">Parola</label>
                                <input type="password" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="password" placeholder="Parola" />
                            </div>
                            <!--end::Form Group-->
                          </div>
                          <div class="col-sm-6">
                            <!--begin::Form Group-->
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-dark">Parola (Tekrar)</label>
                                <input type="password" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="password_repeat" placeholder="Parola (tekrar)" />
                            </div>
                            <!--end::Form Group-->
                          </div>
                        </div>
                    </div>
                    <!--end: Wizard Step 1-->

                    <!--begin: Wizard Step 2-->
                    <div class="pb-5" data-wizard-type="step-content">
                        <!--begin::Title-->
                        <div class="pt-lg-0 pt-5 pb-15">
                            <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Firma Bilgileri</h3>
                            <div class="text-muted font-weight-bold font-size-h4">
                            </div>
                        </div>
                        <!--begin::Title-->

                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-12">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Firma Unvanı</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="title" placeholder="Firma Unvanı" />
                                </div>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Row-->

                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-6">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Vergi Numarası</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="tax_no" placeholder="Vergi Numarası" />
                                </div>
                                <!--end::Input-->
                            </div>
                            <div class="col-xl-6">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Vergi Dairesi</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="tax_office" placeholder="Vergi Dairesi" />
                                </div>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Row-->

                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-12">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Adres</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="address" placeholder="Adres" />
                                </div>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-6">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Şehir</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="city" placeholder="Şehir" />
                                </div>
                                <!--end::Input-->
                            </div>
                            <div class="col-xl-6">
                                <!--begin::Input-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">İlçe</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="county" placeholder="İlçe" />
                                </div>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Row-->
                        <div class="row ml-auto">
                            <div class="xl-6">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="contract_checkbox" value="1"/>
                                    <span></span>
                                    <a href="{{ asset('contracts/gizlilik-politikasi.pdf') }}" target="_blank">Gizlilik Sözleşmesi</a>'ni, <a href="{{ asset('contracts/kvkk.pdf') }}" target="_blank">Kişisel Verilerin Korunması Politikası</a>'nı ve <a href="{{ asset('contracts/kullanici-sozlesmesi.pdf') }}" target="_blank">Kullanıcı Sözleşmesi</a>'ni okudum ve onaylıyorum.
                                </label>
                            </div>
                        </div>
                    </div>
                    <!--end: Wizard Step 2-->

                    <!--begin: Wizard Step 3
                    <div class="pb-5" data-wizard-type="step-content">
                        <div class="pt-lg-0 pt-5 pb-15">
                            <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Ödeme</h3>
                            <div class="text-muted font-weight-bold font-size-h4">
                                Abonelik ödemenizi yaparak kaydınızı tamamlayın.
                            </div>
                        </div>
                        <div class="text-dark-50 line-height-lg bg-white p-5">
                          <div class="table-responsive">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th class="pl-0 font-weight-bold text-muted text-uppercase">#</th>
                                  <th class="text-right font-weight-bold text-muted text-uppercase">Adet</th>
                                  <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Tutar</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr class="font-weight-boldest">
                                  <td class="border-0 pl-0 pt-7 d-flex align-items-center">
                                  <div class="symbol symbol-40 flex-shrink-0 mr-4 symbol-light-success">
                                  <span class="symbol-label font-size-h5">1</span>
                                  </div>
                                  Yıllık Abonelik</td>
                                  <td class="text-right pt-7 align-middle">1</td>
                                  <td class="text-primary pr-0 pt-7 text-right align-middle">1990.00TL</td>
                                </tr>
                                <tr>
                                  <td colspan="1"></td>
                                  <td class="font-weight-bolder text-right">Ara Toplam</td>
                                  <td class="font-weight-bolder text-right pr-0">1990.00TL</td>
                                </tr>
                                <tr>
                                  <td colspan="1" class="border-0 pt-0"></td>
                                  <td class="border-0 pt-0 font-weight-bolder text-right">KDV</td>
                                  <td class="border-0 pt-0 font-weight-bolder text-right pr-0">358.20TL</td>
                                </tr>
                                <tr>
                                  <td colspan="1" class="border-0 pt-0"></td>
                                  <td class="border-0 pt-0 font-weight-bolder font-size-h5 text-right">Genel Toplam</td>
                                  <td class="border-0 pt-0 font-weight-bolder font-size-h5 text-success text-right pr-0">2348.20TL</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Kart Sahibi</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="card_name" placeholder="Kart Sahibi" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Kart Numarası</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6 card-no" name="card_no" placeholder="Kart Numarası" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">SKT</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6 card-date" name="card_date" placeholder="SKT" />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">CVV</label>
                                    <input type="text" class="form-control h-auto py-7 px-6 border-0 rounded-lg font-size-h6 cvv" name="cvv" placeholder="CVV" />
                                </div>
                            </div>
                        </div>

                        <div class="row ml-auto">
                            <div class="xl-6">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="contract-checkbox"/>
                                    <span></span>
                                    <a href="{{ url('/storage/contracts/gizlilik-politikasi.pdf') }}" target="_blank">Gizlilik Sözleşmesi</a>'ni ve <a href="{{ url('/storage/contracts/kvkk.pdf') }}" target="_blank">Kişisel Verilerin Korunması Politikası</a>'nı okudum ve onaylıyorum.
                                </label>
                            </div>
                        </div>

                    </div>
                    end: Wizard Step 3-->


                    <!--begin: Wizard Actions-->
                    <div class="d-flex justify-content-between pt-3">
                        <div class="mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder font-size-h6 pl-6 pr-8 py-4 my-3 mr-3" data-wizard-type="action-prev">
                                <span class="svg-icon svg-icon-md mr-1"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Left-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                      <polygon points="0 0 24 0 24 24 0 24"/>
                                      <rect fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) " x="14" y="7" width="2" height="10" rx="1"/>
                                      <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
                                  </g>
                              </svg><!--end::Svg Icon--></span>
                              Geri
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 pl-5 pr-8 py-4 my-3" data-wizard-type="action-submit" type="submit" id="kt_login_signup_form_submit_button">
                                Tamamla
                                <span class="svg-icon svg-icon-md ml-2"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                      <polygon points="0 0 24 0 24 24 0 24"/>
                                      <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                      <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                  </g>
                              </svg><!--end::Svg Icon--></span>
                              </button>
                            <button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" data-wizard-type="action-next">
                                İleri
                                <span class="svg-icon svg-icon-md ml-1"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                            </button>
                        </div>
                    </div>

                    <!--end: Wizard Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Signin-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
</div>
<!--end::Login-->
	</div>
<!--end::Main-->


  <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
  <!--begin::Global Config(global config for global JS scripts)-->
  <script>
  var KTAppSettings = {
  "breakpoints": {
  "sm": 576,
  "md": 768,
  "lg": 992,
  "xl": 1200,
  "xxl": 1400
  },
  "colors": {
  "theme": {
  "base": {
  "white": "#ffffff",
  "primary": "#3699FF",
  "secondary": "#E5EAEE",
  "success": "#1BC5BD",
  "info": "#8950FC",
  "warning": "#FFA800",
  "danger": "#F64E60",
  "light": "#E4E6EF",
  "dark": "#181C32"
  },
  "light": {
  "white": "#ffffff",
  "primary": "#E1F0FF",
  "secondary": "#EBEDF3",
  "success": "#C9F7F5",
  "info": "#EEE5FF",
  "warning": "#FFF4DE",
  "danger": "#FFE2E5",
  "light": "#F3F6F9",
  "dark": "#D6D6E0"
  },
  "inverse": {
  "white": "#ffffff",
  "primary": "#ffffff",
  "secondary": "#3F4254",
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
  "gray-200": "#EBEDF3",
  "gray-300": "#E4E6EF",
  "gray-400": "#D1D3E0",
  "gray-500": "#B5B5C3",
  "gray-600": "#7E8299",
  "gray-700": "#5E6278",
  "gray-800": "#3F4254",
  "gray-900": "#181C32"
  }
  },
  "font-family": "Poppins"
  };
  </script>
  <!--end::Global Config-->

  <!--begin::Global Theme Bundle(used by all pages)-->
  <script src="/plugins/global/plugins.bundle.js"></script>
  <script src="/plugins/custom/prismjs/prismjs.bundle.js"></script>
  <script src="/js/scripts.bundle.js"></script>
  <!--end::Global Theme Bundle-->


  <!--begin::Page Scripts(used by this page)-->
    <script src="/js/pages/custom/login/login-3.js"></script>
    <script>
    $(".phone").inputmask({
        "mask": "(599) 999-9999",
        definitions: {'5': {validator: "[1-9]"}},
        "showMaskOnHover": false
    });
    $('.tax_no').inputmask('999 999 999 9');
      $(".card-no").inputmask("9999-9999-9999-9999");
      $(".card-date").inputmask("99/99");
      $(".cvv").inputmask("999");
    </script>
    <script>
			KTApp.blockPage({
			overlayColor: '#000000',
			state: 'danger',
			message: 'Lütfen Bekleyin...'
			});
			$(document).ready(function(){
			KTApp.unblockPage();
			$('.general-form').ajaxForm({
				beforeSubmit:  function(formData, jqForm, options){
          KTApp.blockPage({
          overlayColor: '#000000',
          state: 'danger',
          message: 'Lütfen Bekleyin...'
          });
					var val = null;
						$(".formprogress").show();
						$( ".required", jqForm ).each(function( index ) {
							if(!$(this).val()){
								val = 1;
								$(this).addClass('is-invalid').addClass('is-invalid').closest('.form-group').find('.invalid-feedback').show().html('');
								$(this).closest('.form-group').find('.invalid-feedback').html("Bu alan zorunludur.");
								$(this).closest('.form-group').addClass('invalid-select');
							}else{
								$(this).removeClass('is-invalid');
								$(this).closest('.form-group').removeClass('invalid-select');
								$(this).closest('.form-group').find('.invalid-feedback').html(".");
							}
						});
						if(val){
							KTUtil.scrollTop();
						}
				},
				error: function(){
			  KTApp.unblockPage();
              swal.fire({
                text: "Dikkat! Sistemsel bir hata nedeniyle kaydedilemedi!",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                customClass: {
                  confirmButton: "btn font-weight-bold btn-light-primary"
                }
              }).then(function() {
                KTUtil.scrollTop();
              });
						$(".formprogress").hide();
				},
				dataType:  'json',
				success:   function(item){
			  KTApp.unblockPage();
						$(".formprogress").hide();
						if(item.status){
                swal.fire({
                html: item.message,
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                customClass: {
                  confirmButton: "btn font-weight-bold btn-light-primary"
                }
		          }).then(function() {
						    window.location.href = item.redirect;
					    });
						}else{
              swal.fire({
                icon: "error",
                html: item.message,
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                customClass: {
                  confirmButton: "btn font-weight-bold btn-light-primary"
                }
              }).then(function() {
                KTUtil.scrollTop();
              });
						}
				}
		});
		});
    </script>
  <!--end::Page Scripts-->
  </body>
  <!--end::Body-->
</html>

@extends('layouts.main')

@section('content')

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

  <!--Begin::App-->
  <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

    <!--Begin:: App Aside Mobile Toggle-->
    <button class="kt-app__aside-close" id="kt_user_profile_aside_close">
      <i class="la la-close"></i>
    </button>

    <!--End:: App Aside Mobile Toggle-->

    <!--Begin:: App Aside-->
    <div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">

      <!--begin:: Widgets/Applications/User/Profile1-->
      <div class="kt-portlet kt-portlet--height-fluid-">
        <div class="kt-portlet__head  kt-portlet__head--noborder">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
            </h3>
          </div>
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit-y">
          @include('user.components.left')
        </div>
      </div>

      <!--end:: Widgets/Applications/User/Profile1-->
    </div>

    <!--End:: App Aside-->

    <!--Begin:: App Content-->
    <!--Begin:: App Content-->
    <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
      <div class="row">
        <div class="col-xl-12">
          <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head">
              <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Şifremi güncelle<small>şifrenizi güncelleyebilirsiniz.</small></h3>
              </div>
            </div>
            <form class="kt-form kt-form--label-right general-form" method="POST" action="/user/password">
            @csrf
              <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                  <div class="kt-section__body">
                    <div class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40" role="alert">
                      <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
                      <div class="alert-text">Düzenli olarak şifrenizi değiştirmenizi ve şifrelerinizde çağrışım yapılabilecek (Doğum tarihi vb.) karakterler kullanmamanızı tavsiye ederiz.</div>
                      <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xl-3 col-lg-3 col-form-label">Mevcut Şifreniz</label>
                      <div class="col-lg-9 col-xl-6">
                        <input type="password" class="form-control" name="current_password" value="">
                        <!--<a href="#" class="kt-link kt-font-sm kt-font-bold kt-margin-t-5">Forgot password ?</a>-->
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xl-3 col-lg-3 col-form-label">Yeni Şifre</label>
                      <div class="col-lg-9 col-xl-6">
                        <input type="password" class="form-control password-strength" value="" name="password">
                        <div id="password-strength-status"></div>
                      </div>
                    </div>
                    <div class="form-group form-group-last row">
                      <label class="col-xl-3 col-lg-3 col-form-label">Yeni Şifre (Tekrar)</label>
                      <div class="col-lg-9 col-xl-6">
                        <input type="password" class="form-control" value="" name="retype-password">
                        <div class="retype-result"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                  <div class="row">
                    <div class="col-lg-3 col-xl-3">
                    </div>
                    <div class="col-lg-9 col-xl-9">
                      <button type="submit" class="btn btn-brand btn-bold">Güncelle</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--End:: App Content-->
  </div>

  <!--End::App-->
</div>

<!-- end:: Content -->

@endsection

@section('scripts')

@stop

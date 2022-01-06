{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <!--begin:: Widgets/Notifications-->
      <div class="card">
        <div class="card-header flex-wrap">
          <div class="card-title">
              <h3 class="card-label">Anket</h3>
          </div>
        </div>
        <div class="card-body">
        <form class="kt-form kt-form--label-right general-form" method="POST" action="{{ route('form-save') }}" >
          @csrf
          <input type="hidden" name="id" value="{{ $form->id ?? '' }}">
            <div class="card-body">
              <div class="kt-section kt-section--first">
                <div class="kt-section__body">
          
                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">* Anket Başlığı</label>
                    <div class="col-lg-9 col-xl-6">
                        <input class="form-control" type="text" required name="title" value="{{ $form->title ?? '' }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">* Anket Başlangıç Tarihi</label>
                    <div class="col-lg-9 col-xl-6">
                        <input class="form-control date-format" type="text" required name="start_at" value="{{ isset($form->start_at) ? date('d-m-Y', strtotime($form->start_at)) : '' }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">* Anket Bitiş Tarihi</label>
                    <div class="col-lg-9 col-xl-6">
                        <input class="form-control date-format" type="text" required name="end_at" value="{{ isset($form->end_at) ? date('d-m-Y', strtotime($form->end_at)) : '' }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Açıklama</label>
                    <div class="col-lg-9 col-xl-6">
                        <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $form->description ?? '' }}</textarea>
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
                    <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!--end:: Widgets/Notifications-->
    </div>
  </div>
</div>

								
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script>
$('.summernote').summernote({
  height: 150,
  toolbar: [// [groupName, [list of button]]
  ['style', ['bold', 'italic', 'underline', 'clear']], ['fontsize', ['fontsize']], ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']]]
});
</script>

@endsection
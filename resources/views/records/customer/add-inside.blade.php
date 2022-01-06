<input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
<div class="row">
  <div class="col-sm-8">
      <div class="row">
        <div class="col-lg-9 col-xl-6">
            <div class="image-input image-input-outline" id="kt_image_4" style="margin:10px 0px 30px;">
                @isset(Auth::user()->avatar)
                  <div class="image-input-wrapper" style="background-image: url({{ Storage::url('uploads/company/') }}{{ $detail->logo ?? '' }})"></div>
                @else 
                  <div class="image-input-wrapper" style="background-image: url(/users/100_1.jpg)"></div>
                @endisset

                  <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                    <i class="fa fa-pen icon-sm text-muted"></i>
                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
                    <input type="hidden" name="profile_avatar_remove"/>
                  </label>

                  <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                  </span>

                  <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                  </span>
                </div>
        </div>
      </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Şirket Unvanı',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => true,
                'name' => 'title',
                'class' => 'get-companies',
                'value' => isset($detail->title) ? $detail->title : null
            ])
            </div>
        </div>
        <div class="col-sm-4">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Şirket Kısa Adı',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => true,
                'name' => 'code',
                'value' => isset($detail->code) ? $detail->code : null
            ])
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <strong>İrtibat Kişi(ler):</strong>
        </div>
    </div>

    <div class="repeater" style="margin-bottom:55px;">
        <div class="form-group form-group-last row ">
            <div data-repeater-list="personel" class="col-lg-9">
            <div data-repeater-item class="form-group row align-items-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-sm-6">
                            @include('components.forms.input', [
                                'label' => 'İsim',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'name' => 'name',
                                'class' => '',
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('components.forms.input', [
                                'label' => 'Soyisim',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'name' => 'surname',
                                'class' => '',
                            ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('components.forms.input', [
                                'label' => 'Telefon Numarası',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'name' => 'phone',
                                'class' => 'phone',
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('components.forms.input', [
                                'label' => 'E-mail',
                                'placeholder' => '',
                                'type' => 'text',
                                'help' => '',
                                'name' => 'email',
                                'class' => '',
                            ])
                        </div>
                    </div>
                </div>       
                <div class="col-md-2">
                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-light-danger btn-bold btn-icon">
                    <i class="la la-trash-o"></i>
                    </a>
                </div>
            </div>
            </div>
        </div>
        <div class="form-group form-group-last row">
        <label class="col-xl-2 col-lg-2 col-form-label"></label>
            <div class="col-lg-4">
            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-light-info">
                <i class="la la-plus"></i> Yeni Ekle
            </a>
            </div>
        </div>
    </div>

      <div class="row">
          <div class="col-sm-12">
              <button class="btn btn-primary" type="submit">Kaydet</button>
          </div>
      </div>
  </div>
</div>
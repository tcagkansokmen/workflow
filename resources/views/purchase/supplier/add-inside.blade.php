<input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
<div class="row">
  <div class="col-sm-8">
      <div class="row">
        <div class="col-lg-9 col-xl-6">
            <div class="image-input image-input-outline" id="kt_image_4" style="margin:10px 0px 30px;">
                <div class="image-input-wrapper" style="background-image: url(/users/100_1.jpg)"></div>

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
                'class' => 'get-suppliers',
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
        <div class="col-sm-6">
            <div class="form-group">
                <label style="display:block;">* Şehir</label>
                @component('components.forms.select', [
                'required' => false,
                'name' => 'city_id',
                'value' => $detail->city_id ?? '',
                'values' => $authenticated->cities() ?? array(),
                'class' => 'select2-standard pick-city',
                ])
                @endcomponent
            </div>
        </div>
        <div class="col-sm-6">
                <label style="display:block;">İlçe</label>
                @component('components.forms.select', [
                'required' => false,
                'name' => 'county_id',
                'value' => $detail->county_id ?? '',
                'values' => $counties ?? array(),
                'class' => 'select2-standard pick-county',
                ])
                @endcomponent
        </div>
        <div class="col-sm-6">
            @include('components.forms.input', [
                'label' => 'Adres',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'name' => 'address',
                'class' => '',
                'value' => $detail->address ?? null
            ])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <strong>İrtibat Kişisi:</strong>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            @include('components.forms.input', [
                'label' => 'İsim',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'name' => 'name',
                'class' => '',
                'value' => $detail->name ?? null
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
                'value' => $detail->surname ?? null
            ])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            @include('components.forms.input', [
                'label' => '* Telefon Numarası',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'name' => 'phone',
                'class' => 'phone',
                'value' => $detail->phone ?? null
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
                'value' => $detail->email ?? null
            ])
        </div>
    </div>

      <div class="row">
          <div class="col-sm-12">
              <button class="btn btn-primary" type="submit">Kaydet</button>
          </div>
      </div>
  </div>
</div>
<input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
<div class="row">
  <div class="col-sm-12">
    <div class="row">
        <div class="col-sm-8">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Proje Adı',
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

        <div class="col-sm-8">
            <div class="form-group">
                <label style="display:block;">* Müşteri</label>
                @component('components.forms.select', [
                'required' => false,
                'name' => 'customer_id',
                'value' => $detail->customer_id ?? '',
                'values' => $authenticated->customers() ?? array(),
                'class' => 'getting-customers',
                ])
                @endcomponent
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <label style="display:block;">Proje Açıklaması</label>
            <textarea class="form-control" name="description">{{ $detail->description ?? null }}</textarea>
        </div>
    </div>
    <div class="row mt-5 mb-5">
        <div class="col-sm-12">
            <div class="checkbox-inline">
                <label class="checkbox checkbox-success">
                    <input type="checkbox" name="send_address" value="1" {{ isset($detail)&&isset($detail->address) ? 'checked' : '' }} /> 
                    <span></span>
                    Adres Bilgisi Girmek İstiyorum
                </label>
            </div>
        </div>
        <div class="col-sm-4 show-address {{ isset($detail)&&isset($detail->address) ? 'checked' : '' }} ">
            <div class="form-group">
                <label style="display:block;">Şehir</label>
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
        <div class="col-sm-4 show-address {{ isset($detail)&&isset($detail->address) ? 'checked' : '' }}">
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
        <div class="col-sm-8 show-address {{ isset($detail)&&isset($detail->address) ? 'checked' : '' }}">
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
        <div class="col-sm-4">
            @include('components.forms.input', [
                'label' => 'Başlangıç',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'name' => 'start_at',
                'class' => 'date-format',
                'value' => isset($detail->start_at) ? date_formatter($detail->start_at) : null
            ])
        </div>
        <div class="col-sm-4">
            @include('components.forms.input', [
                'label' => 'Bitiş',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'name' => 'end_at',
                'class' => 'date-format',
                'value' => isset($detail->end_at) ? date_formatter($detail->end_at) : null
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

<input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
<div class="row">
  <div class="col-sm-12">
    <div class="row">
        <div class="col-sm-8">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Ürün Adı',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => true,
                'name' => 'title',
                'class' => 'get-products',
                'value' => isset($detail->title) ? $detail->title : null
            ])
            </div>
        </div>
        <!--
        <div class="col-sm-4">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Ürün Kodu',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => true,
                'name' => 'code',
                'value' => isset($detail->code) ? $detail->code : null
            ])
            </div>
        </div>
        -->
    </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label style="display:block;">* Kategori</label>
                            @component('components.forms.select', [
                            'required' => true,
                            'name' => 'category_id',
                            'value' => $detail->category_id ?? '',
                            'values' => $authenticated->categories() ?? array(),
                            'class' => 'select2-standard',
                            ])
                            @endcomponent
                        </div>
                    </div>
                </div>
    <!--
    <div class="row">
        <div class="col-sm-4">
            <div class="typeahead">
            @include('components.forms.input', [
                'label' => 'Fiyat',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => true,
                'name' => 'price',
                'class' => 'money-format',
                'value' => isset($detail->price) ? money_formatter($detail->price) : null
            ])
            </div>
        </div>
    </div>
    -->
    <!--
    <div class="row">
        <div class="col-sm-8">
            @include('components.forms.input', [
                'label' => 'Açıklama',
                'placeholder' => '',
                'type' => 'text',
                'help' => '',
                'required' => false,
                'name' => 'description',
                'class' => '',
                'value' => $detail->description ?? null
            ])
        </div>
    </div>
    -->

    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary" type="submit">Kaydet</button>
        </div>
    </div>
  </div>
</div>
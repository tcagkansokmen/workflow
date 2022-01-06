@csrf
<style>
.file_side {
  position: relative;
  overflow: hidden;
  display: inline-block;
  cursor:pointer;
}

.file_side input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.imzalar td{
  width:33%;
}
</style>
<input type="hidden" name="id" value="{{ $detail->id ?? null }}">
<div class="row">
  <div class="col-lg-3">
    <div class="form-group">
    <label for="">Belge Tarihi</label>
      <input class="form-control date-format doc_date"  type="text"  name="doc_date" placeholder="Belge Tarihi" value="{{ isset($detail)&&$detail->is_food!=1 ? date_formatter($detail->doc_date) : '' }}">
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group">
      <label class="">* Müşteri</label>
      @component('components.forms.select', [
          'required' => false,
          'name' => 'customer_id',
          'value' => $detail->customer_id ?? '',
          'values' => $firms ?? array(),
          'class' => 'select2-standard  pick-customer',
          'attribute' => ''
      ])
      @endcomponent
    </div>
  </div>

  <div class="col-lg-3">
    <div class="form-group">
      <label class="">* Proje</label>
      @component('components.forms.select', [
          'required' => false,
          'name' => 'project_id',
          'value' => $detail->project_id ?? '',
          'values' => $projects ?? array(),
          'class' => 'select2-standard getting-projects pick-project',
          'attribute' => ''
      ])
      @endcomponent
    </div>
  </div>

  <div class="col-lg-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
      <div class="form-group">
        <label for="">Firma</label>
        <input class="form-control" type="text" name="firm" placeholder="Açıklama" value="{{ $detail->firm ?? null }}">
      </div>
    </div>
  </div>

  <div class="col-lg-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
        <div class="form-group">
          <label class="">* Harcama</label>
          @component('components.forms.select', [
              'required' => false,
              'name' => 'type',
              'value' => $detail->type ?? '',
              'values' => $expense ?? array(),
              'class' => 'select2-standard ',
              'attribute' => ''
          ])
          @endcomponent
      </div>
    </div>
  </div>

  <div class="col-lg-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
    <div class="form-group">
  <label class="">* Belge Türü</label>
      @component('components.forms.select', [
          'required' => false,
          'name' => 'expense_doc_types',
          'value' => $detail->expense_doc_types ?? '',
          'values' => $expense_doc_type ?? array(),
          'class' => 'select2-standard ',
          'attribute' => ''
      ])
      @endcomponent
  </div>
  </div>
  </div>

  <div class="col-lg-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
      <div class="form-group">
      <label for="">Belge No</label>
        <input class="form-control" type="text" name="doc_no" placeholder="Belge No" value="{{ $detail->doc_no ?? null }}">
      </div>
    </div>
  </div>


  <div class="col-lg-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
      <div class="form-group">
        <label for="">Tutar (KDV Dahil)</label>
          <input class="form-control money-format"  type="text" name="price" placeholder="Tutar" value="{{ isset($detail->price) ? money_formatter($detail->price) : '' }}">
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
      <div class="form-group">
        <label for="">Açıklama</label>
        <input class="form-control" type="text" name="description" placeholder="Açıklama" value="{{ $detail->description ?? null }}">
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="not-food {{ isset($detail->is_food)&&$detail->is_food==1 ? 'd-none' : '' }}">
      <div class="file_side">
        <input type="hidden" class="file_input" name="file_input" >
          <label for="file-upload" class="custom-file-upload btn btn-bold btn-md btn-light-primary btn-block" style="margin-bottom:0;">
            <i class="la la-plus"></i> Dosya Ekle
          </label>
          <input id="file-upload" type="file" accept=".pdf, .jpg, .png, .jpeg" capture="camera"/>
      </div>
      <div class="d-md-none kt-margin-b-10"></div>
    </div>
  </div>

  <div class="col-lg-3">
    <button type="submit" class="btn btn-success btn-block">Kaydet</button>
  </div>
  </div>
</div>
<script>
$('.select2-standard').select2()
$(".money-format").inputmask("currency", { radixPoint: ",", prefix: '' })
  $('.date-format').datepicker({
      orientation: "bottom right",
      allowInputToggle: true,
      format: 'dd-mm-yyyy',
      language: 'tr'
  }); // minimum setup for modal demo
  $(".date-format").inputmask("99-99-9999");

</script>
<div class="kanban-item">
  <input type="hidden" name="button_id" value="{{ $b->id ?? null }}">
  <div class="d-flex align-items-center">
      <div class="d-flex flex-column align-items-start">
      <input type="text" class="form-control form-control-sm mb-3" name="data[{{ $d->sef ?? $uniqid }}][button][{{ $b->sef ?? $uniqid_2 }}][name]" value="{{ $b->title ?? 'Buton Adı' }}">
        <div style="width:100%;">
          <div class="row">
            <div class="col-sm-8">
              <div class="btn-group btn-block mb-3">
                <input type="hidden" name="data[{{ $d->sef ?? $uniqid }}][button][{{ $b->sef ?? $uniqid_2 }}][type]" class="button-type" value="{{ $b->type ?? 'Kabul' }}">
                <input type="hidden" name="data[{{ $d->sef ?? $uniqid }}][button][{{ $b->sef ?? $uniqid_2 }}][color]" class="button-color" value="{{ $b->color ?? 'success' }}">
                  <button class="btn btn-light-{{ $b->color ?? 'primary' }} btn-block font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ $b->type ?? 'Türü' }}
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item pick-button-type" data-color="success" href="#"><span class="label label-xl label-dot label-success mr-3"></span><span class="name">Kabul</span></a>
                    <a class="dropdown-item pick-button-type" data-color="danger" href="#"><span class="label label-xl label-dot label-danger mr-3"></span><span class="name">Red</span></a>
                    <a class="dropdown-item pick-button-type" data-color="warning" href="#"><span class="label label-xl label-dot label-warning mr-3"></span><span class="name">Revize</span></a>
                  </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-icon input-icon-right">
                <input type="text" class="form-control form-control-sm" name="data[{{ $d->sef ?? $uniqid }}][button][{{ $b->sef ?? $uniqid_2 }}][redirect]" style="opacity:0;" placeholder="Yönlendirme"  value="{{ $b->next ?? null }}">
                <span class="pick-board">
                  <i class="fa fa-crosshairs icon-md"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
        @php 
          $groupval = isset($b) ? $b->groups->pluck('user_group_id')->toArray() : null;
        @endphp     
        @component('components.forms.select', [
            'required' => true,
            'name' => 'data['.($d->sef ?? $uniqid).'][button]['.($b->sef ?? $uniqid_2).'][group][]',
            'value' => $groupval ?? null,
            'values' => $groups ?? array(),
            'class' => 'select2-standard form-control',
            'attribute' => 'multiple'
            ])
          @endcomponent

      </div>
  </div>
</div>

<script>
$('.select2-standard').select2()
</script>

<div class="kanban-board" data-board="{{ $d->sef ?? $uniqid }}"  style="width:255px;" >
  <input type="hidden" name="item_id" value="{{ $d->id ?? null }}">
  <input type="hidden" name="data[{{ $d->sef ?? $uniqid }}][sef]" value="{{ $d->sef ?? $uniqid }}" class="uniqid">
  <header class="kanban-board-header light-{{ $d->color ?? 'dark' }}">
      <div class="kanban-title-board" style="font-size:12px">{{ $d->title ?? 'Hazırlanıyor' }}</div>
      <input type="hidden" name="data[{{ $d->sef ?? $uniqid }}][color]" class="color">
      <div>
      <span class="btn btn-sm btn-icon btn-light-primary add-new-button">
        <i class="la la-plus"></i>
      </span>
      <div class="dropdown dropdown-inline ml-2">
          <button type="button" class="btn btn-light-danger btn-icon btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="ki ki-bold-more-hor"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item pick-workflow-color" data-color="dark" href="#"><span class="label label-xl label-dot label-dark mr-3"></span> Siyah</a>
            <a class="dropdown-item pick-workflow-color" data-color="success" href="#"><span class="label label-xl label-dot label-success mr-3"></span> Yeşil</a>
            <a class="dropdown-item pick-workflow-color" data-color="warning" href="#"><span class="label label-xl label-dot label-warning mr-3"></span> Sarı</a>
            <a class="dropdown-item pick-workflow-color" data-color="primary" href="#"><span class="label label-xl label-dot label-primary mr-3"></span> Mavi</a>
            <a class="dropdown-item pick-workflow-color" data-color="danger" href="#"><span class="label label-xl label-dot label-danger mr-3"></span> Kırmızı</a>
            <a class="dropdown-item pick-workflow-color" data-color="info" href="#"><span class="label label-xl label-dot label-info mr-3"></span> Mor</a>
          </div>
        </div>
      </div>
  </header>
  <main class="kanban-drag">
  <input type="text" class="form-control form-control-sm mb-3 status-title" name="data[{{ $d->sef ?? $uniqid }}][name]" placeholder="Statü Adı" value="{{ $d->title ?? 'Hazırlanıyor' }}">
  <label class="checkbox mb-3">
      <input type="checkbox" name="data[{{ $d->sef ?? $uniqid }}][is_editable]" {{ isset($d) ? ($d->is_editable ? 'checked' : '') : 'checked' }} value="1"/>
      <span class="mr-2"></span>
      Düzenlenebilir
  </label>
  <label class="checkbox mb-3">
      <input type="checkbox" name="data[{{ $d->sef ?? $uniqid }}][is_done]" {{ isset($d)&&$d->is_done ? 'checked' : '' }} value="1"/>
      <span class="mr-2"></span>
      Tamamlandı
  </label>
  <div class="button-list">
    @isset($d)
      @foreach($d->buttons as $b)
        @include('management.workflow.button')
      @endforeach
    @endisset    
  </div>
  </main>
  <footer></footer>
</div>
{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
.nowrap{
  white-space:nowrap;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      <div class="card-toolbar">
      </div>
  </div>

  <div class="card-body">
    <div class="tablo">
      <table class="table table-striped- table-hover table-checkable standard-datatable" id="teklif-listesi">
        <thead>
          <tr>
            <th>İsim</th>
            <th>Değerlendirecek Kişiler</th>
            <th>Ortalama</th>
            <th>İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $d)
            <tr>
              <td>
              <div class="d-flex align-items-center">
                <div class="symbol symbol-light-dark flex-shrink-0">
                  @if($d->user->avatar)
                    <a href="#" class=" kt-media kt-margin-r-5 t-5 symbol  symbol-45" >
                      <img src="{{ Storage::url('uploads/users') }}/{{ $d->user->avatar }}" alt="image">
                    </a>
                  @else 
                    <span class="symbol-label font-size-h5 kt-margin-r-5 t-5">
                      <span>{{ strtoupper(substr($d->user->name, 0, 2)) }}</span>
                    </span>
                  @endif
                </div>
                <div class="ml-4">
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->user->name }}</div>
                  <a href="#" class="text-muted font-weight-bold text-hover-primary">{{ $d->user->title }}</a>
                </div>
              </div>
              </td>
              <td>
                <span class="btn btn-light-success">{{ count($d->raters) }}</span>
              </td>
              <td>
              @if($d->toplam_puan>4)
                <span class="btn btn-light-success">{{ $d->toplam_puan ?? '0.00' }}</span>
              @elseif($d->toplam_puan>3)
                <span class="btn btn-light-primary">{{ $d->toplam_puan ?? '0.00' }}</span>
              @else
                <span class="btn btn-light-danger">{{ $d->toplam_puan ?? '0.00' }}</span>
              @endif
              </td>
              <td>
                <a href="#" 
                
                data-toggle="tooltip"
                class="btn btn btn-icon btn-light btn-hover-success btn-sm call-bdo-modal "
                data-size="medium" 
                data-url="{{ route('user-rater-list', ['id' => $d->id]) }}"
                title="Değerlendiren Listesi">
                  <i class="la la-users text-success"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
  </script>
<script>
    $('.repeater').repeater({
      initEmpty: false,
      defaultValues: {
          'text-input': 'foo'
      },
      show: function () {
          $(this).slideDown();
          formatlar()

          $(this).show(function(){
            $(this).find('.select2-container').remove(); // bind $(this) so if you have more select2 field outside repeater then it doesnt removed
            $('.select2-standard').select2();
        });
      },
      hide: function (deleteElement) {
          $(this).slideUp(deleteElement);
      }
    });
  // Dual Listbox
  var $this = $('.dual-listbox-1');

  // init dual listbox
  var dualListBox = new DualListbox($this.get(0), {
    addEvent: function(value) {
      $.ajax({
        type: "POST",
        url: "{{ route('save-active-project') }}",
        dataType: 'json',
        data: 'id={{ $project->id ?? '' }}&user_id='+value,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        error: function(){

        },
        beforeSubmit: function(){

        },
        success: function(item){
          
        }
      });
    },
    removeEvent: function(value) {  
      $.ajax({
        type: "POST",
        url: "{{ route('save-active-project') }}",
        dataType: 'json',
        data: 'id={{ $project->id ?? '' }}&user_id='+value+"&sil=1",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      })
    },
    availableTitle: 'Seçenekler',
    selectedTitle: 'Seçilenler',
    addButtonText: 'Ekle',
    removeButtonText: 'Kaldır',
    addAllButtonText: 'Tümünü Ekle',
    removeAllButtonText: 'Tümünü Kaldır',
  });

  var $this = $('.dual-listbox-2');

  // init dual listbox
  var dualListBox = new DualListbox($this.get(0), {
    addEvent: function(value) {
      $.ajax({
        type: "POST",
        url: "{{ route('save-formal-project') }}",
        dataType: 'json',
        data: 'id={{ $project->id ?? '' }}&user_id='+value,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        error: function(){

        },
        beforeSubmit: function(){

        },
        success: function(item){
          
        }
      });
    },
    removeEvent: function(value) {  
      $.ajax({
        type: "POST",
        url: "{{ route('save-formal-project') }}",
        dataType: 'json',
        data: 'id={{ $project->id ?? '' }}&user_id='+value+"&sil=1",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      })
    },
    availableTitle: 'Seçenekler',
    selectedTitle: 'Seçilenler',
    addButtonText: 'Ekle',
    removeButtonText: 'Kaldır',
    addAllButtonText: 'Tümünü Ekle',
    removeAllButtonText: 'Tümünü Kaldır',
  });

</script>
@endsection
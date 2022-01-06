<div class="card-body">
  <form class="kt-form general-form" method="POST" action="{{ route('save-active-project') }}" >
    @csrf
    <input type="hidden" name="id" value="{{ $project->id ?? '' }}">
    <select id="dual-listbox_1" name="aktif[]" class="dual-listbox-1" multiple>
      @foreach($users as $u)
        <option value="{{ $u->id }}"
        @if(in_array($u->id, $raters))
        selected
        @endif
        >{{ $u->name }} {{ $u->surname }}</option>
      @endforeach
    </select>
  </form>
</div>
  <script>
var $this = $('.dual-listbox-1');

// init dual listbox
var dualListBox = new DualListbox($this.get(0), {
  addEvent: function(value) {
    $.ajax({
      type: "POST",
      url: "{{ route('add-new-rater') }}",
      dataType: 'json',
      data: 'yearly_rating_id={{ $yearly_rating_id ?? '' }}&user_id={{ $user_id }}&rater_id='+value,
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
      url: "{{ route('remove-new-rater') }}",
      dataType: 'json',
      data: 'yearly_rating_id={{ $yearly_rating_id ?? '' }}&user_id={{ $user_id }}&rater_id='+value,
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
  availableTitle: 'Seçenekler',
  selectedTitle: 'Seçilenler',
  addButtonText: 'Ekle',
  removeButtonText: 'Kaldır',
  addAllButtonText: 'Tümünü Ekle',
  removeAllButtonText: 'Tümünü Kaldır',
});
</script>
{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<form action="{{ route('save-workflow') }}" method="POST" class="general-form">
@csrf
  <div class="card card-custom">
    <input type="hidden" name="key" value="offer">
      <div class="card-header flex-wrap border-1 pt-3 pb-3">
          <div class="card-title">
            <h3 class="card-label">
                Teklif Süreci
            </h3>
          </div>
          <div class="card-toolbar">
            <a href="#" class="btn btn-light-success add-board-item">
              <i class="la la-plus mr-3"></i> Yeni Statü Ekle
            </a>
            &nbsp;&nbsp;
            <button class="btn btn-light-primary" type="submit">Kaydet</button>
          </div>
      </div>
      <div class="card-body">
          <div class="kanban-container kanban-list">
            @foreach($offers as $d)
              @include('management.workflow.kanban-item')
            @endforeach
          </div>
      </div>
  </div>
</form>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/kanban/kanban.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
.pick-workflow-color, .pick-button-type{
  display:flex;
  align-items:center;
}
.kanban-board-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.kanban-board.pick-shadow{
  position:relative;
  cursor:pointer !important;
}
.kanban-board.pick-shadow:before{
  content:'';
  background-color:rgba(0,0,0,0.45);
  position:absolute;
  left:0;
  top:0;
  width:100%;
  height:100%;
  z-index:4;
  cursor:pointer !important;
}
.kanban-board.show-picked:before{
  content:'';
  background-color:rgba(27, 197, 189,0.35);
  position:absolute;
  left:0;
  top:0;
  width:100%;
  height:100%;
  z-index:4;
  cursor:pointer !important;
}
.kanban-board.pick-shadow:hover:before{
  background-color:rgba(77, 169, 255, 0.55);
}
.pick-board{
  cursor:pointer;
}
.pick-board:hover{
  opacity:0.85;
}
</style>
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script>
$(document).ready(function(){
  $("body").on('keyup', '.status-title', function(e){
    var val = $(this).val()
    $(this).closest('.kanban-board').find('.kanban-title-board').html(val)
  });

  $("body").on('click', '.pick-workflow-color', function(e){
    e.preventDefault();

    var color = $(this).attr('data-color');
    $(this).closest('.kanban-board-header').removeClass(function (index, css) {
      return (css.match (/\blight\S+/g) || []).join(' '); 
    });

    $(this).closest('.kanban-board-header').addClass('light-'+color)
    $(this).closest('.kanban-board-header').find('.color').val(color)
  });

  $("body").on('click', '.pick-button-type', function(e){
    e.preventDefault();

    var color = $(this).attr('data-color');
    var name = $(this).find('.name').html();

    $(this).closest('.btn-group').find('button').removeClass(function (index, css) {
      return (css.match (/\bbtn-light\S+/g) || []).join(' '); 
    });

    $(this).closest('.btn-group').find('button').addClass('btn-light-'+color).html(name)
    $(this).closest('.btn-group').find('.button-color').val(color)
    $(this).closest('.btn-group').find('.button-type').val(name)
    
  });

  $("body").on('click', '.add-new-button', function(e){
    e.preventDefault();

    var key = $(this).closest('form').find('input[name=key]').val();
    var uniqid = $(this).closest('.kanban-board').find('.uniqid').val();
    var thi = $(this)
    
    $.ajax({
      url: "{{ route('workflow-button') }}?key="+key+"&uniqid="+uniqid,
      dataType: 'html',
      type: 'get',
      success: function(item){
        thi.closest('.kanban-board').find('.button-list').append(item)
      }
    });
  });

  $("body").on('click', '.add-board-item', function(e){
    e.preventDefault();

    var key = $(this).closest('form').find('input[name=key]').val();
    var thi = $(this)
    $.ajax({
      url: "{{ route('workflow-item') }}",
      dataType: 'html',
      type: 'get',
      success: function(item){
        thi.closest('.card').find('.kanban-list').append(item)
      }
    });
  });

  $("body").on('click', '.pick-board', function(e){
    e.preventDefault();
      var index = $(this).closest('.kanban-board').index()

    $(this).addClass('active').addClass('text-primary')
    $(this).closest('.kanban-list').find('.kanban-board:not(:eq('+index+'))').addClass('pick-shadow')
  });

  $("body").on('click', '.pick-board.active', function(e){
    e.preventDefault();
      var index = $(this).closest('.kanban-board').index()

    $(this).removeClass('active').removeClass('text-primary')
    $(this).closest('.kanban-list').find('.kanban-board').removeClass('pick-shadow')
  });

  $("body").on('click', '.kanban-board.pick-shadow', function(e){
    e.preventDefault();

    var id = $(this).attr('data-board')

    $('.pick-board.active').closest('.input-icon').find('input').val(id)
    
    $('.pick-board').removeClass('active').removeClass('text-primary')
    $('.kanban-board').removeClass('pick-shadow')
  });
  

  $("body").on({
    mouseenter: function () {
      var index = $(this).closest('.kanban-board').index()
      var val = $(this).closest('.input-icon').find('input').val()

      $('.kanban-board[data-board='+val+']').addClass('show-picked')

      //$('.kanban-board:not(:eq('+index+'))').addClass('show-picked')
    },
    mouseleave: function () {
      $('.kanban-board').removeClass('show-picked')
    }
  }, '.pick-board');
});
</script>
@endsection

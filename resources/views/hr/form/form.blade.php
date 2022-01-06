{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
.form-wrapper{
background-image: url('/images/bg2.jpg');
background-size: cover;
height: 100%;
display: block;
background-attachment:fixed;
}
p, li, h1,h3,h4,h5{
  color:#191919;
  font-weight:500;
  font-size:16px;
}
.kt-portlet.ust-olan{
  background:rgba(255,255,255,1);
}
.kt-portlet.ust-olan .kt-portlet__head,
.kt-portlet.ust-olan .card-body{
  /*background:none;*/
}
.kt-portlet.alt-olan{
  /*background:#f3f3f3;*/
}
.kt-portlet.alt-olan .kt-portlet__head,
.kt-portlet.alt-olan .card-body{
  background:none;
}
.form-group{
}
label.baslik{
  color:#191919;
  font-size:16px !important;
  font-weight:bold !important;
}
body {
  counter-reset: section;                     
}
.datas{
  position:relative;
}
.datas::before {
  /*counter-increment: section;            
  content: counter(section) ". ";    
  position:absolute;
  left:0px;
  font-size:18px;
  font-weight:bold;
  top:-2px;*/
}
label.kt-checkbox,
label.kt-radio,
label.kt-radiobox{
  font-size:14px;
  font-weight:500;
}

.form-control{
  border:1px solid #262053;
  background:#f7f7f7;
  color:#262053;
  font-size:14px;
  font-weight:600;
  line-height:32px;
  height:auto;
}
.kt-radio,
.kt-checkbox{
  margin-right:15px;
  padding-left:20px;
}

</style>

<div style="background-image: url('{{ Storage::url('uploads/form/') }}{{ $form['background'] }}');">
  <div class="card card-custom">
    <div class="card-header flex-wrap">
        <div class="card-title">
            <h3 class="card-label">{{ $form->title }}</h3>
        </div>
    </div>
    <div class="card-body">
      <form action="{{ route('contact-form-send') }}" method="POST" class="send-form">
        @csrf
        <input type="hidden" name="form_id" value="{{ $form->id }}">
        <div class="row">
          <div class="col-sm-6">
            @if( ($form->start_at < date("Y-m-d") && $form->end_at > date("Y-m-d")) || !$form->end_at || Auth::check() )
            <div style="margin-top:25px;">
                @foreach($fields as $f)
                @php 
                error_reporting(0);
                $answer = '';
                if(isset($answered)){
                $ans = $answered[$f['name']];
                $pusharray = array();
                foreach($ans['answers'] as $an){
                $pusharray[] = $an['answer'];
                }
                $answer = implode(",", $pusharray);
                }
                @endphp
                @include('module.'.$f->type, 
                [
                'all' => array(
                'is_required' => $f['is_required'] ?? false,
                'label' => $f['label'],
                'name' => $f['name'] ?? uniqid(),
                'class' => $f['form_class'] ?? '',
                'values' => $f['values'] ?? '',
                'value' => $answer ?? ''
                )
                ])
                @endforeach
            </div>
            @else 
            @endif
            @if( ($form->start_at < date("Y-m-d") && $form->end_at > date("Y-m-d")) || !$form->end_at || Auth::check() )
            <div class="form-group mt-15">
                <button type="submit" class="btn btn-success btn-lg">Formu Gönder</button>
            </div>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script>
$('.send-form').ajaxForm({ 
  beforeSubmit:  function(){
      $(".formprogress").show();
  },
  error: function(){
    swal.fire({
      "title": "",
      "text": "Kaydedilemedi",
      "type": "warning",
      "confirmButtonClass": "btn btn-secondary"
    });

  },
  dataType:  'json', 
  success:   function(item){
      $(".formprogress").hide();
      if(item.status){
        swal.fire({
          "title": "",
          "text": item.message,
          "type": "success",
          "confirmButtonClass": "btn btn-secondary"
        });

        setTimeout(function(){
          window.location.href = item.redirect;
        }, 1000);
      }else{
        swal.fire({
          "title": "",
          "text": item.message,
          "type": "warning",
          "confirmButtonClass": "btn btn-secondary"
        });

      }
  }
}); 
</script>
@endsection

{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
.form-wrapper{
  padding-top: 75px;
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
.kt-portlet.ust-olan .kt-portlet__body{
  /*background:none;*/
}
.kt-portlet.alt-olan{
  /*background:#f3f3f3;*/
}
.kt-portlet.alt-olan .kt-portlet__head,
.kt-portlet.alt-olan .kt-portlet__body{
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
  padding-left:30px;
}
.datas::before {
  counter-increment: section;            
  content: counter(section) ". ";    
  position:absolute;
  left:0px;
  font-size:18px;
  font-weight:bold;
  top:-2px;
}
label.kt-checkbox,
label.kt-radio,
label.kt-radiobox{
  font-size:14px;
  font-weight:500;
}

.form-control{
  border:2px solid #262053;
  background:#f7f7f7;
  color:#262053;
  font-size:14px;
  font-weight:600;
  line-height:32px;
  height:auto;
}

</style>
<div class="form-wrapper"  style="background-image: url('{{ Storage::url('uploads/form/') }}{{ $form['background'] }}'); min-height:100vh;">
  <div class="col-sm-6 offset-sm-3">
    <div class="card">

      <div class="card-body">
        
        <div>
          <div class="row">
            <div class="col-sm-12">
            <div class="alert alert-outline-success" role="alert">
						  	<strong>Teşekkürler</strong>&nbsp; Anket başarıyla kaydedildi.
						</div>
            </div>
          </div>
        </div>
      </div>


    </div>

  </div>

</div>
@endsection
{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
@endsection
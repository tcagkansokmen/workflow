{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
@php
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

@endphp
<div class="container">
<div class="row">
  <div class="col-sm-10 col-lg-8 col-xl-6">
      <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap">
            <div class="card-title">
                <h3 class="card-label">Cevaplar </h3>
            </div>
            <div class="card-toolbar">
              <a href="{{ route('form-table', ['form_id' => $form_single->id]) }}" class="btn btn-light-info btn-bold btn-icon-h kt-margin-l-10">
                Tablo Olarak Görüntüle
              </a>
            </div>
        </div>
        <div class="card-body">
          <form action="{{ route('form-answers', ['form_id' => $form_single->id]) }}" method="GET">
            <div class="row">
              <div class="col-sm-5">
                <select name="label" id="" class="custom-select pick-label">
                  <option value="">Soru Seçiniz</option>
                  @foreach($answers as $a)
                    <option value="{{ $a->id }}">{{ $a->label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-5">
                <select name="answer" id="" class="custom-select pick-answer">
                  <option value="">Cevap Seçiniz</option>
                </select>
              </div>
              <div class="col-sm-2">
                <button type="submit" class="btn btn-light-primary">Filtrele</button>
              </div>
            </div>
            @if(Request::get('label'))
            <h5 style="margin-top:25px;"><strong>{{ $answers[0]->label }}</strong> sorusuna "{{ Request::get('answer') }}" yanıtını verenler</h5>
            @endif
          </form>
        </div>
      </div>
  @foreach($answers as $a)
    @isset($a->sets)
    @if($a->sets->type == "free")
      <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap">
            <div class="card-title">
                <h3 class="card-label">{{ $a->label }}</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
          <div class="kt-scroll" data-scroll="true" data-height="300" data-scrollbar-shown="true">
            <!--begin: Datatable -->
            <table class="table table-striped">
              <tbody>
                @foreach($a->answers as $c)
                <tr>
                  <td>
                    {{ $c->answer }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @elseif($a->sets->type == "choice")
      @php 

        $datasets = array();
        $grafikdata = array();
        $grafiklabel = array();
        $grafikbg = array();
        $labels = array();
        $data = array();
        $background = array();
        $total = 0;
        $myown = array();
        error_reporting(0);

          foreach($a->answers as $c){
            $data[Str::slug($c->answer)]['adet'] += 1;
            $data[Str::slug($c->answer)]['answer'] = $c->answer;
            $data[Str::slug($c->answer)]['color'] = "#".random_color();
            $total += 1;
          }
          
          foreach($data as $key => $val){
            $grafikdata[] = $val['adet'];
            $grafiklabel[] = $val['answer'];
            $grafikbg[] = $val['color'];
          }


          $datasets[0]["data"] = $grafikdata;
          $datasets[0]["backgroundColor"] = $grafikbg;
          
          $general["datasets"] = $datasets;
          $general["labels"] = $grafiklabel;

          $json = json_encode($general);
          
      @endphp
    <!--begin:: Widgets/Profit Share-->
    <div class="card card-custom gutter-b">
      <div class="kt-widget14">
        <div class="card-header flex-wrap">
            <div class="card-title">
                <h3 class="card-label">{{ $a->label }}</h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
          <div class="kt-widget14__chart">
            <div class="kt-widget14__stat">{{ $total }}</div>
            <div class="my-chart" data-sets="{{ $json }}">
            <canvas class="kt_chart_profit_share" style="height: 250px; width: 250px;"></canvas>
            </div>
          </div>
          <div class="kt-widget14__legends">
            @foreach($data as $g)
            <div class="kt-widget14__legend">
              <span class="kt-widget14__bullet kt-bg-success" style="background:{{ $g['color'] }} !important;"></span>
              <span class="kt-widget14__stats">{{ $g['adet'] }} adet {{ $g['answer'] }}</span>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    @endif
    @endisset
  @endforeach
  </div>
</div>
</div>	
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
.kt-widget14__chart {
position: relative;
}
.kt-widget14__chart .kt-widget14__stat {
display: flex;
justify-content: center;
align-items: center;
position: absolute;
left: 0;
right: 0;
bottom: 0;
top: 0;
font-size: 2.2rem;
font-weight: 500;
color: #a2a5b9;
opacity: 0.7;
}
.kt-widget14__legends {
padding-left: 0.5rem;
flex-grow: 1;
}
.kt-widget14__legend {
display: flex;
align-items: center;
}
.kt-widget14__legend .kt-widget14__bullet {
width: 1.5rem;
height: 0.45rem;
border-radius: 1.1rem;
}
.kt-widget14__legend .kt-widget14__stats {
color: #74788d;
font-weight: 500;
flex: 1;
padding-left: 1rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>

$(document).ready(function(){
$(".my-chart").each(function(){
    var bb = $(this).attr('data-sets');
    var a = $.parseJSON(bb);
    console.log(a);
    console.log(bb);
    var config = {
        type: 'doughnut',
        data: a,
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Technology'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10, 
                caretPadding: 0,
                displayColors: false,
                backgroundColor: '#1BC5BD',
                titleFontColor: '#ffffff', 
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    };

    var ctx = $(this).find('canvas');
    var myDoughnut = new Chart(ctx, config);
});
});
</script>
@endsection
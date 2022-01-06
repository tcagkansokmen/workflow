{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
          <h3 class="card-label">{{ $page_title ?? null }}
              <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
          </h3>
      </div>
      @php
        $current_year = Request::get('year') ? Request::get('year') : date('Y');
        $current_month = Request::get('month') ? Request::get('month') : str_replace('0', '', date('m'));
      @endphp
      <div class="card-toolbar">
          <form action="{{ route('calendar-confirmation-table') }}">
            <div class="form-group" style="margin-bottom:0; display:flex;">
              <select name="year" id="" class="select2-standard" style="width:150px;">
                @foreach(range(date('Y'), date('Y')-5) as $y)
                    <option value="{{ $y }}" {{ $current_year==$y ? 'selected' : '' }} >{{ $y }}</option>
                @endforeach
              </select>&nbsp;&nbsp;
              <select name="month" id="" class="select2-standard" style="width:150px;">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ $current_month==$month ? 'selected' : (date('m')==$month ? 'selected' : '') }}  >{{ $month }}. Ay</option>
                @endforeach
              </select>&nbsp;&nbsp;
              <button class="btn btn-success" type="submit">Filtrele</button>
            </div>
          </form>
      </div>
  </div>

  <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-checkable" id="customers">
              <thead>
                <tr>
                  <th>Tarih</th>
                  <th>Toplam Saat</th>
                </tr>
              </thead>
              <tbody>
              @php 
                $totalhour=0;
              @endphp
                @foreach($dates as $d)
                  @php 
                    $dailyhour=0;
                  @endphp
                  <tr>
                    <td>{{ Carbon\Carbon::parse($d)->formatLocalized('%a, %d %B %Y') }}</td>
                    <td>
                    @isset($result[$d])
                      @foreach($result[$d] as $r)
                        @php 
                          $start = Carbon\Carbon::parse($r['real_start_at']);
                          $end = Carbon\Carbon::parse($r['real_end_at']);
                          $diff = $end->diffInMinutes($start, true);
                          $diff_hour = round($diff/60);

                          $dailyhour += $diff_hour;
                        @endphp
                      @endforeach
                    @endisset
                    @if(isset($holidays[$d]))
                        <div class="team-card grey" >
                          <span class='name'>{{ $holidays[$d] }}</span>
                        </div>
                        @if($dailyhour)
                          <span class="btn btn-bold btn-sm btn-font-sm  btn-label-{{ $dailyhour==8 ? 'success' : 'danger' }}">{{ $dailyhour }}/8</span>
                        @endif
                    @elseif(Carbon\Carbon::parse($d)->isWeekend())
                      <div class="team-card grey" >
                        <span class='name'>Haftasonu</span>
                      </div>
                      @if($dailyhour)
                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-{{ $dailyhour==8 ? 'success' : 'danger' }}">{{ $dailyhour }}/8</span>
                      @endif
                    @else 
                      <span class="btn btn-bold btn-sm btn-font-sm  btn-label-{{ $dailyhour==8 ? 'success' : 'danger' }}">{{ $dailyhour }}/8</span>
                    @endisset

                    </td>
                  </tr>
                  @php 
                    if($dailyhour>8){
                      $dailyhour = 8;
                    }
                    $totalhour += $dailyhour;
                  @endphp
                @endforeach
                <tr class="table-warning">
                  <td>
                    <strong>Toplam Saat</strong>
                  </td>
                  <td>
                  <span class="btn btn-bold btn-sm btn-font-sm  btn-label-{{ $totalhour==$days*8 ? 'success' : 'danger' }}">{{ $totalhour }}/{{ $days*8 }}</span>
                  </td>
                </tr>
                @if($totalhour==$days*8)
                  <tr>
                    <td>
                      
                    </td>
                    <td>
                      <form action="{{ route('close-calendar', ['month'=>$month]) }}" method="POST" class="general-form">
                      @csrf
                        <button class="btn btn-primary" type="submit">Dönem Kapat</button>
                      </form>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
  </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
.nowrap{
  white-space:nowrap;
}
  
</style>
@endsection
@section('scripts')
@endsection

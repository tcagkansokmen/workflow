{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
table td{
  vertical-align:middle !important;
}
.fc-event, .fc-list-item{
  cursor:pointer;
}
.fc-past{
    background:#fafafa;
    cursor:not-allowed;
    color:rgba(0,0,0,0.35);
    font-weight:normal;
}
.fc-day-number{
    color:#111;
}
.fc-past .fc-day-number{
    color:rgba(0,0,0,0.45);
}
.fc-today{
    background:none !important;
}
.fc-today .fc-day-number{
    width:28px;
    height:28px;
    line-height:28px;
    padding:0;
    background:#eb4d3e;
    color:#fff;
    border-radius:50%;
    display:inline-block;
    text-align:center;
}
.fc-sat { color:rgba(0,0,0,0.55); background:#f5f5f5;
    font-weight:bold; }
.fc-sun { color:rgba(0,0,0,0.55); background:#f5f5f5;
    font-weight:bold;  }
.fc-day-grid-event .fc-content{
    white-space:normal;
}
.team-card{
  background:rgba(10,187,135, 0.15);
  color:rgba(10,187,135, 1);
  padding:6px;
  border-radius:3px;
  margin-bottom:6px;
  min-width:120px;
}
.team-card.grey{
  background:rgba(0,0,0,0.05)!important;
  color:rgba(0,0,0,0.55) !important;
}
.team-card span.name{
  display:block;
  font-size:10px;
}
.team-card span.name{
  display:block;
  font-size:12px;
  font-weight:bold;
}
th{
  min-width:125px;
}
</style>
@php
  $current_year = Request::get('year') ? Request::get('year') : date('Y');
  $current_month = Request::get('month') ? Request::get('month') : str_replace('0', '', date('m'));
@endphp
<!-- begin:: Content -->
<div class="card card-custom">
  <div class="card-header flex-wrap">
      <div class="card-title">
      <h3 class="card-label">{{ $page_title ?? null }}
          <div class="text-muted pt-2 font-size-sm">{{ $page_description ?? null }}</div>
      </h3>
      <div class="card-toolbar">
        <form action="{{ route('calendar-team') }}">
          <div class="form-group" style="margin-bottom:0; display:flex;">
            <select name="year" id="" class="select2-standard form-control" style="width:125px;">
              @foreach(range(date('Y'), date('Y')-5) as $y)
                  <option value="{{ $y }}" {{ $current_year==$y ? 'selected' : '' }} >{{ $y }}</option>
              @endforeach
            </select>&nbsp;&nbsp;
            <select name="month" id="" class="select2-standard form-control" style="width:125px;">
              @foreach(range(1, 12) as $month)
                  <option value="{{ $month }}" {{ $current_month==$month ? 'selected' : (date('m')==$month ? 'selected' : '') }}  >{{ $month }}</option>
              @endforeach
            </select>&nbsp;&nbsp;
            <button class="btn btn-success" type="submit">Filtrele</button>
          </div>
        </form>
      </div>
    </div>
    <div class="card-body" style="overflow:scroll;">
      <div class="row">
        <div class="col-sm-12">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-checkable" id="customers">
              <thead>
                <tr>
                  <th>#</th>
                  @foreach($gunler as $d)
                    <th>{{ $d }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($users as $r)
                  <tr>
                    <td>
                      {{ $r['name'] }} {{ $r['surname'] }}
                    </td>

                        @foreach($dates as $d)
                          <td>
                            @isset($rows[$r['id']][$d])
                              @foreach($rows[$r['id']][$d] as $data)
                                <div class="team-card" >
                                  <span class='name'>{{ $data['project']['name'] }}</span>
                                  <span class='date'>{{ date('H:i', strtotime($data['start_at'])) }} - {{ date('H:i', strtotime($data['end_at'])) }}</span>
                                </div>
                              @endforeach
                            @endisset
                            @isset($holidays[$d])
                              <div class="team-card grey" >
                                <span class='name'>{{ $holidays[$d] }}</span>
                              </div>
                            @elseif(Carbon\Carbon::parse($d)->isWeekend())
                              <div class="team-card grey" >
                                <span class='name'>Haftasonu</span>
                              </div>
                            @endif
                            @if(Carbon\Carbon::now()<=Carbon\Carbon::parse($d))
                            <a href="{{ route('add-calendar') }}/?date={{ $d }}&allow_person=1" class="btn btn-light-info btn-bold btn-icon-h btn-sm  call-bdo-modal" 
                            data-size="medium" 
                            data-url="{{ route('add-calendar') }}/?date={{ $d }}&allow_person=1">
                              +
                            </a>
                            @endif
                          </td>
                        @endforeach

                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
@endsection
@section('scripts')

@endsection

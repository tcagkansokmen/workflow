{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-sm-6 offset-sm-3">
      <div class="card">
        <div class="card-header flex-wrap">
            <div class="card-title">
                <h3 class="card-label">Form Cevapları</h3>
            </div>
            <div class="card-toolbar">
          </div>
        </div>
        <div class="card-body">
          <table class="table table-striped- table-hover table-checkable" id="filtreli_form">
            <thead>
              <tr>
                <th>#</th>
                <th>Soru</th>
                <th>Cevap</th>
              </tr>
            </thead>
            <tbody>
              @foreach($answers as $a)
                @isset($a->answers[0])
                <tr>
                  <td></td>
                  <td>{{ $a->label }}</td>
                  <td>
                    @foreach($a->answers as $as)
                      {{ $as->answer ?? '' }}
                      @if($loop->last)
                          {{ '' }}
                      @else 
                          {{ ',' }}
                      @endif
                    @endforeach
                  </td>
                </tr>
                @endisset 
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
@endsection
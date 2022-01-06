{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="row">

<div class="col-xl-12 col-lg-12">
  <!--begin:: Widgets/Notifications-->
  <div class="card">
    <div class="card-header flex-wrap">
        <div class="card-title">
            <h3 class="card-label">{{ isset($form_single) ? $form_single->title.' formuna ait kişiler' : '' }}</h3>
        </div>
        <div class="card-toolbar">
        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Toplam Kişi Sayısı: {{ count($contacts) }}</span>
      </div>
    </div>
    <div class="card-body">

    <form action="{{ route('form-contacts', ['form_id' => $form_single->id]) }}" method="GET">
    <!--begin: Datatable -->
    <table class="table table-striped- table-hover table-checkable" id="filtreli_form">
      <thead>
        <tr>
          <th>
              
          </th>
          <th>
              <input type="text" class="form-control" name="name" value="{{ Request()->name ?? '' }}" placeholder="İsim">
          </th>
          <th>
              <input type="text" class="form-control" name="title" value="{{ Request()->title ?? '' }}" placeholder="Unvan">
          </th>
          <th>
              <input type="text" class="form-control phone" mame="phone" value="{{ Request()->phone ?? '' }}" placeholder="Telefon">
          </th>
          <th>
              <input type="email" class="form-control" name="email" value="{{ Request()->email ?? '' }}" placeholder="E-posta">
          </th>
          <th>
              <select name="cevap" id="" class="custom-select">
                <option value="">Seçiniz</option>
                <option value="1"
                @if(Request()->cevap == 1)
                  selected
                @endif
                >Cevaplandı</option>
                <option value="2"
                @if(Request()->cevap == 2)
                  selected
                @endif
                >Cevaplanmadı</option>
              </select>
          </th>
          <th>
              <select name="is_called" id="" class="custom-select">
                <option value="">Seçiniz</option>
                <option value="1"
                @if(Request()->is_called == 1)
                  selected
                @endif
                >Arandı</option>
                <option value="2"
                @if(Request()->is_called == 2)
                  selected
                @endif
                >Aranmadı</option>
              </select>
          </th>
          <th>
            <input class="form-control date-format" type="text" name="end_at" placeholder="Tarihe kadar">
          </th>
          <th>
              <button type="submit" class="btn btn-md btn-success">Filtrele</button>
          </th>
        </tr>
        <tr>
          <th>#</th>
          <th>İsim</th>
          <th>{{ __('messages.unvan') }}</th>
          <th>Telefon</th>
          <th>E-posta</th>
          <th>Cevap</th>
          <th>Durum</th>
          <th>Eklenme Tarihi</th>
          <th class="align-right">İşlem</th>
        </tr>
      </thead>
      <tbody>
        @foreach($contacts as $c)
        <tr>
          <td>
            {{ $c->id }}
          </td>
          <td nowrap>
          <strong>{{ $c->name }} {{ $c->surname }}</strong>
            @if($c->self)
            <span class="kt-badge kt-badge--danger kt-badge--md" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Liste dışı kayıt">
              <i class="la la-info"></i>
            </span>
            @endif
          </td>
          <td>
          <strong>{{ $c->title }}</strong>
          </td>
          <td>
            {{ $c->phone }}
          </td>
          <td>
            {{ $c->email }}
          </td>
          <td nowrap>
          @if($c->answers_count)
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Cevaplandı</span>
            @isset($c->answered_by)
            <span class="kt-badge kt-badge--info kt-badge--md" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="{{ $c->answered_by->name }} tarafından">
              <i class="la la-info"></i>
            </span>
            @endisset
          @else
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Cevaplamadı</span>
          @endif
          </td>
          <td>
          @if(!$c->call)
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Aranmadı</span>
          @else
            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Arandı</span>
          @endif
          </td>
          <td>{{ date('d.m.Y', strtotime($c->created_at)) }}</td>
          <td class="align-right" nowrap>
            <a href="{{ route('contact-answers', ['contact_id' => $c->id]) }}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Cevaplar">
              <i class="la la-list"></i>
            </a>
            @if(!$c->call)
              <a href="{{ route('contact-called', ['contact_id' => $c->id]) }}" class="btn btn-sm btn-success btn-icon btn-icon-md" title="Arandı">
                <i class="la la-phone"></i>
              </a>
            @else
              <a href="{{ route('contact-not-called', ['contact_id' => $c->id]) }}" class="btn btn-sm btn-danger btn-icon btn-icon-md" title="Aranmadı">
                <i class="la la-phone"></i>
              </a>
            @endif
            <a href="{{ route('form-preview', ['form_id' => $c->form_id]) }}?contact_id={{ $c->hash }}" target="_blank" class="btn btn-sm btn-brand btn-icon btn-icon-md" title="Formu Görüntüle">
              <i class="la la-eye"></i>
            </a>
              @if($c->answers_count == 0 && Auth::user()->group_id < 3)
              <a href="{{ route('contact-delete', ['user_id' => $c->id]) }}" class="btn btn-sm btn-danger btn-icon btn-icon-md" title="Kişi Sil">
                <i class="la la-trash"></i>
              </a>
              @endif

            @if($c->mail_send)
              <button class="btn btn-sm btn-success btn-icon btn-icon-md" title="Eposta gönder">
                <i class="la la-envelope"></i>
              </button>
            @else 
              <button data-id="{{ $c->hash }}" class="btn btn-sm btn-warning btn-icon btn-icon-md sendemail" title="Eposta gönder">
                <i class="la la-envelope"></i>
              </button>
            @endif
            <a href="{{ route('davetli-bilet', ['hash' => $c->hash]) }}" target="_blank" class="btn btn-sm btn-dark btn-icon btn-icon-md" title="QR Code">
              <i class="la la-qrcode"></i>
            </a>
            <a href="{{ route('davetli-yaka', ['hash' => $c->hash]) }}" target="_blank" class="btn btn-sm btn-danger btn-icon btn-icon-md" title="Yaka Kartı">
              <i class="la la-user"></i>
            </a>
          </td>
        </tr>
        @endforeach
      </tfoot>
    </table>
    </form>
    </div>
  </div>
  <!--end:: Widgets/Notifications-->
</div>
</div>


{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
@endsection
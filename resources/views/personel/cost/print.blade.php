{{-- Extends layout --}}
@extends('layout.empty')

{{-- Content --}}
@section('content')

<style>
table td{
  vertical-align:middle !important;
}
</style>
<style>
.file_side {
  position: relative;
  overflow: hidden;
  display: inline-block;
  cursor:pointer;
}

.file_side input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.imzalar td{
  width:33%;
}
</style>

<div class="card card-custom">
  <div class="card-header flex-wrap">
    <div class="card-title">
        <h3 class="card-label">
          {{ $authenticated->name }} {{ $authenticated->surname }} | {{ Carbon\Carbon::parse(Request::get('year').'-'.Request::get('month'))->formatLocalized('%B %Y') }} Masraf Formu
        </h3>
    </div>
  </div>
  <div class="card-body">
      <table class="table table-striped table-bordered table-hover table-checkable">
        <thead>
          <tr>
            <th>Tarih</th>
            <th>No</th>
            <th>Kategori</th>
            <th>Proje</th>
            <th>KM</th>
            <th>%0 KDV</th>
            <th>%8 KDV</th>
            <th>%18 KDV</th>
            <th>Araç KM</th>
            <th>KM Müşteri</th>
          </tr>
        </thead>
        <tbody class="masraf-tablosu">
        @php 
          $km = 0;
          $kdv0 = 0;
          $kdv8 = 0;
          $kdv18 = 0;
          $arackm = 0;
          $musterikm = 0;
        @endphp
          @foreach($data as $d)
          <tr>
            <td>
              {{ date('d.m.Y', strtotime($d->doc_date)) }}
            </td>
            <td>
              {{ $d->doc_no }}
            </td>
            <td>
              {{ $d->expense->name }}
            </td>
            <td>
            <div class="d-flex align-items-center">
              <div>
                  <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $d->firm['title'] }}</div>
                  <a class="text-muted font-weight-bold text-hover-primary">{{ $d->project['name'] }}</a>
              </div>
            </div>
            </td>
            <td>
              @if($d->expense->id==1||$d->expense->id==8)
                @php 
                  $km += $d->price;
                @endphp
                {{ $d->price }}
              @endif
            </td>
            <td>
              @if($d->expense->vat==0&&$d->expense->id!=1&&$d->expense->id!=8)
                @php 
                  $kdv0 += $d->price;
                @endphp
                {{ number_format($d->price*$d->factor_personel, 2, ",", ".") }}
              @endif
            </td>
            <td>
              @if($d->expense->vat==8&&$d->expense->id!=1&&$d->expense->id!=8)
                @php 
                  $kdv8 += $d->price;
                @endphp
                {{ number_format($d->price*$d->factor_personel, 2, ",", ".") }}
              @endif
            </td>
            <td>
              @if($d->expense->vat==18&&$d->expense->id!=1&&$d->expense->id!=8)
                @php 
                  $kdv18 += $d->price;
                @endphp
                {{ number_format($d->price*$d->factor_personel, 2, ",", ".") }}
              @endif
            </td>
            <td>
              @if($d->expense->id==1||$d->expense->id==8)
                @php 
                  $arackm += $d->price*$d->factor_personel;
                @endphp
                {{ number_format($d->price*$d->factor_personel, 2, ",", ".") }}
              @endif
            </td>
            <td>
              @if($d->expense->id==1||$d->expense->id==8)
                @php 
                  $musterikm += $d->price*$d->factor_customer;
                @endphp
                {{ number_format($d->price*$d->factor_customer, 2, ",", ".") }}
              @endif
            </td>
          </tr>
          @endforeach
          <tr>
            <td colspan="10" height="50"></td>
          </tr>
          <tr>
            <td colspan="4">İÇERDİĞİ KDV</td>
            <td>0,00</td>
            <td>0,00</td>
            <td>{{ number_format($kdv8-($kdv8/1.08), 2, ",", ".") }}</td>
            <td>{{ number_format($kdv18-($kdv8/1.18), 2, ",", ".") }}</td>
            <td>0,00</td>
            <td>0,00</td>
          </tr>
          <tr>
            <td colspan="4">NET MASRAF</td>
            <td>{{ number_format($km, 2, ",", ".") }}</td>
            <td>{{ number_format($kdv0, 2, ",", ".") }}</td>
            <td>{{ number_format($kdv8/1.08, 2, ",", ".") }}</td>
            <td>{{ number_format($kdv18/1.08, 2, ",", ".") }}</td>
            <td>{{ number_format($arackm, 2, ",", ".") }}</td>
            <td>{{ number_format($musterikm, 2, ",", ".") }}</td>
          </tr>
          <tr>
            <td colspan="4"><strong>TOPLAM HARCAMA</strong></td>
            <td colspan="6"><strong>{{ number_format($kdv0+$kdv8+$kdv18+$arackm, 2, ",", ".") }}</strong></td>
          </tr>
        </tfoot>
      </table>

      <table class="table imzalar">
        <tbody>
          <tr>
            <td>
              MASRAF SAHİBİ
            </td>
            <td>
              ONAYLAYAN MÜDÜR
            </td>
            <td>
              ONAYLAYAN PARTNER
            </td>
          </tr>
          <tr>
            <td>
              {{ $authenticated->name }} {{ $authenticated->surname }}
            </td>
            <td>
            </td>
            <td>
            </td>
          </tr>
          <tr>
            <td>
              <div style="border-bottom:1px solid #ddd; height:40px;">
                İmza:
              </div>
            </td>
            <td>
              <div style="border-bottom:1px solid #ddd; height:40px;">
                İmza:
              </div>
            </td>
            <td>
              <div style="border-bottom:1px solid #ddd; height:40px;">
                İmza:
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
@endsection

@section('scripts')
@endsection


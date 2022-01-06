@extends('hr.employee.detail')

@section('inside')

<div style="width:100%;">
  <div class="card card-custom gutter-b" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">Maaş Ödemeleri
                <div class="text-muted pt-2 font-size-sm">Personele ait maaş ödemeleri listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
      @php 
      $arr = array(
        '1' => 'Ocak',
        '2' => 'Şubat',
        '3' => 'Mart',
        '4' => 'Nisan',
        '5' => 'Mayıs',
        '6' => 'Haziran',
        '7' => 'Temmuz',
        '8' => 'Ağustos',
        '9' => 'Eylül',
        '10' => 'Ekim',
        '11' => 'Kasım',
        '12' => 'Aralık',
        )
      @endphp
      <table class="table table-stripe standard-datatable">
        <thead>
          <tr>
            <th>Ay</th>
            <th>Brüt Maaş</th>
            <th>Ödeme Durumu</th>
          </tr>
        </thead>
        <tbody>
          @for($i = 1; $i<=12; $i++)
            <tr>
              <td>{{ $arr[$i] }}</td>
              <td>
                <input type="text"
                data-month="{{ $i }}"
                data-year="{{ date('Y') }}"
                data-user="{{ $detail->id }}"
                class="form-control money-format change-payment"
                value="{{ isset($wages[$i]['wage']) ? number_format($wages[$i]['wage'], 2, ",", ".") : '' }}"
                >
              </td>
              <td>
                @isset($wages[$i]['wage'])
                  @if($wages[$i]['is_paid'] == 1)
                    <span class="btn btn-light-success btn-sm btn-bold btn-upper">Ödendi</span>
                  @else 
                  <span class="btn btn-light-info btn-sm btn-bold btn-upper">Ödenmedi</span>
                  @endif
                @endisset
              </td>
            </tr>
          @endfor
        </tbody>
      </table>
    </div>
  </div>

  <div class="card card-custom" style="width:100%;">
    <div class="card-header flex-wrap pt-6 pb-6">
        <div class="card-title">
            <h3 class="card-label">Masraf Fişleri
                <div class="text-muted pt-2 font-size-sm">Personele ait masraf fişlerinin listesi</div>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="#" class="btn btn-light-primary btn-sm  btn-bold">
            {{ date('Y') }}
          </a>&nbsp;&nbsp;
        </div>
    </div>
    <div class="card-body">
          <table class="table table-stripe standard-datatable">
            <thead>
              <tr>
                <th>Tarih</th>
                <th>Kategori</th>
                <th>Tutar</th>
                <th>Durum</th>
              </tr>
            </thead>
            <tbody>
              @foreach($costs as $c)
                <tr>
                  <td>{{ date('d M', strtotime($c->doc_date)) }}</td>
                  <td>
                    {{ $c->expense->name }}
                  </td>
                  <td>
                  {{ number_format($c->price, 2, ",", ".") }}TL
                  </td>
                  <td>
                  <span class="btn btn-light-info btn-sm btn-bold btn-upper">Ödenmedi</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
    </div>
  </div>
</div>
@endsection
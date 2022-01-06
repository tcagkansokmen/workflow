<h5 class="font-size-md">{{ $detail->title }}</h5>
<p><strong>Talep Açan: </strong>{{ $detail->user->name }} {{ $detail->user->surname }}</p>
<p>{{ $detail->description }}</p>
<div class="row">
  <div class="col-sm-8">
    <table class="table table-striped mb-6">
      <thead>
        <tr>
          <th>Ürün Kodu</th>
          <th scope="col">Ürün</th>
          <th scope="col">Miktar</th>
          <th scope="col">Toplam Tutar</th>
        </tr>
      </thead>
      <tbody>
      @foreach($detail->items as $d)
        <tr>
          <th>{{ $d->product->code }}</th>
          <th scope="row">{{ $d->product->title }}</th>
          <td>{{ $d->quantity }} {{ $d->type }}</td>
          <td>{{ money_formatter($d->price) }} TL</td>
        </tr>
      @endforeach
        <tr>
          <th scope="row"></th>
          <th scope="row"></th>
          <td><strong>Toplam: </strong></td>
          <td>
          {{ money_formatter($detail->total_price) }} TL
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
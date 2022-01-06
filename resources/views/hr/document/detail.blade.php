<div class="container" style="min-height:250px;">
  <div class="row">
    <h5>{{ $detail->category->name }} - {{ $detail->title }}</h5>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>İsim</th>
          <th>Okundu</th>
          <th>İmza</th>
          <th>Tarih</th>
        </tr>
      </thead>
      <tbody>
        @if(!count($users))
        <tr>
          <td colspan="4">Listede kimse bulunamadı.</td>
        </tr>
        @endif
        @foreach($users as $us)
          <tr>
            <td>{{ $us->user->name }} {{ $us->user->surname }}</td>
            <td>
              @if($us->is_read)
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Okundu</span>
              @else 
              <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Okunmadı</span>
              @endif
            </td>
            <td>
              @if($detail->mobile_sign)
                @if($us->is_signed)
                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">İmzalandı</span>
                @else 
                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-info">Bekliyor</span>
                @endif
              @else 
                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Yok</span>
              @endif
            </td>
            <td>{{ date('d.m.Y H:i', strtotime($us->updated_at)) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
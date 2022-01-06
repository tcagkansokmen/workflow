
<div class="container">
  <div class="row">

    <div class="col-xl-12 col-lg-12">
    <!--begin:: Widgets/Notifications-->
      <div class="card card-custom gutter-b">
      <div class="card-header flex-wrap">
          <div class="card-title">
              <h3 class="card-label">{{ isset($event) ? $event->title.' ait formlar' : 'Anketler' }}
                  <div class="text-muted pt-2 font-size-sm">Tüm anketlerin listesi</div>
              </h3>
          </div>
          <div class="card-toolbar">
              @if($authenticated->power('poll', 'ekle'))
              <a href="{{ route('add-form') }}" data-repeater-create="" class="btn btn-bold btn-sm btn-light-primary">
                <i class="la la-plus"></i> Yeni Ekle
              </a>
              @endif
          </div>
      </div>
    <div class="card-body">
      <table class="table table-striped- table-hover table-checkable" id="filtreli_form">
        <thead>
          <tr>
            <th>#</th>
            <th>Anket Adı</th>
            <th>Başlangıç</th>
            <th>Bitiş</th>
            <th>Eklenme Tarihi</th>
            <th class="align-right" style="text-align: right;">İşlem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($forms as $c)
          <tr>
            <td>
              {{ $c->id }}
            </td>
            <td>
              <div class="ml-4">
                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">{{ $c->title }}</div>
                <a href="#" class="text-muted font-weight-bold text-hover-primary">Kişi Sayısı <strong>{{ $c->contacts_count }}</strong> / Cevap Sayısı <strong>{{ $c->answers_count ?? 0 }}</strong></a>
              </div>
            </td>
            <td>{{ date('d.m.Y ', strtotime($c->start_at)) }}</td>
            <td>{{ date('d.m.Y ', strtotime($c->end_at)) }}</td>
            <td>{{ date('d.m.Y H:i', strtotime($c->created_at)) }}</td>
            <td class="align-right" style="text-align: right;">
              @if($authenticated->power('poll', 'ekle'))
              <a href="{{ route('form-edit', ['form_id' => $c->id]) }}" class="btn btn btn-icon btn-light btn-hover-info btn-sm" title="Detaylar">
                <i class="la la-edit text-info"></i>
              </a>
              @endif 
              <a href="{{ route('form-preview', ['form_id' => $c->id]) }}" class="btn btn btn-icon btn-light btn-hover-dark btn-sm" title="Detaylar">
                <i class="la la-search text-dark"></i>
              </a>
              <a href="{{ route('form-answers', ['form_id' => $c->id]) }}" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" title="Detaylar">
                <i class="fa fa-fingerprint text-primary"></i>
              </a>
              @if($authenticated->power('poll', 'ekle'))
              <a href="{{ route('form-delete', ['form_id' => $c->id]) }}" class="btn btn btn-icon btn-light btn-hover-danger btn-sm" title="Form Sil">
                <i class="la la-trash text-danger"></i>
              </a>
              @endif
            </td>
          </tr>
          @endforeach
        </tfoot>
      </table>

      </div>
    </div>
    <!--end:: Widgets/Notifications-->
    </div>
  </div>
</div>
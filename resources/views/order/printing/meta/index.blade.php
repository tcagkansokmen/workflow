{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <div class="card card-custom">
      <div class="card-header flex-wrap py-3">
        <div class="card-title">
          <h3 class="card-label">{{ $page_title }}
          <span class="d-block text-muted pt-2 font-size-sm">{{ $page_description }}</h3>
        </div>
        <div class="card-toolbar">
          <a href="{{ route('add-printing-meta') }}" class="btn btn-primary font-weight-bolder">
          {{ Metronic::getSVG("media/svg/icons/Design/Flatten.svg", "svg-icon svg-icon-md") }}
          Yeni Özellik</a>
          <!--end::Button-->
        </div>
      </div>
        <!--begin::Form-->
        <div class="card-body">
          <table class="meta-index">
            <thead>
              <tr>
                <th>Özellik Adı</th>
                <th>Alabildiği Değerler</th>
                <th>İşlem</th>
              </tr>
            </thead>
            <tbody>
              @foreach($metas as $m)
              <tr>
                <td>
                  {{ $m->value }}
                </td>
                <td>
                  @if($m->input=='select')
                    @foreach($m->options as $o)
                    <span class="label label-rounded label-light-danger label-inline font-weight-bolder mr-2">{{ $o->value }}</span>

                    @endforeach
                  @else
                    -
                  @endif
                </td>
                <td>
                  <a href="{{ route('update-printing-meta', ['id' => $m->id]) }}" class="btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Güncelle">
                  <i class="flaticon2-writing icon-md text-primary"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!--end::Form-->
    </div>

@endsection

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $(".meta-index").DataTable({
      "responsive": true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
              pageLength: 10,
              lengthMenu: [[50, 100, 500, 1000], [50, 100, 500, 1000]],
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
		"ordering": false,
        buttons: [
        ]
  });
});
</script>
@endsection

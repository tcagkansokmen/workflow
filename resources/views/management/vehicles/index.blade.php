{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
  <div class="card card-custom">
    <div class="card-header py-3">
      <div class="card-title align-items-start flex-column">
        <h3 class="card-label font-weight-bolder text-dark">{{ $page_title }}</h3>
        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ $page_description }}</span>
      </div>
      <div class="card-toolbar">
        @if($authenticated->power('vehicles', 'add'))
          <a href="{{ route('add-vehicle') }}" class="btn btn-success">Yeni Araç Ekle</a>
        @endif
      </div>
    </div>
      <div class="card-body">
        <table class="table standard-datatable">
          <thead>
            <tr>
              <th scope="col">Araç</th>
              <th scope="col">Kasko</th>
              <th scope="col">Sigorta</th>
              <th scope="col">Kiralık</th>
              <th>Bakım</th>
              <th>Durum</th>
              <th>İşlem</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detail as $d)
              <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="ml-4">
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                          {{ $d->plate }}
                        </div>
                        <a href="#" class="text-muted font-weight-bold text-hover-primary">
                        {{ $d->brand }} {{ $d->model }}
                        </a>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="ml-4">
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                          {{ $d->is_loan ? '' : date_formatter($d->kasko_end) }}
                        </div>
                        <a href="#" class="text-dangernt-weight-bold text-danger">
                          {{ $d->is_loan ? '' :\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($d->kasko_end))." Kalan Gün" }} 
                        </a>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="ml-4">
                        <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                          {{ $d->is_loan ? '' : date_formatter($d->insurance_end) }}
                        </div>
                        <a href="#" class="text-danger font-weight-bold text-hover-primary">
                          {{ $d->is_loan ? '' :\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($d->insurance_end))." Kalan Gün" }} 
                        </a>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="ml-4">
                        <div class="text-danger font-weight-bolder font-size-lg mb-0">
                          {{ $d->is_loan ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($d->loan_end))." Kalan Gün" : '' }} 
                        </div>
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="ml-4">
                        <div class="btn btn-light-primary btn-sm mb-0">
                          {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($d->care_date)) }} Kalan Gün
                        </div>
                      </div>
                    </div>
                  </td>
                    <td>
                      @if($d->is_active)
                      <span class="label label-lg font-weight-bold  label-light-success label-inline">Aktif</span>
                      @else
                      <span class="label label-lg font-weight-bold  label-light-danger label-inline">Pasif</span>
                      @endif
                    </td>
                  <td>
                  @if($authenticated->power('vehicles', 'edit'))
                    <a href="{{ route('update-vehicle', ['id' => $d->id]) }}" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">
                    <i class="flaticon2-writing icon-md text-danger"></i>
                    </a>&nbsp;
                    @endif

                  @if($authenticated->power('vehicles', 'delete'))
                    @if($d->is_active)
                    <a href="{{ route('passive-vehicle', ['id' => $d->id]) }}" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Pasife Al">
                    <i class="flaticon2-trash icon-md text-danger"></i>
                    </a>
                    @else
                    <a href="{{ route('active-vehicle', ['id' => $d->id]) }}" class="btn btn-icon btn-light btn-hover-success btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Aktife Al">
                    <i class="flaticon2-checkmark icon-md text-success"></i>
                    </a>
                    @endif
                  @endif
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
  var qrform = $('.standard-datatable').DataTable({
      responsive: true,
      dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
      <'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
      pageLength: 10,
      "order": [[ 2, "desc" ]],
      "language": {
      "url":"https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      },
      buttons: [
      'excelHtml5',
      'pdfHtml5',
      ]
  });
  </script>
<script>
$("body").on('click', '.delete-cost', function(e){
    e.preventDefault();
    var thi = $(this);
    var href = $(this).attr('href');
    swal.fire({
        title: "Emin misiniz?",
        text: "Bu işlemi yapmak istediğinizi onaylayın.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Evet!",
        cancelButtonText: "Hayır!",
        reverseButtons: true
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: href,
                dataType: 'json',
                type: 'get',
                success: function(data){
                    if(data.status){
                      location.reload();
                    }else{
                        swal.fire(
                            "Dikkat",
                            data.message,
                            "error"
                        )
                    }
                }
            });
        } else if (result.dismiss === "cancel") {

        }
    });
});



</script>
@if (Session::has('message'))

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.success("Doğrulama linki mail adresinize gönderildi!");
    </script>

@endif

@endsection

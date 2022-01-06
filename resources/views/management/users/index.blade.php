{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<div class="d-flex flex-row">
  <!--begin::Aside-->
  {{--<div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
    <!--begin::Profile Card-->
    <div class="card card-custom card-stretch">
      <!--begin::Body-->
      <div class="card-body pt-4">
          @include('firm.components.left')
      </div>
      <!--end::Body-->
    </div>
    <!--end::Profile Card-->
  </div>--}}
  <!--end::Aside-->
  <!--begin::Content-->
  <div class="flex-row-fluid ml-lg-8">
    <div class="card card-custom card-stretch">
      <!--begin::Header-->
      <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
          <h3 class="card-label font-weight-bolder text-dark">{{ $page_title }}</h3>
          <span class="text-muted font-weight-bold font-size-sm mt-1">{{ $page_description }}</span>
        </div>
        <div class="card-toolbar">
          @if($authenticated->power('users', 'edit'))
            <a href="{{ route('add-user') }}" class="btn btn-success">Yeni Kullanıcı Ekle</a>
          @endif
        </div>
      </div>
      <!--end::Header-->
      <!--begin::Form-->
        <!--begin::Body-->
        <div class="card-body">
          <table class="table standard-datatable">
            <thead>
              <tr>
                <th scope="col">İsim</th>
                <th scope="col">Yetki</th>
                <th scope="col">Kalan İzin</th>
                <th scope="col">Avans Durumu</th>
                <th scope="col">Durum</th>
                <th scope="col">İşlem</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detail as $d)
                <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        {{ $d->userAvatar() }}
                        <div class="ml-4">
                          <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                            {{ $d->name }} {{ $d->surname }}
                          </div>
                          <a href="#" class="text-muted font-weight-bold text-hover-primary">
                            {{ $d->email }}
                          </a>
                        </div>
                      </div>
                    </td>
                    <td>{{ $d->title }}</td>
                    <td>
                      @if($d->permissionsLeft()==0)
                      <span class="label label-lg font-weight-bold  label-light-info label-inline">{{ $d->permissionsLeft() }} Gün</span>
                      @elseif($d->permissionsLeft()>0)
                      <span class="label label-lg font-weight-bold  label-light-success label-inline">{{ $d->permissionsLeft() }} Gün</span>
                      @elseif($d->permissionsLeft()<0)
                      <span class="label label-lg font-weight-bold  label-light-danger label-inline">{{ $d->permissionsLeft() }} Gün</span>
                      @endif
                    </td>
                    <td>
                      @if($d->earnestLeft()==0)
                      <span class="label label-lg font-weight-bold  label-light-success label-inline">{{ money_formatter($d->earnestLeft()) }} TL</span>
                      @elseif($d->earnestLeft()>0)
                      <span class="label label-lg font-weight-bold  label-light-danger label-inline">{{ money_formatter($d->earnestLeft()) }} TL</span>
                      @elseif($d->earnestLeft()<0)
                      <span class="label label-lg font-weight-bold  label-light-info label-inline">{{ money_formatter($d->earnestLeft()) }} TL</span>
                      @endif
                    </td>
                    <td>
                      @if($d->is_active)
                      <span class="label label-lg font-weight-bold  label-light-success label-inline">Aktif</span>
                      @else
                      <span class="label label-lg font-weight-bold  label-light-danger label-inline">Pasif</span>
                      @endif
                    </td>
                    <td>
                    @if($authenticated->power('users', 'edit'))
                    <a href="{{ route('personel-detail', ['id' => $d->id]) }}" class="btn btn btn-icon btn-light btn-hover-info btn-sm" data-toggle="tooltip" data-theme="light" title="Detay">
                    <i class="flaticon2-user-1 icon-md text-info"></i>
                    </a>&nbsp;
                    <a href="{{ route('update-user', ['id' => $d->id]) }}" class="btn btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-theme="light" title="Düzenle">
                    <i class="flaticon2-writing icon-md text-primary"></i>
                    </a>&nbsp;
                    @endif
                    @if($authenticated->power('users', 'delete'))
                      @if($d->is_active)
                      <a href="{{ route('passive-user', ['id' => $d->id]) }}" class="btn btn-icon btn-light btn-hover-danger btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Pasife Al">
                      <i class="flaticon2-trash icon-md text-danger"></i>
                      </a>
                      @else
                      <a href="{{ route('active-user', ['id' => $d->id]) }}" class="btn btn-icon btn-light btn-hover-success btn-sm delete-cost" data-toggle="tooltip" data-theme="light" title="Aktife Al">
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
        <!--end::Body-->
      <!--end::Form-->
    </div>
  </div>
  <!--end::Content-->
</div>
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
{{-- Scripts Section --}}
@section('scripts')

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

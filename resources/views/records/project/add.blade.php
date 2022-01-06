{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Yeni Proje
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-project')}}" class="general-form" method="post" enctype="multipart/form-data">
                @csrf
                @include('records.project.add-inside')
            </form>
        </div>
        <!--end::Form-->
    </div>
    <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('save-customer')}}" class="customer-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('records.customer.add-inside')
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
@endsection

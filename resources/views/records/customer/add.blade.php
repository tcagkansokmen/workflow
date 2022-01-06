{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Yeni Müşteri
            </h3>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form action="{{route('save-customer')}}" class="general-form" method="post" enctype="multipart/form-data">
                @csrf
                @include('records.customer.update')
            </form>
        </div>
        <!--end::Form-->
    </div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection

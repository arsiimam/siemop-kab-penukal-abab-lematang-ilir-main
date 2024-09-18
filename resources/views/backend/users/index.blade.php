@extends('layouts.app')

@section('header')
    <h1>{{ __('Pengguna') }}</h1>
@endsection

@section('action-button')
    <div class="top-right-button-container">
        <div class="btn-group" role="group">
            @can('Create User')
                <button type="button" class="btn btn-primary top-right-button mb-2 mr-1 modal-btn-xl" style="width: 80px;"
                    data-title="{{ __('Tambah Data Baru') }}" data-url="{{ route('users.create') }}" data-toggle="modal"
                    data-backdrop="static">{{ __('Tambah') }}</button>
            @endcan
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">{{ __('User') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Data') }}</li>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $dataTable->table(['class' => 'table table-striped dataTable responsive nowrap'], false) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

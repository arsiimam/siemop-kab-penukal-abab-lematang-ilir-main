@extends('layouts.app')

@section('header')
    <h1>{{ __('Roles') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">{{ __('Roles') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
@endsection

@section('action-button')
    <div class="top-right-button-container">
        <div class="btn-group">
            <a href="{{ route('roles.index') }}" class="btn btn-primary mb-2 top-right-button mr-1" style="line-height: 2"><i
                    class="iconsminds-back" style="font-size: 16px"></i> {{ __('Back') }}</a>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $role->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permissions:</strong>
                @if (!empty($rolePermissions))
                    @foreach ($rolePermissions as $v)
                        <label class="label label-success">{{ $v->name }},</label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

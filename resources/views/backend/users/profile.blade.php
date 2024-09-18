@extends('layouts.app')

@section('header')
    <h1>{{ __('Profil') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">{{ __('Profil') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endsection

@section('action-button')
    <div class="top-right-button-container">
        <div class="btn-group">
            <button type="submit" name="save" class="btn btn-info mb-2 top-right-button mr-1">
                {{ __('Simpan') }}
            </button>
        </div>
    </div>
@endsection

@section('form-open')
    {!! Form::model($user, [
        'method' => 'PUT',
        'route' => ['users.update', $user->id],
        'class' => 'needs-validation tooltip-label-right',
        'novalidate',
    ]) !!}
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-italic">Detail Informasi</h6>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Username</label>
                            {{ Form::text('username', old('username'), ['class' => 'form-control', 'required']) }}
                            {{ Form::hidden('role_id', old('role_id'), ['class' => 'form-control', 'required', 'readonly']) }}
                            {{ Form::hidden('institute_id', old('institute_id'), ['class' => 'form-control', 'required', 'readonly']) }}
                            {{ Form::hidden('is_active', old('is_active'), ['class' => 'form-control', 'required', 'readonly']) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Nama Pengguna</label>
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Email</label>
                            {{ Form::text('email', old('email'), ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">No. Telp</label>
                            {{ Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'required']) }}
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="font-italic">Perubahan Password</h6>
                            <hr>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="">Password</label>
                            {{ Form::password('password', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Konfirmasi Password</label>
                            {{ Form::password('confirm-password', ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="font-italic">Avatar</h6>
                    <hr>
                    <div class="dropzone dropzone_single_two_params">
                    </div>
                    {{ Form::textarea('image', null, ['class' => 'form-control image_encode d-none', 'id' => 'image_encode']) }}
                    {{ Form::hidden('name_file', old('name_file'), ['class' => 'form-control name_file', 'id' => 'name_file']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('form-close')
    {!! Form::close() !!}
@endsection

@push('scripts')
    <script>
        $(".dropzone_single_two_params").dropzone({
            maxFiles: 1,
            maxFilesize: 5,
            acceptedFiles: ".jpeg,.jpg,.png",
            url: "{{ url('postimage') }}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {
                var thisDropzone = this;
                var mockFile = {
                    name: 'Recent File',
                    size: 12345,
                    type: 'image/jpeg'
                };
                thisDropzone.emit("addedfile", mockFile);
                $(".dropzone_single").find('.dz-progress').addClass('d-none');
                thisDropzone.emit("thumbnail", mockFile, "{{ asset($user->avatar) }}")

                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });

                this.on("success", function(file, responseText) {
                    console.log(responseText);
                    if (responseText.status === 'error') {
                        var message = responseText.message;
                        this.removeAllFiles();
                        message.forEach(function(entry) {
                            $.toast({
                                heading: 'Error',
                                text: entry,
                                icon: 'warning',
                                position: 'top-right',
                                hideAfter: 5000,
                                showHideTransition: 'slide'
                            })
                        });
                    } else {
                        $('#image_encode').val(responseText.file)
                        $('#name_file').val(responseText.title)
                    }
                });
            },
            thumbnailWidth: 160,
            previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "><div class="p-0 w-30 position-relative"><div class="dz-error-mark"><span><i></i></span></div><div class="dz-success-mark"><span><i></i></span></div><div class="preview-container"><img data-dz-thumbnail class="img-thumbnail border-0" /><i class="simple-icon-doc preview-icon" ></i></div></div><div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"><div><span data-dz-name></span></div><div class="text-primary text-extra-small" data-dz-size /><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div></div><a href="#/" class="remove" onclick="remove_pic();" data-dz-remove><i class="glyph-icon simple-icon-trash"></i></a></div>'
        });
    </script>
@endpush

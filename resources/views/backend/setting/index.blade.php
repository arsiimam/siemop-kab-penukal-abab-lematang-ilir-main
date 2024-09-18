@extends('layouts.app')

@section('header')
    <h1>{{ __('Pengaturan Sistem') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">{{ __('Pengaturan Sistem') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Data') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting Background & Logo</i></h6>
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ __('Banner Login 1') }}</label>
                            <form class="dropzone image-upload" method="POST" action="{{ route('settings.store_banner') }}"
                                enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_banner']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-banner-1">
                                @if ($row['pict_banner']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['pict_banner']->setting_value) }}" width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['pict_banner']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Banner Login 2') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_login']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-banner-2">
                                @if ($row['pict_login']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['pict_login']->setting_value) }}" width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['pict_login']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Logo') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_logo']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-logo">
                                @if ($row['pict_logo']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['pict_logo']->setting_value) }}" width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['pict_logo']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Favicon') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_favicon']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-favicon">
                                @if ($row['pict_favicon']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['pict_favicon']->setting_value) }}" width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['pict_favicon']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Logo Login') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_auth_logo']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-pict_auth_logo">
                                @if ($row['pict_auth_logo']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['pict_auth_logo']->setting_value) }}" width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['pict_auth_logo']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting Frontend</i></h6>
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{ __('Banner Frontend') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['pict_frontend']->id ?? null, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-frontend">
                                {{--  --}}
                            </div>
                        </div>

                        <div class="col-md-9">
                            <form method="POST" action="{{ route('settings.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label>{{ __('Description') }}</label>
                                    {{ Form::textarea('desc_master_data', $row['desc_master_data']->setting_value, ['class' => 'form-control', 'id' => 'editor']) }}
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" name="submit"
                                        value="setting_frontend">{{ __('Simpan Perubahan') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting Company</i></h6>
                    <form method="POST" action="{{ route('settings.store') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Nama Aplikasi') }}</label>
                                {{ Form::text('ds_app_name', ENV('DS_APP_NAME'), ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Deskripsi Aplikasi') }}</label>
                                {{ Form::textarea('ds_app_desc', ENV('DS_APP_DESC'), ['class' => 'form-control', 'rows' => 2]) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Nama Instansi') }}</label>
                                {{ Form::text('company_name', $row['company_name']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Kota') }}</label>
                                {{ Form::text('company_city', $row['company_city']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Kode Pos') }}</label>
                                {{ Form::text('company_poscode', $row['company_poscode']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Alamat') }}</label>
                                {{ Form::text('company_address', $row['company_address']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Email') }}</label>
                                {{ Form::text('company_email', $row['company_email']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('No. Telp') }}</label>
                                {{ Form::text('company_phone', $row['company_phone']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Batas Tanggal Input Laporan') }}</label>
                                {{ Form::number('max_input_date', ENV('MAX_INPUT_DATE'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary" name="submit"
                                    value="setting_company">{{ __('Simpan Perubahan') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting Paraf</i></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{ __('Paraf') }}</label>
                            <form class="dropzone image-upload" method="POST"
                                action="{{ route('settings.store_banner') }}" enctype="multipart/form-data">
                                @csrf
                                {{ Form::hidden('id', $row['setting_signature_file']->id, ['class' => 'form-control', 'readonly']) }}
                            </form>
                            <div id="images-recent-setting_signature_file">
                                @if ($row['setting_signature_file']->setting_value != null)
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset($row['setting_signature_file']->setting_value) }}"
                                                width="100" />
                                            <button type="button" class="btn-link remove_pict"
                                                data-id="{{ $row['setting_signature_file']->id }}"><i
                                                    class="simple-icon-trash"></i></button>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="gallery margin-top-10">
                                        <li class="profile">
                                            <img src="{{ asset('img/image_coming_soon.jpeg') }}" width="100" />
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('settings.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>{{ __('Kepala Instansi (untuk paraf laporan)') }}</label>
                                        {{ Form::text('setting_name_signature', $row['setting_name_signature']->setting_value, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group col-12">
                                        <label>{{ __('Jabatan / Posisi') }}</label>
                                        {{ Form::text('setting_signature_position', $row['setting_signature_position']->setting_value, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group col-12">
                                        <label>{{ __('NIP (Nomor Induk Pegawai)') }}</label>
                                        {{ Form::text('setting_signature_nip', $row['setting_signature_nip']->setting_value, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group col-12 d-none">
                                        <label>{{ __('Status') }}</label>
                                        {{ Form::select('setting_signature_status', ['on' => 'Tampilkan', 'off' => 'Sembunyikan'], $row['setting_signature_status']->setting_value, ['class' => 'form-control select2-default']) }}
                                    </div>
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-primary" name="submit"
                                            value="setting_signature">{{ __('Simpan Perubahan') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting Meta</i></h6>
                    <form method="POST" action="{{ route('settings.store') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Meta Title') }}</label>
                                {{ Form::text('meta_title', $row['meta_title']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Meta Keyword') }}</label>
                                {{ Form::text('meta_keyword', $row['meta_keyword']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Meta Desc') }}</label>
                                {{ Form::text('meta_desc', $row['meta_desc']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Meta Robots') }}</label>
                                {{ Form::text('meta_robots', $row['meta_robots']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary" name="submit"
                                    value="setting_meta">{{ __('Simpan Perubahan') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6><i>Setting SMTP</i></h6>
                    <form method="POST" action="{{ route('settings.store') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Host') }}</label>
                                {{ Form::text('smtp_host', $row['smtp_host']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Port') }}</label>
                                {{ Form::text('smtp_port', $row['smtp_port']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Encrypt') }}</label>
                                {{ Form::text('smtp_encrypt', $row['smtp_encrypt']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Mailer') }}</label>
                                {{ Form::text('smtp_mailer', $row['smtp_mailer']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Username') }}</label>
                                {{ Form::text('smtp_username', $row['smtp_username']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP Password') }}</label>
                                {{ Form::text('smtp_password', $row['smtp_password']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP From Email') }}</label>
                                {{ Form::text('smtp_from', $row['smtp_from']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('SMTP From Name') }}</label>
                                {{ Form::text('smtp_name', $row['smtp_name']->setting_value, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary" name="submit"
                                    value="setting_smtp">{{ __('Simpan Perubahan') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        Dropzone.autoDiscover = false;
        $(".image-upload").dropzone({
            thumbnailWidth: 100,
            maxFilesize: 12,
            acceptedFiles: ".jpeg,.jpg,.png,.webp",
            init: function() {
                this.on("success", function(file, responseText) {
                    console.log(responseText);
                    if (responseText.status === 'error') {
                        this.removeAllFiles();
                        var message = responseText.message;
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
                        myDropzone = this;
                        myDropzone.processQueue();
                        this.on("complete", function() {
                            if (this.getQueuedFiles().length == 0 && this.getUploadingFiles()
                                .length == 0) {
                                var _this = this;
                                _this.removeAllFiles();
                            }
                            load_images();
                        });
                    }
                });
            },
            previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "><div class="p-0 w-30 position-relative"><div class="dz-error-mark"><span><i></i></span></div><div class="dz-success-mark"><span><i></i></span></div><div class="preview-container"><img data-dz-thumbnail class="img-thumbnail border-0" /><i class="simple-icon-doc preview-icon" ></i></div></div><div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"><div><span data-dz-name></span></div><div class="text-primary text-extra-small" data-dz-size /><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div></div><a href="#/" class="remove" data-dz-remove><i class="glyph-icon simple-icon-trash"></i></a></div>'
        });

        load_images();

        function load_images() {
            // 1
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_banner']->id) }}",
                success: function(data) {
                    $('#images-recent-banner-1').html(data);
                }
            })

            // 2
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_login']->id) }}",
                success: function(data) {
                    $('#images-recent-banner-2').html(data);
                }
            })

            // 3
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_logo']->id) }}",
                success: function(data) {
                    $('#images-recent-logo').html(data);
                }
            })

            // 4
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_favicon']->id) }}",
                success: function(data) {
                    $('#images-recent-favicon').html(data);
                }
            })

            // 5
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_auth_logo']->id) }}",
                success: function(data) {
                    $('#images-recent-pict_auth_logo').html(data);
                }
            })

            // 6
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['setting_signature_file']->id) }}",
                success: function(data) {
                    $('#images-recent-setting_signature_file').html(data);
                }
            })

            // 7 
            $.ajax({
                url: "{{ url('settings/filerecent/' . $row['pict_frontend']->id) }}",
                success: function(data) {
                    $('#images-recent-frontend').html(data);
                }
            })
        }

        $(document).on('click', '.remove_pict', function() {
            var id = $(this).data("id");
            $.ajax({
                url: "{{ url('') }}/settings/removeImg/" + id,
                success: function(data) {
                    load_images();
                }
            })
        });
    </script>
@endpush

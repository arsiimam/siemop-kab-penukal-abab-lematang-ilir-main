{{ Form::open([
    'method' => 'POST',
    'id' => 'form_create',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/institute'),
    'data-async',
]) }}
<div class="modal-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="form-group col-12">
                        <label>{{ __('Nama Perangkat Daerah') }}</label>
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('PIC') }}</label>
                        {{ Form::text('pic', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('Kontak PIC') }}</label>
                        {{ Form::text('contact_pic', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('Alamat') }}</label>
                        {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3]) }}
                    </div>
                </div>
            </div>
            <div class="col-6" style="border-left: 1px #dedede solid">
                <div class="row">
                    <div class="form-group col-12">
                        <label>{{ __('Nama Kepala Instansi') }}</label>
                        {{ Form::text('head_of_institute', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('Jabatan') }}</label>
                        {{ Form::text('position', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('NIP') }}</label>
                        {{ Form::text('nip', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group col-12">
                        <label>{{ __('Paraf') }}</label>
                        <div class="dropzone dropzone_single">
                        </div>
                        {{ Form::textarea('image', null, ['class' => 'form-control image_encode d-none', 'id' => 'image_encode']) }}
                        {{ Form::hidden('name_file', old('name_file'), ['class' => 'form-control name_file', 'id' => 'name_file']) }}
                    </div>
                    <div class="form-group col-12 d-none">
                        <label>{{ __('Status') }}</label>
                        {{ Form::select('signature_status', ['Sembunyikan', 'Tampilkan'], null, ['class' => 'form-control select2-modal', 'placeholder' => 'select']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer pt-3 pb-3">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info mr-1" data-dismiss="modal"
            style="width: 80px;">{{ __('Batal') }}</button>
        <button type="submit" class="btn btn-primary btn-save" style="width: 80px;">{{ __('Simpan') }}</button>
    </div>
</div>
{{ Form::close() }}


<script>
    $('form[data-async]').on('submit', function(event) {

        var $form = $(this);
        var data = $form.serialize();

        $.ajax({
            type: $form.attr('method'),
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + $form.attr('data-token'));
                notice.showLoading({
                    type: 'dots',
                    title: 'Loading',
                });
            },
            url: $form.attr('data-target'),
            data: $form.serialize(),
            success: function(response) {
                setTimeout(() => {
                    notice.hideLoading()

                    if (response.status === 'error') {
                        var message = response.message;
                        message.forEach(function(entry) {
                            notify_payload.call(this, 'Required', entry, 'warning');
                        });
                    } else {
                        notify_payload.call(this, 'Success', response.message, 'success');

                        $('#empModal').modal('hide');
                        reload.call();
                    }
                }, 500)
            },
            error: function(response) {
                setTimeout(() => {
                    notice.hideLoading()

                    notify_payload.call(this, 'Error', response.statusText, 'error');
                }, 500)
            }
        });

        event.preventDefault();
    });

    $(".dropzone_single").dropzone({
        maxFiles: 1,
        maxFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png",
        url: "{{ url('postimage') }}",
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
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

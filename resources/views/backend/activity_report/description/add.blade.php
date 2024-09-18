{{ Form::open([
    'method' => 'POST',
    'id' => 'form_create',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/child-activity-report'),
    'data-async',
]) }}
<div class="modal-body">

    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-md-12">
                <label>{{ __('Nama Kegiatan') }}*</label>
                {{ Form::hidden('parent_id', $proyek->id, ['class' => 'form-control']) }}
                {{ Form::hidden('activityprogram_id', $proyek->activityprogram_id, ['class' => 'form-control']) }}
                {{ Form::hidden('input_type', 'activity', ['class' => 'form-control', 'readonly']) }}
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Pagu Indikatif') }}</label>
                {{ Form::text('pagu_indikatif', null, ['class' => 'form-control format-number']) }}
                <span class="font-italic">Pagu indikatif didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Target Kinerja') }}</label>
                {{ Form::text('target_kinerja', null, ['class' => 'form-control']) }}
                <span class="font-italic">Contoh: 1 Dokumen RENSTRA, 1 Laporan MONEV , etc..</span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 font-italic">
                Keterangan:
                <ul>
                    <li>* (Mandatory / Tidak boleh kosong)</li>
                </ul>
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
    $(document).ready(function() {
        $('.format-number').on('keyup', formatNumberField);
    });

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

                        $('#empModal-xl').modal('hide');
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
</script>

{{ Form::open([
    'method' => 'POST',
    'id' => 'form_create',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/announcement'),
    'data-async',
]) }}
<div class="modal-body">

    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-12">
                <label>{{ __('Judul Pengumuman') }}</label>
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-12">
                <label>{{ __('Perangkat Daerah') }}</label>
                {{ Form::select('institute_id', $institutes, null, ['class' => 'form-control select2-modal', 'placeholder' => 'select']) }}
                <span class="font-italic">Jika tidak diisi, maka pengumuman akan diberikan kepada semua perangkat
                    daerah.</span>
            </div>
            <div class="form-group col-6">
                <label>{{ __('Tanggal Mulai') }}</label>
                {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-6">
                <label>{{ __('Tanggal Selesai') }}</label>
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-12">
                <label>{{ __('Deskripsi') }}</label>
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5]) }}
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

                        $('#count_announcement').html(response.count)
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

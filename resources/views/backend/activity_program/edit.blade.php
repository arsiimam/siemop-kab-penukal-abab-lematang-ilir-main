{{-- {{ Form::open(['method' => 'POST', 'id' => 'form_create', 'data-async']) }} --}}
{!! Form::model($activityProgram, [
    'method' => 'PUT',
    'id' => 'form_update',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/activity-program/' . $activityProgram->id),
    'data-async',
]) !!}
<div class="modal-body">

    <div class="container-fluid">
        {{ Form::hidden('_method', 'PUT', ['class' => 'form-control', 'readonly']) }}
        <div class="row">
            <div class="form-group col-12">
                <label>{{ __('Judul Program') }}</label>
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            @can('Manage Any Activity Program')
                <div class="form-group col-12">
                    <label>{{ __('Perangkat Daerah') }}</label>
                    {{ Form::select('institute_id', $institutes, null, ['class' => 'form-control select2-modal', 'placeholder' => 'select']) }}
                </div>
            @elsecan('Create Activity Program')
                {{ Form::hidden('institute_id', null, ['class' => 'form-control', 'readonly']) }}
            @endcan
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
</script>

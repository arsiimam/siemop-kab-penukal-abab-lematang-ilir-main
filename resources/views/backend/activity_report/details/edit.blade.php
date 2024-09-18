{{-- {{ Form::open(['method' => 'POST', 'id' => 'form_create', 'data-async']) }} --}}
{!! Form::model($activityReport, [
    'method' => 'PUT',
    'id' => 'form_update',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/child-activity-report/' . $activityReport->id),
    'data-async',
]) !!}
<div class="modal-body">

    <div class="container-fluid">
        {{ Form::hidden('_method', 'PUT', ['class' => 'form-control', 'readonly']) }}
        <div class="row">
            <div class="form-group col-md-6">
                <label>{{ __('Nama Sub Kegiatan') }}*</label>
                {{ Form::hidden('input_type', 'sub_activity', ['class' => 'form-control', 'readonly']) }}
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Lokasi Kegiatan') }}</label>
                {{ Form::text('location', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Pagu Indikatif (Rp)') }}</label>
                {{ Form::text('pagu_indikatif', custom_number_format($activityReport->pagu_indikatif), ['class' => 'form-control format-number']) }}
                <span class="font-italic">Pagu indikatif didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Target Kinerja') }}</label>
                {{ Form::text('target_kinerja', null, ['class' => 'form-control']) }}
                <span class="font-italic">Contoh: 1 Dokumen RENSTRA, 1 Laporan MONEV , etc..</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Target Fisik (%)') }}</label>
                {{ Form::number('target_fisik', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Target Keuangan (%)') }}</label>
                {{ Form::number('target_keuangan', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Fisik (%)') }}*</label>
                {{ Form::number('fisik', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Realisasi Keuangan (Rp)') }}*</label>
                {{ Form::text('realization', custom_number_format($activityReport->realization), ['class' => 'form-control format-number']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPK') }}</label>
                {{ Form::text('ppk', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPTK') }}</label>
                {{ Form::text('pptk', null, ['class' => 'form-control']) }}
            </div>
        </div>

        @can('Delete Activity Report')
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div class="alert alert-danger" role="alert">
                        Tindakan hapus data, akan menghapus data <b>{{ $activityReport->title }}</b> dan semua sub
                        data yang mengacu pada data ini.
                    </div>
                    <button type="button" class="btn btn-danger btn-delete-inline btn-xs">Hapus
                        Data</button>
                </div>
            </div>
        @endcan
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

    $(document).on('click', ".btn-delete-inline", function(e) {
        e.preventDefault();
        Swal.fire({
            position: 'center',
            title: 'Apakah Anda Yakin?',
            text: "Anda tidak akan dapat mengembalikan data yang sudah dihapus !",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization",
                            "Bearer {{ session('bearerToken') }}");
                        notice.showLoading({
                            type: 'dots',
                            title: 'Loading',
                        });
                    },
                    url: "{{ url('api/child-activity-report/' . $activityReport->id) }}",
                    data: {
                        _method: 'delete'
                    },
                    success: function(response) {
                        setTimeout(() => {
                            notice.hideLoading()

                            if (response.status === 'error') {
                                var message = response.message;
                                message.forEach(function(entry) {
                                    notify_payload.call(this, 'Required',
                                        entry, 'warning');
                                });
                            } else {
                                notify_payload.call(this, 'Success', response
                                    .message, 'success');

                                $('#empModal-xl').modal('hide');
                                reload.call();
                            }
                        }, 500)
                    },
                    error: function(response) {
                        setTimeout(() => {
                            notice.hideLoading()

                            notify_payload.call(this, 'Error', response.statusText,
                                'error');
                        }, 500)
                    }
                })
            }
        });
    });
</script>

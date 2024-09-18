{{-- {{ Form::open(['method' => 'POST', 'id' => 'form_create', 'data-async']) }} --}}
{!! Form::model($development, [
    'method' => 'PUT',
    'id' => 'form_update',
    'data-token' => session('bearerToken'),
    'data-target' => url('api/development/' . $development->id),
    'data-async',
]) !!}
<div class="modal-body">

    <div class="container-fluid">
        {{ Form::hidden('_method', 'PUT', ['class' => 'form-control', 'readonly']) }}

        <div class="row">
            <div class="form-group col-md-6">
                <label>{{ __('Nama Paket Pekerjaan') }}*</label>
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Jenis Pembangunan') }}</label>
                {{ Form::text('type', null, ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Pagu Anggaran') }} <small>(Rp.)*</small></label>
                {{ Form::text('pagu_indikatif', custom_number_format($development->pagu_indikatif), ['class' => 'form-control format-number']) }}
                <span class="font-italic">Pagu Anggaran didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Sumber Dana') }}*</label>
                {{ Form::text('sumber_dana', null, ['class' => 'form-control']) }}
            </div>
            <div class="col-12">
                <hr class="my-2">
            </div>
            <div class="form-group col-md-4">
                <label>{{ __('Nomor Kontrak') }}</label>
                {{ Form::text('contract_number', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-4">
                <label>{{ __('Tanggal Kontrak') }}</label>
                {{ Form::date('contract_date', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-4">
                <label>{{ __('Harga Kontrak') }} <small>(Rp.)</small></label>
                {{ Form::text('contract_price', custom_number_format($development->contract_price), ['class' => 'form-control format-number']) }}
                <span class="font-italic">Harga kontrak didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-4">
                <label>{{ __('Durasi Kontrak') }}</label>
                {{ Form::text('contract_duration', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-8">
                <label>{{ __('Pelaksana / Kontraktor') }}</label>
                {{ Form::text('executor', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Target Progres') }} <small>(%)</small>*</label>
                {{ Form::number('target_progres', null, ['class' => 'form-control']) }}
            </div>
            {{-- <div class="form-group col-md-6">
                <label>{{ __('Progres Pekerjaan') }} <small>(%)</small>*</label>
                {{ Form::number('progress_pekerjaan', null, ['class' => 'form-control']) }}
            </div> --}}
            <div class="form-group col-md-6">
                <label>{{ __('Realisasi Progres') }} <small>(%)</small>*</label>
                {{ Form::number('realisasi_progres', null, ['class' => 'form-control']) }}
            </div>
            <div class="col-12">
                <hr class="my-2">
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Dokumentasi') }}</label>
                {{ Form::text('documentation', null, ['class' => 'form-control']) }}
                <span class="font-italic">Dokumentasi bisa dihubungkan dengan link google drive.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPK') }}*</label>
                {{ Form::text('ppk', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPTK') }}*</label>
                {{ Form::text('pptk', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Lokasi Kegiatan') }}*</label>
                {{ Form::text('location', null, ['class' => 'form-control']) }}
            </div>
        </div>

        {{-- <div class="row">
            <div class="form-group col-md-12">
                <label>{{ __('Nama Paket Pekerjaan') }}*</label>
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Jenis Pembangunan') }}</label>
                {{ Form::text('type', null, ['class' => 'form-control', 'readonly']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Pagu Anggaran') }} <small>(Rp.)</small>*</label>
                {{ Form::text('pagu_indikatif', custom_number_format($development->pagu_indikatif), ['class' => 'form-control format-number']) }}
                <span class="font-italic">Pagu Anggaran didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Sumber Dana') }}*</label>
                {{ Form::text('sumber_dana', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Progres Pekerjaan') }} <small>(%)</small>*</label>
                {{ Form::text('progress_pekerjaan', null, ['class' => 'form-control']) }}
                <span class="font-italic">Progres pekerjaan didefinisikan dalam format %.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Dokumentasi') }}</label>
                {{ Form::text('documentation', null, ['class' => 'form-control']) }}
                <span class="font-italic">Dokumentasi bisa dihubungkan dengan link google drive.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPK') }}*</label>
                {{ Form::text('ppk', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('PPTK') }}*</label>
                {{ Form::text('pptk', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Harga Kontrak') }} <small>(Rp.)</small></label>
                {{ Form::text('contract_price', custom_number_format($development->contract_price), ['class' => 'form-control format-number']) }}
                <span class="font-italic">Harga kontrak didefinisikan dalam format angka.</span>
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Pelaksana / Kontraktor') }}</label>
                {{ Form::text('executor', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Lokasi Kegiatan') }}*</label>
                {{ Form::text('location', null, ['class' => 'form-control']) }}
            </div>
        </div> --}}

        <div class="row mt-4">
            <div class="col-12 font-italic">
                Keterangan:
                <ul>
                    <li>* (Mandatory / Tidak boleh kosong)</li>
                </ul>
            </div>
        </div>

        @can('Delete Development')
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div class="alert alert-danger" role="alert">
                        Tindakan hapus data, akan menghapus data <b>{{ $development->title }}</b> dan semua sub
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
                    url: "{{ url('api/development/' . $development->id) }}",
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

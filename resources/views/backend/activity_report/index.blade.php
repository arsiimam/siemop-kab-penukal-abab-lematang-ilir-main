@extends('layouts.app')

@section('header')
    <h1>{{ __('Uraian kegiatan') }}</h1>
@endsection

@section('action-button')
    <div class="top-right-button-container">
        <div class="btn-group" role="group">
            @can('Create Activity Report')
                <button type="button" class="btn btn-primary top-right-button mb-2 mr-1 modal-btn-xl" style="width: 80px;"
                    data-title="{{ __('Tambah Data Baru') }}" data-url="{{ route('activity-report.create') }}" data-toggle="modal"
                    data-backdrop="static">{{ __('Tambah') }}</button>
            @endcan

            @can('Delete Activity Report')
                <button type="button" class="btn btn-info top-right-button mb-2 dropdown-toggle dropdown-toggle-split"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 80px;">
                    <span class="sr-only">Toogle Dropdown</span>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    <button type="button" class="dropdown-item" id="delete_record"><i class="simple-icon-trash"></i>
                        {{ __('Hapus') }}</button>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">{{ __('Semua Uraian kegiatan') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Data') }}</li>
@endsection

@section('content')
    <div class="row mb-2">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end">

                        @can('Manage Any Activity Report')
                            <div class="form-group col-md-3 mb-0">
                                {{ Form::select('institute_id', $institutes, null, [
                                    'class' => 'form-control select2-with-clear',
                                    'placeholder' => __('Select Data'),
                                    'data-width' => '100%',
                                    'filterable' => 'true',
                                    'id' => 'institute_id',
                                    'required',
                                ]) }}
                                <small><i>Perangkat Daerah</i></small>
                            </div>
                        @endcan

                        <div class="form-group col-md-3 mb-0">
                            {{ Form::select('month', month_list(), date('m'), [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'month',
                                'required',
                            ]) }}
                            <small><i>Bulan</i></small>
                        </div>
                        <div class="form-group col-md-3 mb-0">
                            {{ Form::select('year', year_list(), date('Y'), [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'year',
                                'required',
                            ]) }}
                            <small><i>Tahun</i></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    <script>
        const table = $('#activityreport-table')

        $('[filterable="true"]').on('change', function(e) {
            table.on('preXhr.dt', (e, settings, data) => {
                data.institute_id = $('#institute_id').val()
                data.month = $('#month').val()
                data.year = $('#year').val()
            })
            table.DataTable().ajax.reload()
        });

        function reload() {
            table.DataTable().ajax.reload()
        }

        $(document).on("submit", "form[data-async-delete]", function(event) {

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
                    var $form = $(this);
                    var data = $form.serialize();

                    $.ajax({
                        type: $form.attr('method'),
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + $form.attr(
                                'data-token'));
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
                                        notify_payload.call(this, 'Required',
                                            entry,
                                            'warning');
                                    });
                                } else {
                                    notify_payload.call(this, 'Success', response
                                        .message,
                                        'success');

                                    $('#empModal').modal('hide');
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
                    });
                }
            });

            event.preventDefault();
        });

        $(document).on('click', "#checkAll", function() {
            if ($(this).is(':checked')) {
                $('.delete_check').prop('checked', true);
            } else {
                $('.delete_check').prop('checked', false);
            }
        });

        $(document).on('click', "#delete_record", function() {
            var deleteids_arr = [];
            $("input:checkbox[data-delete]:checked").each(function() {
                deleteids_arr.push($(this).val());
            });

            if (deleteids_arr.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tidak Ada Data yang Dipilih !!',
                })
            } else {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Menghapus data yang dipilih ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader("Authorization",
                                    "Bearer {{ session('bearerToken') }}");
                                notice.showLoading({
                                    type: 'dots',
                                    title: 'Loading',
                                });
                            },
                            url: "{{ url('api/activity-report/bulk-delete') }}",
                            type: "post",
                            data: {
                                id: deleteids_arr
                            },
                            success: function(response) {
                                setTimeout(() => {
                                    notice.hideLoading()

                                    if (response.status === 'error') {
                                        var message = response.message;
                                        message.forEach(function(entry) {
                                            notify_payload.call(this,
                                                'Required', entry, 'warning'
                                            );
                                        });
                                    } else {
                                        notify_payload.call(this, 'Success', response
                                            .message, 'success');

                                        $('#empModal').modal('hide');
                                        reload.call();
                                        $('#checkAll').prop('checked', false);
                                    }
                                }, 500)
                            },
                            error: function(response) {
                                setTimeout(() => {
                                    notice.hideLoading()

                                    notify_payload.call(this, 'Error', response
                                        .statusText, 'error');
                                }, 500)
                            }
                        });
                    }
                });
            }

        });
    </script>
@endpush

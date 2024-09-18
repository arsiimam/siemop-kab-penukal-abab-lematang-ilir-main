@extends('layouts.app')

@section('header')
<h1>{{ __('Uraian Proyek') }}</h1>
@endsection

@section('action-button')
<div class="top-right-button-container">
    <div class="btn-group" role="group">
        <a href="{{ route('activity-report.index') }}" class="btn btn-danger top-right-button mb-2 mr-1" style="width: 80px;">{{ __('Kembali') }}
        </a>
        @can('Create Activity Report')
        <button type="button" class="btn btn-primary top-right-button mb-2 mr-1 modal-btn-xl" style="width: 80px;" data-title="{{ __('Tambah Data Baru') }}" data-url="{{ route('description-of-activities.create', $proyek->id) }}" data-toggle="modal" data-backdrop="static">{{ __('Tambah') }}</button>
        @endcan

        @can('Delete Activity Report')
        <button type="button" class="btn btn-info top-right-button mb-2 dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 80px;">
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
    <a href="{{ route('activity-report.index') }}">{{ __('Proyek') }}</a>
</li>
<li class="breadcrumb-item active" aria-current="page">
    {{ $proyek->program->title ?? '' }}
</li>
@endsection

@section('content')
<div class="row mb-2">
    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card dashboard-small-chart-analytics" style="height: 115px !important;">
            <div class="card-header p-0 position-relative">
                <div class="position-absolute handle card-icon" style="top: 25px !important;">
                    <i class="iconsminds-coins dashboard-icon-big-size"></i>
                </div>
            </div>
            <div class="card-body">
                <p class="lead color-theme-1 mb-1 value">Rp. {{ number_format($proyek->pagu_indikatif) }}</p>
                <p class="mb-0 label">Anggaran / Pagu Indikatif</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card dashboard-small-chart-analytics" style="height: 115px !important;">
            <div class="card-header p-0 position-relative">
                <div class="position-absolute handle card-icon" style="top: 25px !important;">
                    <i class="iconsminds-money-bag dashboard-icon-big-size"></i>
                </div>
            </div>
            <div class="card-body">
                <p class="lead color-theme-1 mb-1 value">Rp. 0</p>
                <p class="mb-0 label">Realisasi</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header p-0 position-relative">
                <div class="position-absolute handle card-icon">
                    <i class="iconsminds-suitcase dashboard-icon-size"></i>
                </div>
            </div>
            <div class="card-body d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Total Program Pokok</h6>
                <div class="dashboard-layout">
                    <h4 class="dashboard-heading">
                        8
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header p-0 position-relative">
                <div class="position-absolute handle card-icon">
                    <i class="iconsminds-affiliate dashboard-icon-size"></i>
                </div>
            </div>
            <div class="card-body d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Total Detail Kegiatan</h6>
                <div class="dashboard-layout">
                    <h4 class="dashboard-heading">
                        0
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered " id="activity-table">
                    <thead>
                        <tr>
                            <th>Uraian Urusan, Program dan Kegiatan</th>
                            <th>Pagu Indikatif <small>(Rp)</small></th>
                            <th>Target Kinerja <small>(%)</small></th>
                            <th>Fisik <small>(Rp)</small></th>
                            <th>Non Fisik <small>(Rp)</small></th>
                            <th>Keuangan <small>(Rp)</small></th>
                            <th>Prosentase <small>(%)</small></th>
                            <th>Pelaksana / Kontruktor</th>
                            <th>Harga Kontrak <small>(Rp)</small></th>
                            <th>Lokasi Kegiatan</th>
                            <th width="160">Aksi</th>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input align-self-center data-table-rows-check" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <button type="button" onclick="reload();">reload</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let table = $('#activity-table').DataTable({
        bLengthChange: false,
        paginate: false,
        destroy: true,
        scrollX: true,
        scrollY: false,
        // info: false,
        sDom: '<"row view-filter"<"col-sm-12"<"float-right"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        processing: true,
        ordering: false,
        columnDefs: [{
            targets: [6],
            className: 'text-center'
        }],
        language: {
            processing: "Processing...",
            paginate: {
                previous: "<i class='simple-icon-arrow-left'></i>",
                next: "<i class='simple-icon-arrow-right'></i>"
            }
        },
        drawCallback: function() {
            $($(".dataTables_wrapper .pagination li:first-of-type"))
                .find("a")
                .addClass("prev");
            $($(".dataTables_wrapper .pagination li:last-of-type"))
                .find("a")
                .addClass("next");

            $(".dataTables_wrapper .pagination").addClass("pagination-sm");
        }
    })

    $(document).ready(function() {
        reload();
    })

    function reload() {
        $.ajax({
            type: "POST",
            url: "{{ url('api/child-activity-report/list_json') }}",
            data: {
                report_id: {
                    {
                        $proyek - > id
                    }
                },
            },
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                notice.showLoading({
                    type: 'dots',
                    title: 'Loading',
                });
            },
            success: function(obj, textstatus) {
                table.clear().draw();
                table.rows.add(obj.data).draw();

                setTimeout(() => {
                    notice.hideLoading()
                }, 500)
            },
            error: function(response) {
                notify_payload.call(this, 'Error', response.statusText, 'error');

                setTimeout(() => {
                    notice.hideLoading()
                }, 500)
            }
        });
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
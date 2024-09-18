@extends('layouts.app')

@section('header')
    <h1>{{ __('Uraian kegiatan') }}</h1>
@endsection

@section('action-button')
    <div class="top-right-button-container">
        <div class="btn-group" role="group">
            <a href="{{ route('activity-report.index') }}" class="btn btn-info top-right-button mb-2 mr-1"
                style="width: 80px;">{{ __('Kembali') }}
            </a>
            @can('Create Activity Report')
                <button type="button" class="btn btn-primary top-right-button mb-2 mr-1 modal-btn-xl" style="width: 80px;"
                    data-title="{{ __('Tambah Data Baru') }}"
                    data-url="{{ route('description-of-activities.create', $proyek->id) }}" data-toggle="modal"
                    data-backdrop="static">{{ __('Tambah') }}</button>
            @endcan
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('activity-report.index') }}">{{ __('Uraian kegiatan') }}</a>
    </li>
    <li class="breadcrumb-item">
        {{ $proyek->institute->name }}
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <b>{{ $proyek->program->title ?? '' }}</b>
    </li>
@endsection

@section('css_addon')
    <style>
        .table-bordered {
            border: 1px solid #030303;
        }

        .table td,
        .table th {
            border-color: #030303 !important;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 1px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-2">
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card card-bg dashboard-small-chart-analytics" style="height: 115px !important;">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon" style="top: 25px !important;">
                        <i class="iconsminds-coins dashboard-icon-big-size"></i>
                    </div>
                </div>
                <div class="card-body">
                    {{-- color-theme-1 --}}
                    <p class="lead mb-1 value">Rp. {{ number_format($proyek->pagu_indikatif) }}</p>
                    <p class="mb-0 label">Anggaran / Pagu Indikatif</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card card-bg dashboard-small-chart-analytics" style="height: 115px !important;">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon" style="top: 25px !important;">
                        <i class="iconsminds-money-bag dashboard-icon-big-size"></i>
                    </div>
                </div>
                <div class="card-body">
                    <p class="lead mb-1 value" id="realization">Rp. 0</p>
                    <p class="mb-0 label">Realisasi</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card card-bg">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon">
                        <i class="iconsminds-suitcase dashboard-icon-size"></i>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Total Kegiatan</h6>
                    <div class="dashboard-layout">
                        <h4 class="dashboard-heading" id="total_program">
                            0
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="card card-bg">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon">
                        <i class="iconsminds-affiliate dashboard-icon-size"></i>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Total Sub Kegiatan</h6>
                    <div class="dashboard-layout">
                        <h4 class="dashboard-heading" id="total_details">
                            0
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="activity-table">
                        <thead style="background-color: #D8E4BC !important;">
                            <tr>
                                @if ($proyek->status != 'done')
                                    @can('Delete Activity Report')
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input align-self-center data-table-rows-check"
                                                    id="checkAll">
                                                <label class="custom-control-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                    @endcan
                                    <th>Aksi</th>
                                @endif
                                <th>No.</th>
                                <th>Uraian Urusan, Program dan Kegiatan</th>
                                <th>Pagu Indikatif <small>(Rp)</small></th>
                                <th>Target Kinerja</th>
                                <th>Target Fisik</th>
                                <th>Target Keuangan</th>
                                <th>Fisik <small>(%)</small></th>
                                {{-- <th>Non Fisik <small>(%)</small></th> --}}
                                <th>Keuangan <small>(Rp)</small></th>
                                <th>Prosentase <small>(%)</small></th>
                                <th>PPK</th>
                                <th>PPTK</th>
                                {{-- <th>Pelaksana / Kontruktor</th>
                                <th>Harga Kontrak <small>(Rp)</small></th> --}}
                                <th>Lokasi Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @can('Delete Activity Report')
        <script>
            var status = '{{ $proyek->status }}';
            if (status == 'done') {
                var text_center = [0, 4, 5, 6, 8];
                var text_right = [2, 7];
                var button = [{
                    text: 'Buka Akses Edit',
                    action: function(e, dt, node, config) {
                        update_status.call()
                    }
                }];
            } else {
                var text_center = [0, 1, 2, 6, 7, 8, 10];
                var text_right = [4, 9];
                var button = [{
                    text: 'Hapus Data',
                    className: 'btn-danger',
                    attr: {
                        id: 'delete_record'
                    },
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                }, {
                    text: 'Tandai Selesai',
                    action: function(e, dt, node, config) {
                        update_status.call()
                    }
                }];
            }
        </script>
    @elsecan('Manage Activity Report')
        <script>
            var status = '{{ $proyek->status }}';
            if (status == 'done') {
                var text_center = [0, 4, 5, 6, 8];
                var text_right = [2, 7];
                var button = [{
                    text: 'Buka Akses Edit',
                    action: function(e, dt, node, config) {
                        update_status.call()
                    }
                }];
            } else {
                var text_center = [0, 1, 5, 6, 7, 9];
                var text_right = [3, 8];
                var button = [{
                    text: 'Tandai Selesai',
                    action: function(e, dt, node, config) {
                        update_status.call()
                    }
                }];
            }
        </script>
    @endcan

    <script>
        let table = $('#activity-table').DataTable({
            bLengthChange: false,
            paginate: false,
            destroy: true,
            scrollX: true,
            scrollY: false,
            // info: false,
            ajax: {
                url: "{{ url('api/child-activity-report/list_json') }}",
                type: "POST",
                data: {
                    report_id: {{ $proyek->id }},
                },
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                },
                complete: function(response) {
                    // console.log(response.responseJSON);
                    document.getElementById("realization").innerHTML = response.responseJSON.realization;
                    document.getElementById("total_program").innerHTML = response.responseJSON.total_program;
                    document.getElementById("total_details").innerHTML = response.responseJSON.total_details;
                },
            },
            sDom: '<"dt-top-container"<"dt-center-in-div"B><l><f>r>t<ip>',
            processing: true,
            ordering: false,
            columnDefs: [{
                targets: text_right,
                className: 'text-right'
            }, {
                targets: text_center,
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
            },
            rowCallback: function(row, data) {
                if (status === 'done') {
                    var parameter = data[0];
                    if (parameter.search("main_program") >= 0) {
                        $(row).css('background-color', '#76933C');
                    } else if (parameter.search("sub_program") >= 0) {
                        $(row).css('background-color', '#CCC0DA');
                    } else {
                        $(row).css('background-color', '');
                    }
                } else {
                    var parameter = data[2];
                    if (parameter.search("main_program") >= 0) {
                        $(row).css('background-color', '#76933C');
                    } else if (parameter.search("sub_program") >= 0) {
                        $(row).css('background-color', '#CCC0DA');
                    } else {
                        $(row).css('background-color', '');
                    }
                }
            },
            buttons: button,
            initComplete: function(rows, response) {
                // console.log(response);
                // document.getElementById("realization").innerHTML = response.realization;
            }
        })

        $(document).ready(function() {
            var theme = localStorage.getItem('dore-theme-color');

            if (theme.search("dark") >= 0) {
                $('.table-bordered').css('border-color', '#424242');
            }
        })

        function reload() {
            table.ajax.reload();
        }

        function update_status() {
            Swal.fire({
                position: 'center',
                title: 'Apakah Anda Yakin ?',
                text: "Merubah Status Data ?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'PUT',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Authorization",
                                "Bearer {{ session('bearerToken') }}");
                            notice.showLoading({
                                type: 'dots',
                                title: 'Loading',
                            });
                        },
                        url: "{{ url('api/activity-report/update-status/' . $proyek->id) }}",
                        success: function(response) {
                            console.log('sukses')
                            setTimeout(() => {
                                location.reload(true);
                            }, 500)
                        },
                        error: function(response) {
                            notice.hideLoading()
                            console.log(response.statusText)
                        }
                    });
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
                            url: "{{ url('api/child-activity-report/bulk-delete') }}",
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

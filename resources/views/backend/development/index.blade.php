@extends('layouts.app')

@section('header')
    <h1>{{ __('Pembangunan Fisik & Non Fisik') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('activity-report.index') }}">{{ __('Pembangunan Fisik & Non Fisik') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <b>Data</b>
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
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row item-select mb-2">
                        @can('Manage Any Activity Program')
                            <div class="form-group col-md-3 mb-2">
                                {{ Form::select('institute_id', $institutes, null, [
                                    'class' => 'form-control select2-default',
                                    'placeholder' => __('Select Data'),
                                    'data-width' => '100%',
                                    'filterable' => 'true',
                                    'id' => 'institute',
                                    'required',
                                ]) }}
                                <small><i>Perangkat Daerah</i></small>
                            </div>
                        @endcan
                        <div class="form-group col-md-3 mb-2">
                            {{ Form::select('activity_programs', $activity_programs, null, [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'activity_programs',
                                'required',
                            ]) }}
                            <small><i>Program Kerja</i></small>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            {{ Form::select('activity', [], date('m'), [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'activity',
                                'required',
                            ]) }}
                            <small><i>Kegiatan</i></small>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            {{ Form::select('sub_activity', [], date('Y'), [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'sub_activity',
                                'required',
                            ]) }}
                            <small><i>Sub Kegiatan</i></small>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            {{ Form::select('type', ['FISIK' => 'FISIK', 'NON FISIK' => 'NON FISIK'], date('Y'), [
                                'class' => 'form-control select2-default',
                                'placeholder' => __('Select Data'),
                                'data-width' => '100%',
                                'filterable' => 'true',
                                'id' => 'type',
                                'required',
                            ]) }}
                            <small><i>Jenis</i></small>
                        </div>
                        <div class="form-group col-md-3 mb-2">
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
                        <div class="form-group col-md-3 mb-2">
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
                        <div class="form-group col-md-3 mb-0">
                            <div class="btn-group mt-1" role="group">
                                <button type="button" class="btn btn-info btn-sm mb-2 mr-1"
                                    onclick="reload.call();">{{ __('Tampil Data') }}
                                </button>
                                @can('Create Development')
                                    <button type="button" class="btn btn-primary btn-sm mb-2 mr-1 modal-create-data"
                                        data-title="{{ __('Tambah Data Pembangunan Fisik & Non Fisik') }}" data-url=""
                                        data-toggle="modal" data-backdrop="static">{{ __('Tambah Data') }}</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="activity-table">
                        <thead style="background-color: #D8E4BC !important;">
                            <tr>
                                @can('Delete Development')
                                    <th rowspan="2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                class="custom-control-input align-self-center data-table-rows-check"
                                                id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                @endcan
                                <th rowspan="2" class="text-center">No.</th>
                                <th rowspan="2">Nama Paket Pekerjaan</th>
                                <th rowspan="2">Pagu Anggaran <small>(Rp.)</small></th>
                                <th rowspan="2">Sumber Dana</th>
                                <th rowspan="2">Jenis</th>
                                {{-- <th>Progress Pekerjaan</th> --}}
                                <th colspan="4" class="text-center">Kontrak</th>
                                <th rowspan="2">Target Progres</th>
                                {{-- <th rowspan="2">Progress Pekerjaan</th> --}}
                                <th rowspan="2">Realisasi Progres</th>
                                <th rowspan="2">Dokumentasi</th>
                                <th rowspan="2">Nama PPK</th>
                                <th rowspan="2">Nama PPTK</th>
                                {{-- <th rowspan="2">Harga Kontrak <small>(Rp.)</small></th> --}}
                                <th rowspan="2">Pelaksana / Kontraktor</th>
                                <th rowspan="2">Lokasi Kegiatan</th>
                            </tr>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Harga <small>(Rp.)</small></th>
                                <th>Durasi</th>
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

    @can('Delete Development')
        <script>
            var button_list = [{
                text: 'Hapus Data',
                className: 'btn-danger',
                attr: {
                    id: 'delete_record'
                },
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                }
            }];

            var columnDefs = [{
                targets: [3, 8],
                className: 'text-right'
            }, {
                targets: [1, 10, 11, 12],
                className: 'text-center'
            }];
        </script>
    @else
        <script>
            var button_list = [];

            var columnDefs = [{
                targets: [2, 7],
                className: 'text-right'
            }, {
                targets: [0, 9, 10, 11],
                className: 'text-center'
            }]
        </script>
    @endcan

    <script>
        var table = $('#activity-table').DataTable({
            bLengthChange: false,
            paginate: false,
            destroy: true,
            scrollX: true,
            scrollY: false,
            // info: false,
            ajax: {
                url: "{{ url('api/development/list_json') }}",
                type: "POST",
                data: {
                    program_id: function() {
                        return $('#activity_programs').val()
                    },
                    subactivity_id: function() {
                        return $('#sub_activity').val()
                    },
                    type: function() {
                        return $('#type').val()
                    },
                    month: function() {
                        return $('#month').val()
                    },
                    year: function() {
                        return $('#year').val()
                    }
                },
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                    notice.showLoading({
                        type: 'dots',
                        title: 'Loading',
                    });
                },
                complete: function(response) {
                    setTimeout(() => {
                        notice.hideLoading()
                    }, 500)

                    if ($('#activity_programs').val() !== '') {
                        if (response.responseJSON.status === 'error') {
                            var message = response.responseJSON.message;
                            message.forEach(function(entry) {
                                notify_payload.call(this, 'Required',
                                    entry,
                                    'warning');
                            });
                        }
                    }
                }
            },
            sDom: '<"dt-top-container"<"dt-center-in-div"B><l><f>r>t<ip>',
            processing: true,
            ordering: false,
            columnDefs: columnDefs,
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
            buttons: button_list,
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

        $(document).on("click", ".modal-create-data", function() {

            var program_id = $('#activity_programs').val()
            var sub_activity = $('#sub_activity').val()
            var type = $('#type').val()

            if (program_id === '') {
                notify_payload.call(this, 'Warning', 'Progam Kerja Belum Dipilih.', 'warning');
            }
            if (sub_activity === '' || sub_activity == null) {
                notify_payload.call(this, 'Warning', 'Sub Kegiatan Belum Dipilih.', 'warning');
            }
            if (type === '') {
                notify_payload.call(this, 'Warning', 'Jenis Belum Dipilih.', 'warning');
            }

            if (program_id !== '' && sub_activity !== '' && sub_activity != null && type !== '') {
                var url = "{{ route('development.create') }}";
                var title = $(this).data('title');
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        program_id: program_id,
                        subactivity_id: sub_activity,
                        type: type,
                        month: $('#month').val(),
                        year: $('#year').val()
                    },
                    success: function(response) {
                        if (response.status === 'error') {
                            $.toast({
                                heading: 'Error',
                                text: response.message,
                                icon: 'error',
                                position: 'top-right',
                                hideAfter: 3000,
                                showHideTransition: 'slide'
                            })
                        } else {
                            $('.modal-title-load').text(title);
                            $('.modal-body-load').html(response);
                            $('#empModal-xl').modal({
                                show: true
                            });

                            $('.select2-modal').select2({
                                theme: "bootstrap",
                                maximumSelectionSize: 6,
                                containerCssClass: ":all:",
                                placeholder: "None / Select",
                                allowClear: true
                            });
                        }
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: response.responseJSON.error,
                            icon: 'error',
                            position: 'top-right',
                            hideAfter: 3000,
                            showHideTransition: 'slide'
                        })
                    }

                });
            }


        });


        $('#month, #year').on('select2:select', function(e) {
            var select_val = $(e.currentTarget).val();
            var parent = this;

            $('#activity').empty();
            $('#sub_activity').empty();

            selected_programs.call();
        });

        $('#institute').on('select2:select', function(e) {
            var select_val = $(e.currentTarget).val();
            var parent = this;

            $('#activity_programs').empty();
            $('#activity').empty();
            $('#sub_activity').empty();

            selected_institute.call();
        });

        $('#activity_programs').on('select2:select', function(e) {
            var select_val = $(e.currentTarget).val();
            var parent = this;

            $('#activity').empty();
            $('#sub_activity').empty();

            selected_programs.call();
        });

        $('#activity').on('select2:select', function(e) {
            var select_val = $(e.currentTarget).val();
            var parent = this;
            $('#sub_activity').empty();

            selected_activity.call();
        });

        function selected_institute() {
            var list_json = {
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                },
                url: "{{ url('api/development/programs_json') }}",
                method: "POST",
                data: {
                    institute_id: function() {
                        return $('#institute').val()
                    }
                },
                timeout: 0,
            };

            $.ajax(list_json).done(function(response) {
                var data = $.map(response.data, function(obj) {
                    obj.id = obj.id;
                    obj.text = obj.title;
                    return obj;
                });
                $('#activity_programs').select2({
                    data: data,
                    placeholder: "None / Select",
                    theme: "bootstrap",
                    maximumSelectionSize: 6,
                    containerCssClass: ":all:"
                });
            });
        }

        function selected_programs() {
            var list_json = {
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                },
                url: "{{ url('api/development/activity_json') }}",
                method: "POST",
                data: {
                    program_id: function() {
                        return $('#activity_programs').val()
                    },
                    tahun: function() {
                        return $('#year').val()
                    },
                    bulan: function() {
                        return $('#month').val()
                    }
                },
                timeout: 0,
            };

            $.ajax(list_json).done(function(response) {
                var data = $.map(response.data, function(obj) {
                    obj.id = obj.id;
                    obj.text = obj.title;
                    return obj;
                });
                $('#activity').select2({
                    data: data,
                    placeholder: "None / Select",
                    theme: "bootstrap",
                    maximumSelectionSize: 6,
                    containerCssClass: ":all:"
                });
            });
        }

        function selected_activity() {
            var list_json = {
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                },
                url: "{{ url('api/development/activity_json') }}",
                method: "POST",
                data: {
                    program_id: function() {
                        return $('#activity_programs').val()
                    },
                    activity_id: function() {
                        return $('#activity').val()
                    },
                    tahun: function() {
                        return $('#year').val()
                    },
                    bulan: function() {
                        return $('#month').val()
                    }
                },
                timeout: 0,
            };

            $.ajax(list_json).done(function(response) {
                var data = $.map(response.data, function(obj) {
                    obj.id = obj.id;
                    obj.text = obj.title;
                    return obj;
                });
                $('#sub_activity').select2({
                    data: data,
                    placeholder: "None / Select",
                    theme: "bootstrap",
                    maximumSelectionSize: 6,
                    containerCssClass: ":all:"
                });
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
                            url: "{{ url('api/development/bulk-delete') }}",
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

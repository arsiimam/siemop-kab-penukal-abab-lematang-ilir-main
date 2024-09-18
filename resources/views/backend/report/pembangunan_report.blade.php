@extends('layouts.app')

@section('header')
    <h1>{{ __('Laporan Pembangunan Fisik & Non Fisik') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <b>Data Laporan</b>
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
                        @else
                            @php
                                $user = Auth::user();
                                $institute_user = $user->institute;
                            @endphp
                            <div style="display: none">
                                {{ Form::select('institute_id', [$user->institute_id => $institute_user->name], $user->institute_id, [
                                    'class' => 'form-control select2-default',
                                    'placeholder' => __('Select Data'),
                                    'data-width' => '100%',
                                    'filterable' => 'true',
                                    'id' => 'institute',
                                    'required',
                                ]) }}
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
                        <div class="form-group col-md-3 mb-2">
                            <div class="btn-group mt-1" role="group">
                                <button type="button" class="btn btn-info btn-sm mb-2 mr-1"
                                    onclick="reload.call();">{{ __('Tampil Data') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-sm mb-2 mr-1"
                                    onclick="download.call()">{{ __('Unduh Data') }}</button>
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
                                <th rowspan="2" class="text-center">No.</th>
                                <th rowspan="2">Nama Paket Pekerjaan</th>
                                <th rowspan="2">Pagu Anggaran <small>(Rp.)</small></th>
                                <th rowspan="2">Sumber Dana</th>
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
    <script>
        var table = $('#activity-table').DataTable({
            bLengthChange: false,
            paginate: false,
            destroy: true,
            scrollX: true,
            scrollY: false,
            // info: false,
            ajax: {
                url: "{{ url('api/report/pembangunan/index_json') }}",
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
            sDom: '<"row view-filter"<"col-sm-12"<"float-left"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
            processing: true,
            ordering: false,
            columnDefs: [{
                targets: [2, 6],
                className: 'text-right'
            }, {
                targets: [0, 8, 9],
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
            var theme = localStorage.getItem('dore-theme-color');

            if (theme.search("dark") >= 0) {
                $('.table-bordered').css('border-color', '#424242');
            }
        })

        function reload() {
            table.ajax.reload();
        }

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

        function download() {

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
                $.ajax({
                    url: "{{ url('api/report/pembangunan/download') }}",
                    type: "post",
                    data: {
                        institute_id: function() {
                            return $('#institute').val()
                        },
                        program_id: function() {
                            return $('#activity_programs').val()
                        },
                        subactivity_id: function() {
                            return $('#sub_activity').val()
                        },
                        type: function() {
                            return $('#type').val()
                        },
                        tahun: function() {
                            return $('#year').val()
                        },
                        bulan: function() {
                            return $('#month').val()
                        },
                        val_institute: function() {
                            return $("#institute :selected").text()
                        },
                        val_program: function() {
                            return $("#activity_programs :selected").text()
                        },
                        val_activity: function() {
                            return $("#activity :selected").text()
                        },
                        val_sub_activity: function() {
                            return $("#sub_activity :selected").text()
                        },
                        val_type: function() {
                            return $("#type :selected").text()
                        },
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer {{ session('bearerToken') }}");
                        notice.showLoading({
                            type: 'dots',
                            title: 'Loading',
                        });
                    },
                    success: function(response) {

                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        var institute = $('#institute').val();
                        var addon_label = '';
                        if (institute !== '') {
                            addon_label = $("#institute :selected").text();
                        }
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Laporan Pembangunan " + $("#institute :selected").text() + ' - ' + $(
                                "#sub_activity :selected").text() + ' ' + $(
                                '#month :selected')
                            .text() + " " + $('#year').val() + ".pdf";

                        link.click();

                        setTimeout(() => {
                            notice.hideLoading()
                        }, 500)
                    },
                    error: function(obj, textstatus) {

                        notify_payload.call(this, 'Warning', obj.statusText, 'warning');

                        setTimeout(() => {
                            notice.hideLoading()
                        }, 500)
                    }
                });
            }
        }
    </script>
@endpush

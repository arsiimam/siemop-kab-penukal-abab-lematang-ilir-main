@extends('layouts.app')

@section('header')
    <h1>{{ __('Laporan Realisasi Fisik & Keuangan') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        Data laporan
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
                    <div class="row justify-content-end">
                        @can('Manage Any Activity Program')
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
                        @else
                            @php
                                $user = Auth::user();
                                $institute_user = $user->institute;
                            @endphp
                            <div style="display: none;">
                                {{ Form::select('institute_id', [$user->institute_id => $institute_user->name], $user->institute_id, [
                                    'class' => 'form-control select2-default',
                                    'placeholder' => __('Select Data'),
                                    'data-width' => '100%',
                                    'filterable' => 'true',
                                    'id' => 'institute_id',
                                    'required',
                                ]) }}
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
    <div class="row mb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="activity-table">
                        <thead style="background-color: #D8E4BC !important;">
                            <tr>
                                <th rowspan="3" class="text-center">No.</th>
                                <th rowspan="3">Uraian Organisasi, Program, Kegiatan, dan Sub Kegiatan</th>
                                <th rowspan="3">Pagu Indikatif <small>(Rp)</small></th>
                                <th rowspan="3">Target Kinerja</th>
                                <th colspan="2" class="text-center">Target</th>
                                <th colspan="3" class="text-center">Realisasi</th>
                                <th rowspan="3">PPK</th>
                                <th rowspan="3">PPTK</th>
                                {{-- <th rowspan="3">Pelaksana / Kontruktor</th>
                                <th rowspan="3">Harga Kontrak <small>(Rp)</small></th> --}}
                                <th rowspan="3">Lokasi Kegiatan</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Fisik <small>(%)</small></th>
                                <th rowspan="2">Keuangan <small>(%)</small></th>
                                <th rowspan="2">Fisik <small>(%)</small></th>
                                {{-- <th rowspan="2">Non Fisik <small>(%)</small></th> --}}
                                <th colspan="2" class="text-center">Keuangan</th>
                            </tr>
                            <tr>
                                <th class="text-center"><small>(Rp)</small></th>
                                <th class="text-center"><small>(%)</small></th>
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
                url: "{{ url('api/report/realisasi/index_json') }}",
                type: "POST",
                data: {
                    dinas_id: function() {
                        return $('#institute_id').val()
                    },
                    tahun: function() {
                        return $('#year').val()
                    },
                    bulan: function() {
                        return $('#month').val()
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
                }
            },
            sDom: '<"dt-top-container"<"dt-center-in-div"B><l><f>r>t<ip>',
            processing: true,
            ordering: false,
            columnDefs: [{
                targets: [2, 7],
                className: 'text-right'
            }, {
                targets: [0, 4, 5, 6, 8],
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
                // console.log(data);
                var parameter = data[0];
                if (parameter.search("main_program") >= 0) {
                    $(row).css('background-color', '#76933C');
                } else if (parameter.search("sub_program") >= 0) {
                    $(row).css('background-color', '#CCC0DA');
                } else {
                    $(row).css('background-color', '');
                }

            },
            buttons: [{
                text: '<i class="iconsminds-data-download"></i> Unduh',
                className: 'btn-success',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                action: function(e, dt, node, config) {
                    download.call();
                }
            }]
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

        $(document).on('click', ".btn-filter", function() {
            reload.call();
            console.log($('#institute_id').val());
        });

        $('[filterable="true"]').on('change', function(e) {
            table.ajax.reload();
        });

        function download() {
            $.ajax({
                url: "{{ url('api/report/realisasi/download') }}",
                type: "post",
                data: {
                    dinas_id: function() {
                        return $('#institute_id').val()
                    },
                    tahun: function() {
                        return $('#year').val()
                    },
                    bulan: function() {
                        return $('#month').val()
                    }
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
                    var institute = $('#institute_id').val();
                    var addon_label = '';
                    if (institute !== '') {
                        addon_label = $("#institute_id :selected").text();
                    }
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Laporan Realisasi Fisik dan Keuangan " + addon_label + " " + $(
                        '#month :selected').text() + " " + $('#year').val() + ".pdf";
                    link.click();

                    setTimeout(() => {
                        notice.hideLoading()
                    }, 500)
                },
                error: function(obj, textstatus) {
                    alert(obj.msg);

                    setTimeout(() => {
                        notice.hideLoading()
                    }, 500)
                }
            });
        }
    </script>
@endpush

@extends('layouts.app')

@section('header')
    <h1>{{ __('Statistik') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        Data Statistik
    </li>
@endsection

@section('content')
    <div class="row mb-2">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="form-group col-md-3 mb-0">
                            {{ Form::select(
                                'order',
                                [
                                    'desc' => 'Persentase Tertinggi',
                                    'asc' => 'Persentase Terendah',
                                ],
                                'desc',
                                [
                                    'class' => 'form-control select2-default',
                                    'placeholder' => __('Select Data'),
                                    'data-width' => '100%',
                                    'filterable' => 'true',
                                    'id' => 'order',
                                    'required',
                                ],
                            ) }}
                            <small><i>Filter Berdasarkan</i></small>
                        </div>
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
                        <thead class="thead-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama Perangkat Daerah</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Program</th>
                                <th>Persentase (%)</th>
                                <th>Realisasi (Rp)</th>
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
                url: "{{ url('api/statistics/index_json') }}",
                type: "POST",
                data: {
                    order: function() {
                        return $('#order').val()
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
            // sDom: '<"dt-top-container"<"dt-center-in-div"B><l><f>r>t<ip>',
            sDom: '<"row view-filter"<"col-sm-12"<"float-left"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
            processing: true,
            ordering: false,
            language: {
                processing: "Processing...",
                paginate: {
                    previous: "<i class='simple-icon-arrow-left'></i>",
                    next: "<i class='simple-icon-arrow-right'></i>"
                }
            },
            columns: [{
                    "data": "no"
                },
                {
                    "data": "institute"
                },
                {
                    "data": "month"
                },
                {
                    "data": "year"
                },
                {
                    "data": "programs"
                },
                {
                    "data": "percentage"
                },
                {
                    "data": "realization"
                },
            ],
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

        $('[filterable="true"]').on('change', function(e) {
            table.ajax.reload();
        });
    </script>
@endpush

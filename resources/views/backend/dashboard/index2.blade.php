@extends('layouts.app')

@section('header')
    <h1>{{ __('Beranda') }}</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Data') }}</li>
@endsection

@section('content')
    <div class="row">

        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon">
                        <i class="iconsminds-box-full dashboard-icon-size"></i>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Program Kerja</h6>
                    <div class="dashboard-layout">
                        <h4 class="dashboard-heading">
                            {{ $programs }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header p-0 position-relative">
                    <div class="position-absolute handle card-icon">
                        <i class="iconsminds-line-chart-1 dashboard-icon-size"></i>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kegiatan Bulan Ini</h6>
                    <div class="dashboard-layout">
                        <h4 class="dashboard-heading">
                            {{ $proyek }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card" style="height: 400px !important;">
                <div class="card-body">
                    <h5 class="card-title">Grafik Realisasi Program Tahun {{ date('Y') }}</h5>
                    <div class="dashboard-line-chart chart">
                        <canvas id="trxChart"></canvas>
                        {{-- <canvas id="conversionChart"></canvas> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Kalender</h5>
                    <div class="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Anggaran & Realisasi Perangkat Daerah {{ recent_month() }}</h5>
                    <table class="table table-bordered table-hover responsive info-table"
                        data-order="[[ 0, &quot;desc&quot; ]]">
                        <thead class="thead-light">
                            <tr>
                                <th>Program</th>
                                <th>Perangkat Daerah</th>
                                <th>Pagu Infikatif</th>
                                <th>Realisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($table_realization as $item)
                                <tr>
                                    <td>
                                        <b>{{ $item->program->title }}</b>
                                    </td>
                                    <td>{{ $item->institute->name }}</td>
                                    <td class="text-right">{{ custom_number_format($item->pagu_indikatif) }}</td>
                                    <td class="text-right">{{ custom_number_format($item->realization) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Daftar Pengumuman</h5>
                    <table class="table table-bordered table-hover responsive info-table"
                        data-order="[[ 0, &quot;desc&quot; ]]">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th style="max-width: 50%;">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcement as $item)
                                <tr>
                                    <td>
                                        <b>{{ $item->title }}</b>
                                    </td>
                                    <td>{{ date('d F Y', strtotime($item->start_date)) }}</td>
                                    <td>{{ date('d F Y', strtotime($item->end_date)) }}</td>
                                    <td>{{ $item->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var table = $('.info-table').DataTable({
            bLengthChange: false,
            // paginate: false,
            destroy: true,
            scrollX: true,
            scrollY: false,
            info: false,
            sDom: '<"row view-filter"<"col-sm-12"<"float-left"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
            processing: false,
            ordering: false,
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
    </script>

    <script>
        var themeColor1 = '#6fb327';
        var themeColor2 = '#51c878';
        var themeColor3 = '#aaba9f';
        var themeColor1_10 = 'rgba(111, 179, 39, 0.1)';
        var themeColor2_10 = 'rgba(81, 200, 120, 0.1)';
        var themeColor3_10 = 'rgba(170, 186, 159, 0.1)';
        var primaryColor = '#3a3a3a';
        var foregroundColor = '#fff';
        var separatorColor = '#d7d7d7';

        if (typeof Chart !== "undefined") {
            Chart.defaults.global.defaultFontFamily = "'Nunito', sans-serif";

            Chart.defaults.LineWithShadow = Chart.defaults.line;
            Chart.controllers.LineWithShadow = Chart.controllers.line.extend({
                draw: function(ease) {
                    Chart.controllers.line.prototype.draw.call(this, ease);
                    var ctx = this.chart.ctx;
                    ctx.save();
                    ctx.shadowColor = "rgba(0,0,0,0.15)";
                    ctx.shadowBlur = 10;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 10;
                    ctx.responsive = true;
                    ctx.stroke();
                    Chart.controllers.line.prototype.draw.apply(this, arguments);
                    ctx.restore();
                }
            });

            var chartTooltip = {
                backgroundColor: foregroundColor,
                titleFontColor: primaryColor,
                borderColor: separatorColor,
                borderWidth: 0.5,
                bodyFontColor: primaryColor,
                bodySpacing: 10,
                xPadding: 15,
                yPadding: 15,
                cornerRadius: 0.15,
                displayColors: false,
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Rp. ' + new Intl.NumberFormat().format(tooltipItem.yLabel);
                    }
                }
            };

            var centerTextPlugin = {
                afterDatasetsUpdate: function(chart) {},
                beforeDraw: function(chart) {
                    var width = chart.chartArea.right;
                    var height = chart.chartArea.bottom;
                    var ctx = chart.chart.ctx;
                    ctx.restore();

                    var activeLabel = chart.data.labels[0];
                    var activeValue = chart.data.datasets[0].data[0];
                    var dataset = chart.data.datasets[0];
                    var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                    var total = meta.total;

                    var activePercentage = parseFloat(
                        ((activeValue / total) * 100).toFixed(1)
                    );
                    activePercentage = chart.legend.legendItems[0].hidden ?
                        0 :
                        activePercentage;

                    if (chart.pointAvailable) {
                        activeLabel = chart.data.labels[chart.pointIndex];
                        activeValue =
                            chart.data.datasets[chart.pointDataIndex].data[chart.pointIndex];

                        dataset = chart.data.datasets[chart.pointDataIndex];
                        meta = dataset._meta[Object.keys(dataset._meta)[0]];
                        total = meta.total;
                        activePercentage = parseFloat(
                            ((activeValue / total) * 100).toFixed(1)
                        );
                        activePercentage = chart.legend.legendItems[chart.pointIndex].hidden ?
                            0 :
                            activePercentage;
                    }

                    ctx.font = "36px" + " Nunito, sans-serif";
                    ctx.fillStyle = primaryColor;
                    ctx.textBaseline = "middle";

                    var text = activePercentage + "%",
                        textX = Math.round((width - ctx.measureText(text).width) / 2),
                        textY = height / 2;
                    ctx.fillText(text, textX, textY);

                    ctx.font = "14px" + " Nunito, sans-serif";
                    ctx.textBaseline = "middle";

                    var text2 = activeLabel,
                        textX = Math.round((width - ctx.measureText(text2).width) / 2),
                        textY = height / 2 - 30;
                    ctx.fillText(text2, textX, textY);

                    ctx.save();
                },
                beforeEvent: function(chart, event, options) {
                    var firstPoint = chart.getElementAtEvent(event)[0];

                    if (firstPoint) {
                        chart.pointIndex = firstPoint._index;
                        chart.pointDataIndex = firstPoint._datasetIndex;
                        chart.pointAvailable = true;
                    }
                }
            };

            if (document.getElementById("trxChart")) {
                var trxChart = document.getElementById("trxChart").getContext("2d");
                var myChart = new Chart(trxChart, {
                    type: "LineWithShadow",
                    options: {
                        plugins: {
                            datalabels: {
                                display: false
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1200,
                            easing: 'linear'
                        },
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    display: true,
                                    lineWidth: 1,
                                    color: "rgba(0,0,0,0.1)",
                                    drawBorder: false
                                },
                                ticks: {
                                    beginAtZero: true,
                                    min: 0,
                                    padding: 20,
                                    userCallback: function(label, index, labels) {
                                        if (Math.floor(label) === label) {
                                            return new Intl.NumberFormat().format(label);
                                        }
                                    },
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                        legend: {
                            display: false
                        },
                        tooltips: chartTooltip
                    },
                    data: {
                        labels: {!! $realization['labels'] !!},
                        datasets: [{
                            label: "",
                            data: {!! $realization['data'] !!},
                            borderColor: themeColor2,
                            pointBackgroundColor: foregroundColor,
                            pointBorderColor: themeColor2,
                            pointHoverBackgroundColor: themeColor2,
                            pointHoverBorderColor: foregroundColor,
                            pointRadius: 4,
                            pointBorderWidth: 2,
                            pointHoverRadius: 5,
                            fill: true,
                            borderWidth: 2,
                            backgroundColor: themeColor2_10
                        }]
                    }
                });
            }
        }
    </script>
@endpush

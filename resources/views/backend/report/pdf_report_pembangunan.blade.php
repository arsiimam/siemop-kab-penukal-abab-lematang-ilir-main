<!DOCTYPE html>

<head>
    <style>
        @page {
            /* margin-top: 20px; */
        }

        body {
            font-size: 9px;
            font-family: Helvetica, sans-serif;
        }

        .fw-bold {
            font-weight: bold;
        }

        .f-8 {
            font-size: 8px;
        }

        .f-10 {
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right !important;
        }

        .text-upper {
            text-transform: uppercase;
        }

        .text-lower {
            text-transform: lowercase;
        }

        .text-capital {
            text-transform: capitalize;
        }

        p {
            margin: 2px;
        }

        .table {
            display: table;
            border-collapse: collapse;
            margin-bottom: 1rem;
            margin-top: 10px;
            font-size: 8px;
        }

        .table tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table.table-bordered th {
            border: .5px #000 solid !important;
            padding: 4px;
        }

        .table.table-bordered td {
            border: .5px #000 solid !important;
            padding: 4px;
        }

        .w-100 {
            width: 100%;
        }

        .w-70 {
            width: 70%;
        }

        .bg-header {
            background-color: #D8E4BC !important;
        }

        .bg-parent {
            background-color: #76933C !important;
        }

        .bg-child {
            background-color: #CCC0DA !important;
        }

        .mtb {
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <div class="text-center fw-bold text-upper">
        <p>LAPORAN</p>
        <p>PEMBANGUNAN {{ $header['type'] }} KABUPATEN PENUKAL ABAB LEMATANG ILIR TAHUN ANGGARAN {{ $tahun }}</p>
        <p>BULAN {{ $month }} {{ $tahun }}</p>
    </div>

    {{-- BODY --}}
    <div class="w-100">

        <div class="mtb">
            <p>NAMA PERANGKAT DAERAH : {{ $header['institute'] }}</p>
            <p>NAMA PROGRAM : {{ $header['program'] }}</p>
            <p>NAMA KEGIATAN : {{ $header['activity'] }}</p>
            <p>NAMA SUB KEGIATAN : {{ $header['sub_activity'] }}</p>
            <p>PEMBANGUNAN {{ $header['type'] }} </p>
        </div>

        <table class="table table-bordered w-100">
            <thead class="bg-header">
                <tr>
                    <th rowspan="2" class="text-center" width="20">No.</th>
                    <th rowspan="2">NAMA PAKET PEKERJAAN</th>
                    <th rowspan="2">PAGU ANGGARAN (Rp)</th>
                    <th rowspan="2">SUMBER DANA</th>
                    <th colspan="4">TARGET</th>
                    <th rowspan="2">TARGET PROGRES (%)</th>
                    {{-- <th rowspan="2">PROGRES PEKERJAAN (%)</th> --}}
                    <th rowspan="2">REALISASI PROGRES (%)</th>
                    <th rowspan="2" style="width: 20px;">DOKUMENTASI</th>
                    <th rowspan="2">NAMA PPK</th>
                    <th rowspan="2">NAMA PPTK</th>
                    <th rowspan="2">PELAKSANA / KONTRAKTOR</th>
                    <th rowspan="2">LOKASI KEGIATAN</th>
                </tr>
                <tr>
                    <th>NOMOR</th>
                    <th>TANGGAL</th>
                    <th>HARGA KONTRAK (Rp.)</th>
                    <th>DURASI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{!! $item[0] !!}</td>
                        <td>{!! $item[1] !!}</td>
                        <td class="text-right">{!! $item[2] !!}</td>
                        <td>{!! $item[3] !!}</td>
                        <td>{!! $item[4] !!}</td>
                        <td>{!! $item[5] !!}</td>
                        <td class="text-right">{!! $item[6] !!}</td>
                        <td>{!! $item[7] !!}</td>
                        <td class="text-center">{!! $item[8] !!}</td>
                        {{-- <td class="text-center">{!! $item[9] !!}</td> --}}
                        <td class="text-center">{!! $item[10] !!}</td>
                        <td class="text-center">
                            <a href="{!! $item[11] !!}">Lihat File</a>
                        </td>
                        <td>{!! $item[12] !!}</td>
                        <td>{!! $item[13] !!}</td>
                        <td>{!! $item[14] !!}</td>
                        <td>{!! $item[15] !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SIGNATURE --}}
    <table class="w-100" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td width="75%"></td>
                <td>
                    <table class="p-5 w-100" border="0">
                        <tbody>
                            <tr>
                                {{-- <td class="text-upper">{{ $signature['institute'] }}</td> --}}
                                <td class="text-upper">
                                    <b>{{ $signature['position'] }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-upper">
                                    <b>{{ settingByUnique('company_city') }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 50px;">
                                    @if ($signature['image'] != null)
                                        <img src="data:image/png;base64,{!! base64_encode(file_get_contents(public_path($signature['image']))) !!}" width="120"
                                            alt="Logo 1">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-upper">
                                    <b>{{ $signature['name'] }}</b>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>{{ $signature['position'] }}</td>
                            </tr> --}}
                            <tr>
                                <td class="text-upper">NIP. {{ $signature['nip'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

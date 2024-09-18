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
            padding: 1px;
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
    </style>
</head>

<body>
    {{-- HEADER --}}
    <div class="text-center fw-bold text-upper">
        <p>LAPORAN</p>
        <p>
            REALISASI FISIK, NON FISIK, DAN KEUANGAN KEGIATAN APBD KABUPATEN PENUKAL ABAB LEMATANG ILIR TAHUN ANGGARAN
            {{ $tahun }} BELANJA OPERASIONAL
        </p>
        <p>BULAN {{ $month }} {{ $tahun }}</p>
    </div>

    {{-- BODY --}}
    <div class="w-100">
        <table class="table table-bordered w-100">
            <thead class="bg-header">
                <tr class="text-upper">
                    <th rowspan="3" class="text-center" width="20">No.</th>
                    <th rowspan="3">Uraian Urusan, Organisasi, Program dan Kegiatan</th>
                    <th rowspan="3">Pagu Indikatif <small>(Rp)</small></th>
                    <th rowspan="3">Target Kinerja <small>(%)</small></th>
                    <th colspan="4" class="text-center">Realisasi</th>
                    <th rowspan="3">PPK</th>
                    <th rowspan="3">PPTK</th>
                    <th rowspan="3">Pelaksana / Kontruktor</th>
                    <th rowspan="3">Harga Kontrak <small>(Rp)</small></th>
                    <th rowspan="3">Lokasi Kegiatan</th>
                </tr>
                <tr class="text-upper">
                    <th rowspan="2">Fisik <small>(%)</small></th>
                    <th rowspan="2">Non Fisik <small>(%)</small></th>
                    <th colspan="2" class="text-center">Keuangan</th>
                </tr>
                <tr class="text-upper">
                    <th class="text-center"><small>(Rp)</small></th>
                    <th class="text-center" width="30"><small>(%)</small></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    @if ($item[13] == 'parent')
                        @php
                            $class = 'bg-parent';
                        @endphp
                    @elseif($item[13] == 'child')
                        @php
                            $class = 'bg-child';
                        @endphp
                    @else
                        @php
                            $class = '';
                        @endphp
                    @endif

                    <tr class="{{ $class }}">
                        <td class="text-center">{!! $item[0] !!}</td>
                        <td>{!! $item[1] !!}</td>
                        <td class="text-right">{!! $item[2] !!}</td>
                        <td class="text-center">{!! $item[3] !!}</td>
                        <td class="text-center">{!! $item[4] !!}</td>
                        <td class="text-center">{!! $item[5] !!}</td>
                        <td class="text-right">{!! $item[6] !!}</td>
                        <td class="text-center">{!! $item[7] !!}</td>
                        <td>{!! $item[8] !!}</td>
                        <td>{!! $item[9] !!}</td>
                        <td>{!! $item[10] !!}</td>
                        <td class="text-right">{!! $item[11] !!}</td>
                        <td>{!! $item[12] !!}</td>
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
                                <td class="text-upper">{{ settingByUnique('company_name') }}</td>
                            </tr>
                            <tr>
                                <td class="text-upper">{{ settingByUnique('company_city') }}</td>
                            </tr>
                            <tr>
                                <td style="height: 50px;">
                                    @if (settingByUnique('setting_signature_file') != null)
                                        <img src="data:image/png;base64,{!! base64_encode(file_get_contents(public_path(settingByUnique('setting_signature_file')))) !!}" width="120"
                                            alt="Logo 1">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ settingByUnique('setting_name_signature') }}</td>
                            </tr>
                            <tr>
                                <td>{{ settingByUnique('setting_signature_position') }}</td>
                            </tr>
                            <tr>
                                <td class="text-upper">NIP. {{ settingByUnique('setting_signature_nip') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

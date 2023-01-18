<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>
    <link rel="stylesheet" href="{{ asset('backend_pemda/dist/css/adminlte.min.css') }}">
</head>
<body>
    <h3 class="text-center">Laporan Daily Activity Karyawan | {{ $karyawan->name }} - {{ $karyawan->jabatan->jabatan }}</h3>
    <h4 class="text-center">
        Tanggal {{ formatTanggal($awal, false) }}
        s/d Tanggal {{ formatTanggal($akhir, false) }}

    </h4>

    <table class="table table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="5%">Absen</th>
                    <th width="5%">Grooming</th>
                    <th width="5%">Kebersihan</th>
                    <th width="5%">Briefing</th>
                    <th width="5%">Omset</th>
                    <th width="5%">COD</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td width="5%">{{ $row['DT_RowIndex'] }}</td>
                        <td>{{ $row['tanggal'] }}</td>
                        <td>@if ( $row['absen'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['absen'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['absen'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                        <td>@if ( $row['grooming'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['grooming'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['grooming'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                        <td>@if ( $row['cleaning'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['cleaning'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['cleaning'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                        <td>@if ( $row['briefing'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['briefing'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['briefing'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                        <td>@if ( $row['omset'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['omset'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['omset'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                        <td>@if ( $row['cod'] == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row['cod'] == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row['cod'] == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif</td>
                    </tr>  
                @endforeach
            </tbody>
    </table>
</body>
</html>
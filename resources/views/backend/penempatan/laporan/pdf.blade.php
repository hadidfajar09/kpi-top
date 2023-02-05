<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Omset</title>
    <link rel="stylesheet" href="{{ asset('backend_pemda/dist/css/adminlte.min.css') }}">
</head>
<body>
    <h3 class="text-center">Laporan Daily Omset | {{ $penempatan->nama }} : Rp.{{ formatUang($nominal_total) }}</h3>
    <h4 class="text-center">
        Tanggal {{ formatTanggal($awal, false) }}
        s/d Tanggal {{ formatTanggal($akhir, false) }}

    </h4>

    <table class="table table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="10%">Sales</th>
                    <th width="15%">Nominal</th>
                    <th>Catatan</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($omset as $row)
                    <tr>
                        <td width="5%">{{ $no++ }}</td>
                        <td>{{ formatTanggal($row->tanggal_setor) }}</td>
                       
                        <td><span class="badge badge-danger">{{ $row->user->name }}</span></td>
                        <td><span class="badge badge-danger">{{ formatUang($row->nominal) }}</span></td>
                        <td>{{ $row->catatan }}</td>
                        <td>
                            @if ( $row->status == 0 )
                            <span class="badge badge-danger">Ditolak</span>
                        @elseif( $row->status == 1 )
                        <span class="badge badge-success">Diterima</span>
                        @elseif( $row->status == 2 )
                        <span class="badge badge-light">Pending</span>
                        @else
                        <span class="badge badge-dark">Kosong</span>
                        @endif
                        </td>
                       
                    </tr>  
                @endforeach
            </tbody>
    </table>
</body>
</html>
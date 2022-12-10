<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIMOJI | Cetak Kartu Pelanggan</title>
    <link rel="icon" href="{{ asset($setting->path_logo) }}" type="image/*">

    <style>
        .box {
            position: relative;
        }
        .card {
           width: 85.60mm;;
        }
    
       
        .name {
            position: absolute;
            top: 61pt;
            left: 62pt;
            font-size: 9pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: rgb(0, 0, 0) !important;
        }
        .no_kk {
            position: absolute;
            top: 76pt;
            left: 62pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(0, 0, 0) !important;
        }
        .alamat {
            position: absolute;  
            top: 91pt;
            left: 62pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(0, 0, 0) !important;
        }

        .kode_pelanggan {
            position: absolute;
            top: 125pt;
            right:9pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(255, 255, 255) !important;
        }
        .barcode {
            position: absolute;
            top: 105pt;
            left: .860rem;
            border: 1px solid #fff;
            padding: .5px;
            background: #fff;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
    
</head>
<body>
    <section style="border: 1px solid #fff">
        <table width="100%">
            @foreach ($datapelanggan as $key => $data)
                <tr>
                    @foreach ($data as $row)
                        <td class="text-center" width="50%">
                            <div class="box">
                                <img  src="{{ asset($setting->path_pelanggan) }}" alt="card"  width="100%">
                                {{-- <img  src="{{ asset($setting->path_pelanggan_bg) }}" alt="card"  width="100%"> --}}
                               
                                <div class="name">{{ $row->nama_pelanggan }}</div>
                                <div class="no_kk">{{ $row->kecamatan->nama_kecamatan }}</div >
                                <div class="alamat">{{ $row->alamat }}</div>
                                <div class="kode_pelanggan">{{ $row->kode_pelanggan }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$row->qrcode",'QRCODE') }}" alt="qrcode" bg-color="#fff" height="45" width="45">
                                </div>
                            </div>
                        </td><br>

                        @if (count($datapelanggan) == 1){
                            <td class="text-center" style="width: 50%;"></td>
                        }
                            
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
    </script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js">
    </script>
</body>
</html>

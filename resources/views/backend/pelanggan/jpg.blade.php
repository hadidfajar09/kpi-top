<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIMOJI | Cetak Kartu Pelanggan</title>
    
    <link rel="icon" href="{{ asset($setting->path_logo) }}" type="image/*">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    

    <style>
        .box {
            position: relative;
        }
        .card {
           width: 85.60mm;
        }
       
        .name {
            position: absolute;
            top: 75pt;
            left: 432pt;
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: rgb(0, 0, 0) !important;
        }
        .no_kk {
            position: absolute;
            top: 94pt;
            left: 432pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(0, 0, 0) !important;
        }
        .alamat {
            position: absolute;  
            top: 111pt;
            left: 432pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(0, 0, 0) !important;
        }

        .kode_pelanggan {
            position: absolute;
            top: 155pt;
            left:602pt;
            font-size: 9pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(255, 255, 255) !important;
        }
        .barcode {
            position: absolute;
            top: 135pt;
            left: 370pt;
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

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
</script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
</head>
<body onload = "autoClick();">
    <section class="d-flex align-items-center">
        <div class="col text-center">
            <table width="100%">
                <tr>
                        <td id="htmlContent" class="text-center">
                            <div class="box ">
                                    <img src="{{ asset($setting->path_pelanggan) }}" alt="card" width="30%">
                                    <div class="name">{{ $pelanggan[0]->nama_pelanggan }}</div>
                                    <div class="no_kk">{{ $pelanggan[0]->kecamatan->nama_kecamatan }}</div >
                                    <div class="alamat">{{ $pelanggan[0]->alamat }}</div>
                                    <div class="kode_pelanggan">{{ $pelanggan[0]->kode_pelanggan }}</div>
                                    <div class="barcode">
                                        <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$pelanggan[0]->qrcode",'QRCODE') }}" alt="qrcode" bg-color="#fff" height="45" width="45">
                                    </div>
                               
                                 <!-- <img  src=#" alt="card"  width="100%"> -->
                               
                               
                            </div>
                        </td><br>

                </tr>
        </table>
        <a class="btn btn-success" id="download">Download JPG</a>
    </div>
    </section>

    

    <script type="text/javascript">
        function autoClick(){
          $("#download").click();
        }
  
        $(document).ready(function(){
          var element = $("#htmlContent");
  
          $("#download").on('click', function(){
  
            html2canvas(element, {
              onrendered: function(canvas) {
                var imageData = canvas.toDataURL("image/jpg");
                var newData = imageData.replace(/^data:image\/jpg/, "data:application/octet-stream");
                $("#download").attr("download", "image.jpg").attr("href", newData);
              }
            });
  
          });
        });
      </script>
</body>
</html>
